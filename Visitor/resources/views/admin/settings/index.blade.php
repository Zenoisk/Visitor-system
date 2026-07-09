@extends('layouts.admin', ['title' => 'Settings', 'heading' => 'Account Settings'])

@section('content')
<div class="mx-auto max-w-2xl">
    <section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Account</p>
        <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Profile & Security</h1>
        <p class="mt-2 text-sm font-medium text-slate-500">Update your name, email address, or change your password.</p>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Profile Section --}}
            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Profile Information</h3>
                <div class="mt-4 grid gap-5 sm:grid-cols-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="name">Full Name</label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" type="text" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="username">Username</label>
                        <input id="username" name="username" value="{{ old('username', $user->username) }}" type="text" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('username') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="email">Email Address</label>
                        <input id="email" name="email" value="{{ old('email', $user->email) }}" type="email" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm font-semibold text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            {{-- Password Section --}}
            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-slate-500">Change Password</h3>
                <p class="mt-1 text-xs font-medium text-slate-400">Leave blank to keep your current password.</p>
                <div class="mt-4 grid gap-5 sm:grid-cols-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="current_password">Current Password</label>
                        <input id="current_password" name="current_password" type="password" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="••••••••">
                        @error('current_password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="new_password">New Password</label>
                        <input id="new_password" name="new_password" type="password" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="••••••••">
                        @error('new_password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500" for="new_password_confirmation">Confirm New Password</label>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="mt-1 block h-[42px] w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow hover:bg-blue-700">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
