@php
    $type = $type ?? 'text';
    $required = $required ?? true;
@endphp
<div>
    <label class="text-sm font-medium">{{ $label }}</label>
    <input name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value ?? '') }}" @required($required) class="mt-2 w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
    @error($name) <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
</div>
