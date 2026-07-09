<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorRequest;
use App\Models\Visitor;
use App\Models\VisitorFormField;
use App\Services\VisitorNumberService;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitorController extends Controller
{
    public function index(Request $request): View
    {
        $visitors = Visitor::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('visitor_no', 'like', "%{$search}%")
                        ->orWhere('ic_passport_no', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('person_to_meet', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('visit_date'), fn ($query) => $query->whereDate('visit_date', $request->visit_date))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.visitors.index', [
            'visitors' => $visitors,
            'statuses' => Visitor::STATUSES,
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        return view('admin.visitors.create', [
            'statuses' => Visitor::STATUSES,
            'customFields' => VisitorFormField::orderedActive(),
        ]);
    }

    public function store(VisitorRequest $request, VisitorNumberService $numbers, ActivityLogService $logger): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        $attempts = 5;
        $visitor = null;

        while ($attempts > 0) {
            try {
                $visitor = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $numbers) {
                    $visitorNo = $numbers->generate();
                    return Visitor::create([
                        ...$request->validated(),
                        'visitor_no' => $visitorNo,
                    ]);
                });
                break;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() === '23000' || str_contains($e->getMessage(), '1062')) {
                    $attempts--;
                    if ($attempts === 0) {
                        throw $e;
                    }
                    usleep(50000); // 50ms
                } else {
                    throw $e;
                }
            }
        }

        $logger->log('created', $visitor, null, $visitor->status, 'Visitor created manually by admin.');

        return redirect()->route('admin.visitors.show', $visitor)->with('success', 'Visitor record created.');
    }

    public function show(Visitor $visitor): View
    {
        return view('admin.visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor): View
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        return view('admin.visitors.edit', [
            'visitor' => $visitor,
            'statuses' => Visitor::STATUSES,
            'customFields' => VisitorFormField::orderedActive(),
        ]);
    }

    public function update(VisitorRequest $request, Visitor $visitor, ActivityLogService $logger): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        $oldStatus = $visitor->status;
        $visitor->update($request->validated());
        
        $logger->log('updated', $visitor, $oldStatus, $visitor->status, 'Visitor details updated.');

        return redirect()->route('admin.visitors.show', $visitor)->with('success', 'Visitor record updated.');
    }

    public function destroy(Visitor $visitor, ActivityLogService $logger): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        $logger->log('deleted', $visitor, $visitor->status, null, 'Visitor record deleted.');
        
        $visitor->delete();

        return redirect()->route('admin.visitors.index')->with('success', 'Visitor record deleted.');
    }

    public function updateStatus(Request $request, Visitor $visitor, ActivityLogService $logger): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Visitor::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldStatus = $visitor->status;
        $newStatus = $data['status'];

        // Prevent invalid transitions
        if ($oldStatus === 'checked_out' && in_array($newStatus, ['checked_in', 'pending', 'approved'])) {
            return back()->with('error', 'Cannot transition from checked out to '.$newStatus);
        }
        if ($oldStatus === 'rejected' && $newStatus === 'checked_in') {
            return back()->with('error', 'Cannot check in a rejected visitor.');
        }

        if ($newStatus === 'checked_in' && ! $visitor->checked_in_at) {
            $data['checked_in_at'] = now();
        }

        if ($newStatus === 'checked_out' && ! $visitor->checked_out_at) {
            $data['checked_out_at'] = now();
        }

        $visitor->update($data);
        
        if ($oldStatus !== $newStatus) {
            $logger->log('status_changed', $visitor, $oldStatus, $newStatus, 'Status updated via action button.');
        }

        return back()->with('success', 'Visitor status updated.');
    }
}
