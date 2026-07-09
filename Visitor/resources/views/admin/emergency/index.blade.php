@extends('layouts.admin', ['title' => 'Emergency List', 'heading' => 'On-Site Visitors'])

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm font-semibold text-slate-600">The following visitors are currently checked in and have not checked out.</p>
    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800">
        <span class="material-symbols-outlined text-[20px]">print</span> Print List
    </button>
</div>

<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-indigo-50 text-xs uppercase tracking-wide text-indigo-800 border-b border-indigo-100">
                <tr>
                    <th class="px-5 py-4">Visitor No</th>
                    <th class="px-5 py-4">Name</th>
                    <th class="px-5 py-4">IC / Passport</th>
                    <th class="px-5 py-4">Phone</th>
                    <th class="px-5 py-4">Department / Host</th>
                    <th class="px-5 py-4">Checked In At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($visitors as $visitor)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-slate-500">{{ $visitor->visitor_no }}</td>
                        <td class="px-5 py-4 font-extrabold text-slate-900">{{ $visitor->full_name }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $visitor->ic_passport_no }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $visitor->phone }}</td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">{{ $visitor->department }}</p>
                            <p class="text-xs text-slate-500">{{ $visitor->person_to_meet }}</p>
                        </td>
                        <td class="px-5 py-4 font-semibold text-emerald-600">
                            {{ $visitor->checked_in_at?->format('H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center font-semibold text-slate-500">No visitors currently on-site.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .overflow-hidden, .overflow-hidden * { visibility: visible; }
        .overflow-hidden { position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; }
        button { display: none !important; }
    }
</style>
@endsection
