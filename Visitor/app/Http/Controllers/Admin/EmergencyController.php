<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmergencyController extends Controller
{
    public function index(Request $request): View
    {
        $visitors = Visitor::where('status', 'checked_in')
            ->whereNull('checked_out_at')
            ->latest('checked_in_at')
            ->get();

        return view('admin.emergency.index', [
            'visitors' => $visitors,
        ]);
    }
}
