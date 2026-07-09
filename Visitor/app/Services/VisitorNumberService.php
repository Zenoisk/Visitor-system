<?php

namespace App\Services;

use App\Models\Visitor;

class VisitorNumberService
{
    public function generate(): string
    {
        $prefix = 'VST-'.now()->format('Ymd');
        
        // Loop to ensure uniqueness, though extremely unlikely to collide if count is correct,
        // it handles race conditions safely.
        $count = Visitor::whereDate('created_at', now())
            ->lockForUpdate()
            ->count();
        
        do {
            $count++;
            $visitorNo = $prefix.'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        } while (Visitor::where('visitor_no', $visitorNo)->exists());

        return $visitorNo;
    }
}
