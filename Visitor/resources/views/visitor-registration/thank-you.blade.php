@extends('layouts.guest', ['title' => 'Registration Successful | AIROD'])

@section('content')
@php
    $backgroundImage = collect(['jpg', 'png', 'jpeg', 'webp'])
        ->map(fn ($extension) => "images/airod-registration-bg.{$extension}")
        ->first(fn ($path) => file_exists(public_path($path))) ?? 'images/airod-registration-bg.jpg';
@endphp

<div class="fixed inset-0 -z-20 bg-[#f6faff]">
    <img src="{{ asset($backgroundImage) }}" alt="" class="h-full w-full object-cover opacity-25">
</div>
<div class="fixed inset-0 -z-10 bg-gradient-to-b from-[#f6faff]/82 via-[#f6faff]/72 to-[#dbe8f5]/92"></div>

<main class="flex min-h-screen w-full items-center justify-center px-4 py-10">
    <section class="w-full max-w-2xl rounded-3xl border border-white/90 bg-white/[0.9] p-6 text-center shadow-2xl shadow-[#003f87]/15 backdrop-blur-xl sm:p-8 md:p-10">
        <img src="{{ asset('images/airod-logo.webp') }}" alt="AIROD" class="mx-auto h-auto w-52 object-contain sm:w-64">

        <div class="mx-auto mt-8 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
            <span class="material-symbols-outlined !text-[52px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>

        <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Submitted</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#111827] md:text-4xl">
            Registration Successful
        </h1>

        <p class="mx-auto mt-4 max-w-xl text-base font-medium leading-7 text-[#344052] md:text-lg">
            @if ($visitorName)
                Thank you, {{ $visitorName }}. Your visitor request has been received by AIROD.
            @else
                Your visitor request has been received by AIROD.
            @endif
            Please proceed to the security counter with your IC or passport. No public reference number is required.
        </p>

        <div class="mt-8 grid grid-cols-1 gap-4 text-left md:grid-cols-3">
            @if ($visitorNo)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white">
                            <span class="material-symbols-outlined">badge</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#555f6b]">Visitor No</p>
                            <p class="mt-1 text-lg font-extrabold text-emerald-700">{{ $visitorNo }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-[#d7e2ff] bg-[#ecf5fe]/90 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#0056b3] text-white">
                        <span class="material-symbols-outlined">hourglass_top</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#555f6b]">Status</p>
                        <p class="mt-1 text-lg font-extrabold text-[#003f87]">Pending Review</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[#d7e2ff] bg-[#ecf5fe]/90 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#003f87] text-white">
                        <span class="material-symbols-outlined">admin_panel_settings</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#555f6b]">Handled By</p>
                        <p class="mt-1 text-lg font-extrabold text-[#003f87]">HR / Security</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-[#c2c6d4] bg-white/80 p-5 text-left">
            <div class="flex gap-3">
                <span class="material-symbols-outlined mt-0.5 text-[#0056b3]">info</span>
                <p class="text-sm font-medium leading-6 text-[#424752]">
                    Your internal visitor record is visible only to authorized AIROD HR/security users in the admin dashboard.
                </p>
            </div>
        </div>

        @auth
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-6 py-3 text-sm font-bold text-[#003f87] shadow-sm transition hover:border-[#0056b3] hover:bg-[#ecf5fe]">
                    <span class="material-symbols-outlined !text-[20px]">groups</span>
                    Visitor Records
                </a>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0056b3] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0056b3]/25 transition hover:bg-[#003f87]">
                    <span class="material-symbols-outlined !text-[20px]">dashboard</span>
                    Back to Dashboard
                </a>
            </div>
        @else
            <a href="{{ route('visitor-registration.create') }}" class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl bg-[#0056b3] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0056b3]/25 transition hover:bg-[#003f87]">
                <span class="material-symbols-outlined !text-[20px]">arrow_back</span>
                Back to Registration
            </a>
        @endauth
    </section>
</main>
@endsection
