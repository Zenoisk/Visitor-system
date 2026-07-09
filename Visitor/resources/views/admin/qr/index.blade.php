@extends('layouts.admin', ['title' => 'QR Code', 'heading' => 'Visitor Registration QR Code'])

@section('content')
<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold">Public visitor registration link</h3>
        <p class="mt-2 text-sm text-slate-600">Display this QR code at the AIROD security counter so visitors can register on arrival.</p>
        <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-medium text-slate-700">{{ $registrationUrl }}</div>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
        <div class="mx-auto flex h-80 w-80 items-center justify-center rounded-xl border border-slate-200 bg-white p-3 [&>svg]:h-full [&>svg]:w-full">
            {!! $qrSvg !!}
        </div>
        <button onclick="window.print()" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Print QR Code
        </button>
    </div>
</div>
<style>
    @media print {
        body * { visibility: hidden; }
        .rounded-xl.border.bg-white.text-center, .rounded-xl.border.bg-white.text-center * { visibility: visible; }
        .rounded-xl.border.bg-white.text-center { position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; }
        button { display: none !important; }
    }
</style>
@endsection
