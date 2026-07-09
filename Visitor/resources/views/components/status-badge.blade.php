@props(['status'])
@php
    $classes = [
        'pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'approved' => 'bg-blue-100 text-blue-800 ring-blue-200',
        'checked_in' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'checked_out' => 'bg-slate-200 text-slate-800 ring-slate-300',
        'rejected' => 'bg-rose-100 text-rose-800 ring-rose-200',
    ][$status] ?? 'bg-slate-100 text-slate-800 ring-slate-200';
@endphp
<span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide ring-1 {{ $classes }}">{{ str_replace('_', ' ', $status) }}</span>
