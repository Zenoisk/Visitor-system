<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with(['user', 'visitor'])
            ->latest()
            ->paginate(20);

        return view('admin.activity-logs.index', [
            'logs' => $logs,
        ]);
    }
}
