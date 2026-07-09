@extends('layouts.guest', ['title' => 'AIROD | Admin Login'])

@section('content')
<style>
    .login-mesh {
        background-image: linear-gradient(rgba(15, 23, 42, 0.4), rgba(10, 15, 30, 0.6)), url('{{ asset('images/login-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .admin-glass {
        background: rgba(255, 255, 255, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
</style>

<div class="login-mesh relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-10 sm:px-6">
    <div class="pointer-events-none absolute left-6 top-6 hidden h-12 w-12 border-l-2 border-t-2 border-white/20 md:block"></div>
    <div class="pointer-events-none absolute right-6 top-6 hidden h-12 w-12 border-r-2 border-t-2 border-white/20 md:block"></div>
    <div class="pointer-events-none absolute bottom-6 left-6 hidden h-12 w-12 border-b-2 border-l-2 border-white/20 md:block"></div>
    <div class="pointer-events-none absolute bottom-6 right-6 hidden h-12 w-12 border-b-2 border-r-2 border-white/20 md:block"></div>
    <div class="pointer-events-none absolute -bottom-36 -right-28 h-80 w-80 rounded-full bg-[#acc7ff]/10 blur-3xl"></div>

    <main class="relative z-10 w-full max-w-[440px]">
        <div class="mb-8 flex flex-col items-center text-center">
            <img
                src="{{ asset('images/airod-logo.webp') }}"
                alt="AIROD logo"
                class="mb-4 h-16 w-auto max-w-[240px] object-contain brightness-0 invert"
            >
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-white">Visitor Management</p>
        </div>

        <section class="admin-glass rounded-lg p-7 sm:p-8">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-extrabold tracking-tight text-[#003f87] sm:text-3xl">
                    Security & Admin Portal
                </h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Authenticate to access the AIROD facility dashboard.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-slate-600">
                        Username
                    </label>
                    <div class="group relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[22px] text-slate-400 transition-colors group-focus-within:text-[#003f87]">person</span>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                            autofocus
                            placeholder="Username"
                            class="h-12 w-full rounded-lg border border-slate-300 bg-white/90 py-3 pl-12 pr-4 text-[15px] font-medium text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0056b3] focus:outline-none focus:ring-4 focus:ring-[#acc7ff]/45"
                        >
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-slate-600">
                        Password
                    </label>
                    <div class="group relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[22px] text-slate-400 transition-colors group-focus-within:text-[#003f87]">lock</span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Enter your Password"
                            class="h-12 w-full rounded-lg border border-slate-300 bg-white/90 py-3 pl-12 pr-4 text-[15px] font-medium text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0056b3] focus:outline-none focus:ring-4 focus:ring-[#acc7ff]/45"
                        >
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-4">
                    <label for="remember" class="flex cursor-pointer select-none items-center gap-2 text-sm font-medium text-slate-700">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-[#003f87] focus:ring-[#0056b3]"
                        >
                        Remember me
                    </label>
                    <span class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Secure Login</span>
                </div>

                <button
                    type="submit"
                    class="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#0056b3] px-4 text-base font-bold text-white shadow-lg shadow-blue-900/15 transition hover:bg-[#003f87] focus:outline-none focus:ring-4 focus:ring-[#acc7ff]/55 active:scale-[0.99]"
                >
                    <span class="material-symbols-outlined text-[21px]">login</span>
                    Login
                </button>
            </form>
        </section>

        <div class="mt-6 flex flex-col items-center gap-3 text-center text-sm text-slate-300 sm:flex-row sm:justify-center">
            <a href="{{ route('visitor-registration.create') }}" class="font-semibold text-white hover:text-sky-300 hover:underline">
                Visitor registration
            </a>
            <span class="hidden h-1 w-1 rounded-full bg-slate-500 sm:block"></span>
            <span>Need access? Contact system support.</span>
        </div>
    </main>
</div>
@endsection
