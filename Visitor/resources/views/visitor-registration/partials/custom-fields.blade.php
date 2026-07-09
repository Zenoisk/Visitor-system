@php
    $fields = $customFields ?? collect();
    $visitorCustomFields = isset($visitor) ? ($visitor->custom_fields ?? []) : [];
    $inputClass = $inputClass ?? 'mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $textareaClass = $textareaClass ?? $inputClass.' min-h-28 resize-none leading-6';
    $labelClass = $labelClass ?? 'text-xs font-extrabold uppercase tracking-[0.12em] text-slate-600';
@endphp

@if ($fields->isNotEmpty())
    <div class="mt-3 border-b border-[#c2c6d4] pb-4 md:col-span-2">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#d7e2ff] text-[#003f87]">
                <span class="material-symbols-outlined">dynamic_form</span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#6b7280]">Additional Fields</p>
                <h2 class="text-2xl font-extrabold tracking-tight text-[#003f87]">Extra Information</h2>
            </div>
        </div>
    </div>

    @foreach ($fields as $field)
        @php
            $storedValue = $visitorCustomFields[$field->id]['value'] ?? $visitorCustomFields[(string) $field->id]['value'] ?? '';
            $value = old("custom_fields.{$field->id}", $storedValue);
            $fieldId = "custom_field_{$field->id}";
        @endphp

        <div class="{{ $field->field_type === 'textarea' ? 'md:col-span-2' : '' }}">
            <label class="{{ $labelClass }}" for="{{ $fieldId }}">
                {{ $field->label }}
                @if ($field->is_required)
                    <span class="text-rose-600">*</span>
                @endif
            </label>

            @if ($field->field_type === 'textarea')
                <textarea id="{{ $fieldId }}" name="custom_fields[{{ $field->id }}]" rows="3" class="{{ $textareaClass }}" placeholder="{{ $field->placeholder }}" @required($field->is_required)>{{ $value }}</textarea>
            @else
                <input id="{{ $fieldId }}" name="custom_fields[{{ $field->id }}]" value="{{ $value }}" class="{{ $inputClass }}" placeholder="{{ $field->placeholder }}" type="{{ $field->field_type }}" @required($field->is_required)>
            @endif

            @if ($field->helper_text)
                <p class="mt-1 text-xs font-semibold text-slate-500">{{ $field->helper_text }}</p>
            @endif

            @error("custom_fields.{$field->id}")
                <p class="mt-1 text-sm font-semibold text-red-700">{{ $message }}</p>
            @enderror
        </div>
    @endforeach
@endif
