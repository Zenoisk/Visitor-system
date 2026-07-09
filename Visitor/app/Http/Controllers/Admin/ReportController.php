<?php

namespace App\Http\Controllers\Admin;

use App\Exports\VisitorsExport;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->date('start_date') ?? now()->startOfDay();
        $endDate = $request->date('end_date') ?? now()->endOfDay();

        $query = Visitor::whereBetween('visit_date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $visitors = $query->latest('visit_date')->get();

        $statusCounts = $visitors->groupBy('status')->map->count();

        return view('admin.reports.index', [
            'visitors' => $visitors,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalVisitors' => $visitors->count(),
            'pendingVisitors' => $statusCounts->get('pending', 0),
            'approvedVisitors' => $statusCounts->get('approved', 0),
            'checkedInVisitors' => $statusCounts->get('checked_in', 0),
            'checkedOutVisitors' => $statusCounts->get('checked_out', 0),
            'rejectedVisitors' => $statusCounts->get('rejected', 0),
            'statuses' => Visitor::STATUSES,
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $startDate = $request->date('start_date') ?? now()->startOfDay();
        $endDate = $request->date('end_date') ?? now()->endOfDay();
        $status = $request->status;

        $filename = 'AIROD_Visitors_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.xlsx';

        return Excel::download(new VisitorsExport($startDate, $endDate, $status), $filename);
    }
}
