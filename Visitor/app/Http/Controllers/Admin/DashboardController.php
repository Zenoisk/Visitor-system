<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statusCounts = Visitor::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'totalToday' => Visitor::whereDate('visit_date', today())->count(),
            'totalVisitors' => Visitor::count(),
            'pendingVisitors' => (int) $statusCounts->get('pending', 0),
            'approvedVisitors' => (int) $statusCounts->get('approved', 0),
            'checkedInVisitors' => (int) $statusCounts->get('checked_in', 0),
            'checkedOutVisitors' => Visitor::where('status', 'checked_out')->whereDate('checked_out_at', today())->count(),
            'rejectedVisitors' => (int) $statusCounts->get('rejected', 0),
            'todaysVisitors' => Visitor::whereDate('visit_date', today())->latest()->take(6)->get(),
            'recentVisitors' => Visitor::latest()->take(8)->get(),
            'currentlyInsideVisitors' => Visitor::where('status', 'checked_in')->whereNull('checked_out_at')->latest('checked_in_at')->take(6)->get(),
            'statusCounts' => $statusCounts,
        ]);
    }
}
