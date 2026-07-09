<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    public function index(): View
    {
        $entries = Watchlist::with('creator')->latest()->paginate(20);

        return view('admin.watchlists.index', [
            'entries' => $entries,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ic_passport_no' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9\-\/]+$/', 'unique:watchlists,ic_passport_no'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        Watchlist::create([
            ...$data,
            'ic_passport_no' => strtoupper(trim($data['ic_passport_no'])),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'IC/Passport number added to the watchlist.');
    }

    public function destroy(Watchlist $watchlist): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Unauthorized action.');

        $watchlist->delete();

        return back()->with('success', 'Entry removed from the watchlist.');
    }
}
