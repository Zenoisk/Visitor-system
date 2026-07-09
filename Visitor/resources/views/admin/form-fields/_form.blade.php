@php
    $inputClass = 'mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 hover:border-blue-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $labelClass = 'text-xs font-extrabold uppercase tracking-[0.12em] text-slate-600';
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="label" class="{{ $labelClass }}">Field Label</label>
        <input id="label" name="label" value="{{ old('label', $field->label) }}" class="{{ $inputClass }}" placeholder="e.g. Access Card Number" required>
        @error('label') <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="field_type" class="{{ $labelClass }}">Field Type</label>
        <select id="field_type" name="field_type" class="{{ $inputClass }}" required>
            @foreach (\App\Models\VisitorFormField::TYPES as $type)
                <option value="{{ $type }}" @selected(old('field_type', $field->field_type) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        @error('field_type') <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="placeholder" class="{{ $labelClass }}">Placeholder</label>
        <input id="placeholder" name="placeholder" value="{{ old('placeholder', $field->placeholder) }}" class="{{ $inputClass }}" placeholder="Optional placeholder text">
        @error('placeholder') <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sort_order" class="{{ $labelClass }}">Sort Order</label>
        <input id="sort_order" name="sort_order" value="{{ old('sort_order', $field->sort_order ?? 0) }}" class="{{ $inputClass }}" type="number" min="0" max="999">
        @error('sort_order') <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="helper_text" class="{{ $labelClass }}">Helper Text</label>
        <input id="helper_text" name="helper_text" value="{{ old('helper_text', $field->helper_text) }}" class="{{ $inputClass }}" placeholder="Optional helper note shown under the input">
        @error('helper_text') <p class="mt-1 text-sm font-semibold text-rose-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
        <input type="checkbox" name="is_required" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('is_required', $field->is_required))>
        <span>
            <span class="block text-sm font-extrabold text-slate-900">Required field</span>
            <span class="block text-xs font-semibold text-slate-500">Visitors must complete this input.</span>
        </span>
    </label>

    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('is_active', $field->exists ? $field->is_active : true))>
        <span>
            <span class="block text-sm font-extrabold text-slate-900">Show on form</span>
            <span class="block text-xs font-semibold text-slate-500">Inactive fields stay hidden from visitors.</span>
        </span>
    </label>
</div>
