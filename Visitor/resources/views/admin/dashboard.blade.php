@extends('layouts.admin', ['title' => 'Dashboard', 'heading' => 'Visitor Dashboard'])

@section('content')
@php
    $cards = [
        ['Visitors Today', $totalToday, 'calendar_month', 'from-blue-600 to-sky-500', 'Scheduled for today'],
        ['Pending Review', $pendingVisitors, 'hourglass_top', 'from-amber-500 to-orange-400', 'Awaiting approval'],
        ['Approved', $approvedVisitors, 'verified', 'from-blue-500 to-indigo-500', 'Cleared by staff'],
        ['Checked In', $checkedInVisitors, 'login', 'from-emerald-500 to-teal-500', 'Total checked in today'],
        ['Checked Out', $checkedOutVisitors, 'logout', 'from-slate-600 to-slate-500', 'Visit completed'],
        ['Currently Inside', count($currentlyInsideVisitors), 'badge', 'from-indigo-600 to-violet-500', 'On-site right now'],
    ];
@endphp

<section class="relative mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-[#061426] via-[#0c2f55] to-[#0056b3] p-6 text-white shadow-2xl shadow-blue-950/20 md:p-7">
    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-sky-300/20 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 right-1/4 h-px w-72 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] lg:items-center">
        <div class="relative">
            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-200">AIROD Visitor Operations</p>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight md:text-4xl">Security Counter Overview</h1>
            <p class="mt-3 max-w-2xl text-sm font-semibold leading-6 text-blue-50/90">
                Monitor today's visitors, review pending requests, and update check-in status from one control surface.
            </p>
        </div>

        <div class="relative rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur">
            <p class="text-sm font-extrabold text-blue-100">Total Visitor Records</p>
            <p class="mt-2 text-5xl font-extrabold">{{ $totalVisitors }}</p>
            <p class="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-blue-200">All-time database count</p>
        </div>
    </div>
</section>

<section class="grid gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
    @foreach ($cards as [$label, $value, $icon, $gradient, $hint])
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/70 transition hover:-translate-y-0.5 hover:border-blue-100 hover:shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-extrabold text-slate-600">{{ $label }}</p>
                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-slate-950">{{ $value }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $gradient }} text-white shadow-lg">
                    <span class="material-symbols-outlined">{{ $icon }}</span>
                </div>
            </div>
            <p class="mt-4 text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400">{{ $hint }}</p>
        </div>
    @endforeach
</section>

<section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200/70">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700">Live Queue</p>
                <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-950">Today's Visitors</h3>
            </div>
            <a href="{{ route('admin.visitors.index', ['visit_date' => now()->toDateString()]) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">
                View today
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="mt-5 space-y-3">
            @forelse ($todaysVisitors as $visitor)
                <a href="{{ route('admin.visitors.show', $visitor) }}" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-blue-200 hover:bg-blue-50/50 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-950">{{ $visitor->full_name }}</p>
                            <p class="text-sm font-medium text-slate-500">{{ $visitor->company_name }} / {{ $visitor->person_to_meet }}</p>
                        </div>
                    </div>
                    <x-status-badge :status="$visitor->status" />
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-400">event_available</span>
                    <p class="mt-2 font-bold text-slate-600">No visitors scheduled for today.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200/70">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-indigo-700">Live Status</p>
                <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-950">Currently Inside</h3>
            </div>
            <a href="{{ route('admin.emergency.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-50 px-4 py-2.5 text-sm font-extrabold text-indigo-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-100 hover:shadow-md">
                Emergency List
                <span class="material-symbols-outlined text-[18px]">emergency</span>
            </a>
        </div>

        <div class="mt-5 space-y-3">
            @forelse ($currentlyInsideVisitors as $visitor)
                <a href="{{ route('admin.visitors.show', $visitor) }}" class="flex flex-col gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 transition hover:border-indigo-300 hover:bg-indigo-100/50 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-indigo-700 shadow-sm">
                            <span class="material-symbols-outlined">badge</span>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-950">{{ $visitor->full_name }}</p>
                            <p class="text-sm font-medium text-slate-500">In since {{ $visitor->checked_in_at?->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-indigo-700">{{ $visitor->department }}</p>
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-400">meeting_room</span>
                    <p class="mt-2 font-bold text-slate-600">No visitors currently on-site.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200/70">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700">Latest Submissions</p>
                <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-950">Recent Records</h3>
            </div>
            <a href="{{ route('admin.visitors.index') }}" class="text-sm font-extrabold text-blue-700 hover:text-blue-900">View all</a>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-xs uppercase tracking-[0.08em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Visitor</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($recentVisitors as $visitor)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.visitors.show', $visitor) }}" class="font-extrabold text-blue-700">{{ $visitor->full_name }}</a>
                                <p class="text-xs font-medium text-slate-500">{{ $visitor->company_name }}</p>
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-600">{{ $visitor->visit_date?->format('d M') }}</td>
                            <td class="px-4 py-3"><x-status-badge :status="$visitor->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center font-semibold text-slate-500">No visitor submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200/70">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700">Actions</p>
            <h3 class="mt-1 text-xl font-extrabold tracking-tight text-slate-950">Quick Links</h3>
        </div>
        <div class="mt-5 grid grid-cols-2 gap-4">
            @php
                $isAdmin = auth()->user()?->role === 'admin';
            @endphp
            <a href="{{ route('admin.visitors.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                <span class="material-symbols-outlined text-4xl text-blue-600">groups</span>
                <span class="font-bold text-slate-700">All Visitors</span>
            </a>
            <a href="{{ route('admin.qr.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                <span class="material-symbols-outlined text-4xl text-blue-600">qr_code_2</span>
                <span class="font-bold text-slate-700">QR Code</span>
            </a>
            @if ($isAdmin)
                <a href="{{ route('admin.visitors.create') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                    <span class="material-symbols-outlined text-4xl text-blue-600">person_add</span>
                    <span class="font-bold text-slate-700">Add Visitor</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                    <span class="material-symbols-outlined text-4xl text-blue-600">analytics</span>
                    <span class="font-bold text-slate-700">Reports</span>
                </a>
            @else
                <a href="{{ route('admin.emergency.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                    <span class="material-symbols-outlined text-4xl text-blue-600">emergency</span>
                    <span class="font-bold text-slate-700">Emergency List</span>
                </a>
                <a href="{{ route('admin.watchlists.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition hover:-translate-y-1 hover:border-blue-300 hover:bg-blue-50 hover:shadow-lg">
                    <span class="material-symbols-outlined text-4xl text-blue-600">shield</span>
                    <span class="font-bold text-slate-700">Watchlist</span>
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
