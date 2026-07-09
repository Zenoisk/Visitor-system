<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Visitor;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(string $action, Visitor $visitor, ?string $oldStatus = null, ?string $newStatus = null, ?string $description = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'visitor_id' => $visitor->id,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'description' => $description,
        ]);
    }
}
