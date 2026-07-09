@extends('layouts.admin', ['title' => 'Daily Reports', 'heading' => 'Visitor Reports'])

@section('content')
<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Analytics & History</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Daily Visitor Summaries</h1>
        </div>
        
        <form class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs font-bold text-slate-500">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="mt-1 block h-[42px] rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="mt-1 block h-[42px] rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500">Status</label>
                <select name="status" class="mt-1 block h-[42px] rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-bold text-white shadow hover:bg-slate-800">
                <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                Generate Report
            </button>
        </form>
        <a href="{{ route('admin.reports.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'status' => request('status')]) }}" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-5 text-sm font-bold text-emerald-700 shadow-sm hover:bg-emerald-100">
            <span class="material-symbols-outlined text-[18px]">download</span>
            Export to Excel
        </a>
    </div>
</section>

<section class="mb-6 grid gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6">
    @php
        $cards = [
            ['Total Visitors', $totalVisitors, 'text-slate-600', 'bg-slate-100'],
            ['Pending', $pendingVisitors, 'text-amber-600', 'bg-amber-50'],
            ['Approved', $approvedVisitors, 'text-blue-600', 'bg-blue-50'],
            ['Checked In', $checkedInVisitors, 'text-emerald-600', 'bg-emerald-50'],
            ['Checked Out', $checkedOutVisitors, 'text-slate-700', 'bg-slate-200'],
            ['Rejected', $rejectedVisitors, 'text-rose-600', 'bg-rose-50'],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $textColor, $bgColor])
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-extrabold {{ $textColor }}">{{ $value }}</p>
        </div>
    @endforeach
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-4">Visitor No</th>
                    <th class="px-5 py-4">Name / IC</th>
                    <th class="px-5 py-4">Company</th>
                    <th class="px-5 py-4">Host / Dept</th>
                    <th class="px-5 py-4">Date</th>
                    <th class="px-5 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($visitors as $visitor)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-slate-500">{{ $visitor->visitor_no }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.visitors.show', $visitor) }}" class="font-extrabold text-blue-700 hover:underline">{{ $visitor->full_name }}</a>
                            <p class="text-xs text-slate-500">IC: {{ $visitor->ic_passport_no }}</p>
                        </td>
                        <td class="px-5 py-4 text-slate-700">{{ $visitor->company_name }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-700">{{ $visitor->person_to_meet }}</p>
                            <p class="text-xs text-slate-500">{{ $visitor->department }}</p>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-600">{{ $visitor->visit_date?->format('d M Y') }}</td>
                        <td class="px-5 py-4"><x-status-badge :status="$visitor->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center font-semibold text-slate-500">No visitors found for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
