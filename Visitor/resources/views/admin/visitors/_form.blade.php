<div class="grid gap-5 md:grid-cols-2">
    @include('visitor-registration.partials.input', ['name' => 'full_name', 'label' => 'Full Name', 'value' => $visitor->full_name ?? ''])
    @include('visitor-registration.partials.input', ['name' => 'ic_passport_no', 'label' => 'IC / Passport No', 'value' => $visitor->ic_passport_no ?? ''])
    @include('visitor-registration.partials.input', ['name' => 'phone', 'label' => 'Phone', 'value' => $visitor->phone ?? ''])
    @include('visitor-registration.partials.input', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $visitor->email ?? '', 'required' => false])
    @include('visitor-registration.partials.input', ['name' => 'company_name', 'label' => 'Company Name', 'value' => $visitor->company_name ?? ''])
    @include('visitor-registration.partials.input', ['name' => 'vehicle_plate_no', 'label' => 'Vehicle Plate No', 'value' => $visitor->vehicle_plate_no ?? '', 'required' => false])
    @include('visitor-registration.partials.input', ['name' => 'person_to_meet', 'label' => 'Person To Meet', 'value' => $visitor->person_to_meet ?? ''])
    <div>
        <label class="text-sm font-bold text-slate-700" for="department">Department</label>
        <select id="department" name="department" class="mt-2 w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
            <option value="" disabled @selected(!old('department', $visitor->department ?? null))>Select Department</option>
            @foreach(['IT', 'HR', 'Finance', 'Procurement', 'Engineering', 'Security', 'Management', 'Others'] as $dept)
                <option value="{{ $dept }}" @selected(old('department', $visitor->department ?? null) === $dept)>{{ $dept }}</option>
            @endforeach
        </select>
        @error('department') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    @include('visitor-registration.partials.input', ['name' => 'visit_date', 'label' => 'Visit Date', 'type' => 'date', 'value' => isset($visitor) ? $visitor->visit_date?->format('Y-m-d') : ''])
    <div>
        <label class="text-sm font-medium">Status</label>
        <select name="status" class="mt-2 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $visitor->status ?? 'pending') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">@include('visitor-registration.partials.textarea', ['name' => 'purpose_of_visit', 'label' => 'Purpose Of Visit', 'value' => $visitor->purpose_of_visit ?? ''])</div>
    <div class="md:col-span-2">@include('visitor-registration.partials.textarea', ['name' => 'remarks', 'label' => 'Remarks', 'value' => $visitor->remarks ?? '', 'required' => false])</div>
    @include('visitor-registration.partials.custom-fields', ['customFields' => $customFields ?? collect(), 'visitor' => $visitor ?? null])
</div>
