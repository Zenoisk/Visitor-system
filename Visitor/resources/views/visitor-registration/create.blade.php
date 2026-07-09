@extends('layouts.guest', ['title' => 'Visitor Registration | AIROD'])

@section('content')
@php
    $inputClass = 'mt-2 w-full rounded-xl border border-[#b8c3d3]/90 bg-white/95 px-4 py-3.5 text-[15px] font-semibold text-[#141d23] shadow-sm outline-none transition placeholder:font-medium placeholder:text-[#7b8390] hover:border-[#7f9bbd] focus:border-[#0056b3] focus:bg-white focus:ring-4 focus:ring-[#acc7ff]/45';
    $textareaClass = $inputClass.' min-h-28 resize-none leading-6';
    $labelClass = 'text-[12px] font-extrabold uppercase tracking-[0.09em] text-[#344052]';
    $backgroundImage = collect(['jpg', 'png', 'jpeg', 'webp'])
        ->map(fn ($extension) => "images/airod-registration-bg.{$extension}")
        ->first(fn ($path) => file_exists(public_path($path))) ?? 'images/airod-registration-bg.jpg';
@endphp

<div class="fixed inset-0 -z-20 bg-[#f6faff]">
    <img src="{{ asset($backgroundImage) }}" alt="" class="h-full w-full object-cover opacity-35">
</div>
<div class="fixed inset-0 -z-10 bg-gradient-to-b from-[#f6faff]/76 via-[#f6faff]/64 to-[#dbe8f5]/88"></div>

