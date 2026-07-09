<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitorRegistrationRequest;
use App\Models\Visitor;
use App\Models\VisitorFormField;
use App\Models\Watchlist;
use App\Services\VisitorNumberService;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicVisitorRegistrationController extends Controller
{
    public function create(): View
    {
        return view('visitor-registration.create', [
            'customFields' => VisitorFormField::orderedActive(),
        ]);
    }

    public function store(StoreVisitorRegistrationRequest $request, VisitorNumberService $numbers, ActivityLogService $logger): RedirectResponse
    {
        $isBlacklisted = Watchlist::isBlacklisted($request->validated('ic_passport_no'));

        $attempts = 5;
        $visitor = null;

        while ($attempts > 0) {
            try {
                $visitor = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $numbers, $isBlacklisted) {
                    $visitorNo = $numbers->generate();
                    return Visitor::create([
                        ...$request->validated(),
                        'visitor_no' => $visitorNo,
                        'status' => 'pending',
                        'is_blacklisted' => $isBlacklisted,
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

        $logger->log('created', $visitor, null, 'pending', 'Public visitor registration submitted.');

        return redirect()
            ->route('visitor-success')
            ->with('visitor_name', $visitor->full_name)
            ->with('visitor_no', $visitor->visitor_no)
            ->with('success', 'Visitor registration submitted successfully.');
    }

    public function thankYou(): View
    {
        return view('visitor-registration.thank-you', [
            'visitorName' => session('visitor_name'),
            'visitorNo' => session('visitor_no'),
        ]);
    }
}
