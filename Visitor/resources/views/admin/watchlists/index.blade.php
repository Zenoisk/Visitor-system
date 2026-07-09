@extends('layouts.admin', ['title' => 'Security Watchlist', 'heading' => 'Visitor Watchlist'])

@section('content')
<div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]">
    {{-- Watchlist Table --}}
    <div>
        <section class="mb-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-rose-700">Security Module</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Blocked IC / Passport Numbers</h1>
            <p class="mt-2 text-sm font-medium text-slate-500">Visitors registering with a listed IC/Passport will be silently flagged on the admin dashboard.</p>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-rose-50 text-xs uppercase tracking-wide text-rose-800 border-b border-rose-100">
                        <tr>
                            <th class="px-5 py-4">IC / Passport No</th>
                            <th class="px-5 py-4">Reason</th>
                            <th class="px-5 py-4">Added By</th>
                            <th class="px-5 py-4">Date Added</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($entries as $entry)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 font-extrabold uppercase text-slate-900">{{ $entry->ic_passport_no }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $entry->reason ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if ($entry->creator)
                                        <span class="font-semibold text-slate-700">{{ $entry->creator->name }}</span>
                                    @else
                                        <span class="text-slate-400">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-600">{{ $entry->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4 text-right">
                                    @if (auth()->user()?->isAdmin())
                                        <form method="POST" action="{{ route('admin.watchlists.destroy', $entry) }}" onsubmit="return confirm('Remove this entry from the watchlist?')">
                                            @csrf @method('DELETE')
                                            <button class="rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-200">Remove</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center font-semibold text-slate-500">No entries in the watchlist.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-5">{{ $entries->links() }}</div>
    </div>

    {{-- Add New Entry Form --}}
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm self-start">
        <h2 class="text-lg font-extrabold text-slate-950">Add to Watchlist</h2>
        <p class="mt-1 text-sm font-medium text-slate-500">Flag an IC or Passport number. Future registrations matching this number will be marked as blacklisted.</p>

        <form method="POST" action="{{ route('admin.watchlists.store') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label class="text-xs font-bold text-slate-500" for="ic_passport_no">IC / Passport Number</label>
                <input id="ic_passport_no" name="ic_passport_no" value="{{ old('ic_passport_no') }}" type="text" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm font-semibold uppercase text-slate-700 shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="e.g. 900101012345" required>
                @error('ic_passport_no') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500" for="reason">Reason (Optional)</label>
                <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-rose-500 focus:ring-rose-500" placeholder="Why is this person blacklisted?">{{ old('reason') }}</textarea>
                @error('reason') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>
            <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 py-3 font-semibold text-white shadow hover:bg-rose-700">
                <span class="material-symbols-outlined text-[20px]">shield</span>
                Add to Watchlist
            </button>
        </form>
    </div>
</div>
@endsection