<main class="mx-auto flex w-full max-w-[1440px] flex-col items-center px-4 pb-14 pt-8 sm:px-6 md:pt-12">
    @auth
        <div class="mb-6 flex w-full max-w-4xl flex-col gap-3 rounded-2xl border border-white/80 bg-white/[0.86] p-4 shadow-lg shadow-[#003f87]/10 backdrop-blur-md sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-[#0056b3]">Admin Mode</p>
                <p class="mt-1 text-sm font-semibold text-[#344052]">You are viewing the public visitor form as an authenticated admin.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.form-fields.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-4 py-2.5 text-sm font-extrabold text-[#003f87] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0056b3] hover:shadow-md">
                    <span class="material-symbols-outlined text-[19px]">dynamic_form</span>
                    Edit Form Fields
                </a>
                <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-4 py-2.5 text-sm font-extrabold text-[#003f87] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0056b3] hover:shadow-md">
                    <span class="material-symbols-outlined text-[19px]">groups</span>
                    Visitor Records
                </a>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0056b3] px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#003f87] hover:shadow-md">
                    <span class="material-symbols-outlined text-[19px]">dashboard</span>
                    Back to Dashboard
                </a>
            </div>
        </div>
    @endauth

    <section class="mb-8 flex w-full max-w-4xl flex-col items-center text-center">
        <div class="rounded-2xl border border-white/80 bg-white/[0.72] px-5 py-4 shadow-lg shadow-[#003f87]/10 backdrop-blur-md">
            <img src="{{ asset('images/airod-logo.webp') }}" alt="AIROD" class="h-auto w-52 object-contain sm:w-64 md:w-72">
            <p class="mt-3 text-center text-sm font-semibold tracking-wide text-[#424752]">Visitor Registration</p>
        </div>
        <h1 class="mt-8 text-4xl font-extrabold tracking-tight text-[#111827] md:text-5xl">Welcome to AIROD</h1>
        <p class="mt-4 max-w-2xl text-lg font-medium leading-8 text-[#344052]">Please register your visit before proceeding to the security counter.</p>
    </section>

    <section class="w-full max-w-4xl overflow-hidden rounded-2xl sm:rounded-3xl border border-white/90 bg-white/[0.92] shadow-2xl shadow-[#003f87]/15 backdrop-blur-xl">
        <div class="border-b border-[#d7e2ff] bg-gradient-to-r from-[#003f87] via-[#0056b3] to-[#2f80d4] px-4 py-5 text-white sm:px-7 md:px-10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#d7e2ff]">Secure visitor intake</p>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-white">AIROD Entry Registration</h2>
                </div>
                <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-bold text-white backdrop-blur">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                    Sent to HR for approval
                </div>
            </div>
        </div>

        <div class="px-4 py-5 sm:px-7 md:px-10 md:py-8">
            <div class="mb-7 rounded-2xl border border-[#d7e2ff] bg-[#ecf5fe]/80 p-4">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined mt-0.5 text-[#0056b3]">verified_user</span>
                    <p class="text-sm font-semibold leading-6 text-[#344052]">
                        Complete the required fields below. Your details are used only for AIROD visitor screening and entry coordination.
                    </p>
                </div>
            </div>

        <form method="POST" action="{{ route('visitor-register.store') }}" class="grid grid-cols-1 gap-x-7 gap-y-6 md:grid-cols-2">
            @csrf

            <div class="border-b border-[#c2c6d4] pb-4 md:col-span-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#d7e2ff] text-[#003f87]">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#6b7280]">Step 1</p>
                        <h2 class="text-2xl font-extrabold tracking-tight text-[#003f87]">Visitor Information</h2>
                    </div>
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}" for="full_name">Full Name</label>
                <input id="full_name" name="full_name" value="{{ old('full_name') }}" class="{{ $inputClass }}" placeholder="e.g. John Doe" type="text" required autocomplete="name">
                @error('full_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="ic_passport_no">IC / Passport Number</label>
                <input id="ic_passport_no" name="ic_passport_no" value="{{ old('ic_passport_no') }}" class="{{ $inputClass }} uppercase" placeholder="Require" type="text" autocomplete="off" required>
                @error('ic_passport_no') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="phone">Phone Number</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" class="{{ $inputClass }} numeric-only" placeholder="Require" type="tel" inputmode="numeric" pattern="[0-9]*" required autocomplete="tel">
                @error('phone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="email">Email Address</label>
                <input id="email" name="email" value="{{ old('email') }}" class="{{ $inputClass }}" placeholder="john@example.com" type="email" autocomplete="email">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="mt-3 border-b border-[#c2c6d4] pb-4 md:col-span-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#d7e2ff] text-[#003f87]">
                        <span class="material-symbols-outlined">meeting_room</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#6b7280]">Step 2</p>
                        <h2 class="text-2xl font-extrabold tracking-tight text-[#003f87]">Visit Details</h2>
                    </div>
                </div>
            </div>

            <div>
                <label class="{{ $labelClass }}" for="company_name">Company Name</label>
                <input id="company_name" name="company_name" value="{{ old('company_name') }}" class="{{ $inputClass }}" placeholder="Organization" type="text" required>
                @error('company_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <p class="{{ $labelClass }}">Purpose of Visit</p>
                <div class="mt-2 grid grid-cols-2 gap-2 sm:gap-3 sm:grid-cols-5">
                    @foreach ([
                        'Meeting' => 'groups',
                        'Maintenance' => 'build',
                        'Delivery' => 'local_shipping',
                        'Interview' => 'badge',
                        'Other' => 'more_horiz',
                    ] as $purpose => $icon)
                        <label class="group cursor-pointer">
                            <input class="peer sr-only" type="radio" name="purpose_of_visit" value="{{ $purpose }}" @checked(old('purpose_of_visit', 'Meeting') === $purpose) required>
                            <span class="flex min-h-20 sm:min-h-24 flex-col items-center justify-center gap-1.5 sm:gap-2 rounded-2xl border border-[#b8c3d3] bg-white/90 px-2 py-3 sm:px-3 sm:py-4 text-center text-xs sm:text-sm font-extrabold text-[#344052] shadow-sm transition hover:-translate-y-0.5 hover:border-[#0056b3] hover:bg-[#ecf5fe] hover:shadow-md peer-checked:border-[#0056b3] peer-checked:bg-[#0056b3] peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-[#0056b3]/20 peer-focus-visible:ring-4 peer-focus-visible:ring-[#acc7ff]/60">
                                <span class="material-symbols-outlined text-[22px] sm:text-[26px]">{{ $icon }}</span>
                                <span>{{ $purpose }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('purpose_of_visit') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div id="other-purpose-wrap" class="md:col-span-2 {{ old('purpose_of_visit') === 'Other' ? '' : 'hidden' }}">
                <label class="{{ $labelClass }}" for="purpose_of_visit_other">Specify Purpose</label>
                <input id="purpose_of_visit_other" name="purpose_of_visit_other" value="{{ old('purpose_of_visit_other') }}" class="{{ $inputClass }}" placeholder="Please describe your purpose of visit" type="text" @required(old('purpose_of_visit') === 'Other')>
                @error('purpose_of_visit_other') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="person_to_meet">Person To Meet</label>
                <input id="person_to_meet" name="person_to_meet" value="{{ old('person_to_meet') }}" class="{{ $inputClass }}" placeholder="Host name" type="text" required>
                @error('person_to_meet') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="department">Department</label>
                <select id="department" name="department" class="{{ $inputClass }}" required>
                    <option value="" disabled @selected(!old('department'))>Select Department</option>
                    @foreach(['IT', 'HR', 'Finance', 'Procurement', 'Engineering', 'Security', 'Management', 'Others'] as $dept)
                        <option value="{{ $dept }}" @selected(old('department') === $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
                @error('department') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="vehicle_plate_no">Vehicle Plate Number</label>
                <input id="vehicle_plate_no" name="vehicle_plate_no" value="{{ old('vehicle_plate_no') }}" class="{{ $inputClass }}" placeholder="Optional" type="text">
                @error('vehicle_plate_no') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}" for="visit_date">Visit Date</label>
                <input id="visit_date" name="visit_date" value="{{ old('visit_date', now()->toDateString()) }}" class="{{ $inputClass }}" type="date" required>
                @error('visit_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks" rows="3" class="{{ $textareaClass }}" placeholder="Any additional information...">{{ old('remarks') }}</textarea>
                @error('remarks') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            @include('visitor-registration.partials.custom-fields')

            <div class="md:col-span-2">
                <button class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#0056b3] px-5 py-4 text-lg font-bold tracking-tight text-white shadow-lg shadow-[#0056b3]/25 transition hover:bg-[#003f87] active:scale-[0.98]" type="submit">
                    <span class="material-symbols-outlined">send</span>
                    Submit Registration
                </button>
            </div>
        </form>
        </div>
    </section>

    <section class="mt-8 grid w-full max-w-3xl grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-white/80 bg-[#e6eff8]/[0.9] p-5 shadow-sm backdrop-blur-md">
            <span class="material-symbols-outlined mb-2 text-[#003f87]" style="font-variation-settings: 'FILL' 1;">security</span>
            <h3 class="text-sm font-bold uppercase tracking-wide text-[#141d23]">Security First</h3>
            <p class="mt-2 text-sm leading-6 text-[#424752]">Your identification is handled according to AIROD security procedures.</p>
        </div>
        <div class="rounded-xl border border-white/80 bg-[#e6eff8]/[0.9] p-5 shadow-sm backdrop-blur-md">
            <span class="material-symbols-outlined mb-2 text-[#003f87]" style="font-variation-settings: 'FILL' 1;">timer</span>
            <h3 class="text-sm font-bold uppercase tracking-wide text-[#141d23]">Fast Check-in</h3>
            <p class="mt-2 text-sm leading-6 text-[#424752]">Pre-registration helps speed up your entry process at the counter.</p>
        </div>
        <div class="rounded-xl border border-white/80 bg-[#e6eff8]/[0.9] p-5 shadow-sm backdrop-blur-md">
            <span class="material-symbols-outlined mb-2 text-[#003f87]" style="font-variation-settings: 'FILL' 1;">support_agent</span>
            <h3 class="text-sm font-bold uppercase tracking-wide text-[#141d23]">Help Desk</h3>
            <p class="mt-2 text-sm leading-6 text-[#424752]">Need assistance? Please speak with AIROD security staff.</p>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const otherWrap = document.getElementById('other-purpose-wrap');
        const otherInput = document.getElementById('purpose_of_visit_other');
        const purposeOptions = document.querySelectorAll('input[name="purpose_of_visit"]');
        const numericInputs = document.querySelectorAll('.numeric-only');

        const syncOtherPurpose = () => {
            const selected = document.querySelector('input[name="purpose_of_visit"]:checked');
            const isOther = selected?.value === 'Other';

            otherWrap.classList.toggle('hidden', !isOther);
            otherInput.toggleAttribute('required', isOther);

            if (isOther) {
                otherInput.focus();
            } else {
                otherInput.value = '';
            }
        };

        purposeOptions.forEach((option) => option.addEventListener('change', syncOtherPurpose));
        syncOtherPurpose();

        numericInputs.forEach((input) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '');
            });

            input.addEventListener('paste', (event) => {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text');
                input.value = pasted.replace(/\D/g, '');
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });
    });
</script>

@endsection
