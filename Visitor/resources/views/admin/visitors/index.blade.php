@extends('layouts.admin', ['title' => 'Visitors', 'heading' => 'Visitor Records'])

@section('content')
<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Visitor database</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Search, filter, and manage visitors</h1>
        </div>
        <a href="{{ route('admin.visitors.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-700">
            <span class="material-symbols-outlined text-[19px]">person_add</span>
            Add Visitor
        </a>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.visitors.index', ['visit_date' => now()->toDateString()]) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ request('visit_date') === now()->toDateString() && !request('status') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Today</a>
        <a href="{{ route('admin.visitors.index', ['status' => 'pending']) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Pending</a>
        <a href="{{ route('admin.visitors.index', ['status' => 'approved']) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ request('status') === 'approved' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Approved</a>
        <a href="{{ route('admin.visitors.index', ['status' => 'checked_in']) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ request('status') === 'checked_in' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Inside</a>
        <a href="{{ route('admin.visitors.index', ['status' => 'checked_out', 'visit_date' => now()->toDateString()]) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ request('status') === 'checked_out' && request('visit_date') === now()->toDateString() ? 'bg-slate-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Checked Out Today</a>
        <a href="{{ route('admin.visitors.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ !request('visit_date') && !request('status') && !request('search') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All Records</a>
    </div>

    <form class="mt-5 grid gap-3 lg:grid-cols-[1.4fr_0.8fr_0.8fr_auto]">
        <div class="relative">
            <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input name="search" value="{{ request('search') }}" placeholder="Search name, IC, company, host..." class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-sm font-semibold text-slate-900 shadow-sm outline-none transition placeholder:text-slate-500 hover:border-blue-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>
        <select name="status" class="rounded-xl border border-slate-300 bg-white py-3 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <input name="visit_date" type="date" value="{{ request('visit_date') }}" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm outline-none transition hover:border-blue-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md">
            <span class="material-symbols-outlined text-[19px]">filter_alt</span>
            Filter
        </button>
    </form>
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-4">Visitor</th>
                    <th class="px-5 py-4">Company</th>
                    <th class="px-5 py-4">Host</th>
                    <th class="px-5 py-4">Department</th>
                    <th class="px-5 py-4">Visit Date</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($visitors as $visitor)
                    <tr class="{{ $visitor->is_blacklisted ? 'bg-rose-50/60 hover:bg-rose-100/60' : 'hover:bg-blue-50/40' }} transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $visitor->is_blacklisted ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }}">
                                    <span class="material-symbols-outlined text-[21px]">{{ $visitor->is_blacklisted ? 'gpp_bad' : 'person' }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.visitors.show', $visitor) }}" class="font-extrabold {{ $visitor->is_blacklisted ? 'text-rose-700' : 'text-blue-700' }}">{{ $visitor->full_name }}</a>
                                        @if ($visitor->is_blacklisted)
                                            <span class="rounded bg-rose-600 px-1.5 py-0.5 text-[10px] font-bold text-white">BLACKLISTED</span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500">{{ $visitor->visitor_no }} / IC {{ $visitor->ic_passport_no }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ $visitor->company_name }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ $visitor->person_to_meet }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $visitor->department }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-600">{{ $visitor->visit_date?->format('d M Y') }}</td>
                        <td class="px-5 py-4"><x-status-badge :status="$visitor->status" /></td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($visitor->status === 'pending')
                                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-200">Approve</button>
                                    </form>
                                @elseif($visitor->status === 'approved')
                                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="checked_in">
                                        <button class="rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-200">Check In</button>
                                    </form>
                                @elseif($visitor->status === 'checked_in')
                                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="checked_out">
                                        <button class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-300">Check Out</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.visitors.show', $visitor) }}" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-100 border border-slate-200">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center font-semibold text-slate-500">No visitors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="mt-5">{{ $visitors->links() }}</div>
@endsection
