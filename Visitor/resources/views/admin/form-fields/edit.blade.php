@extends('layouts.admin', ['title' => 'Edit Form Field', 'heading' => 'Edit Form Field'])

@section('content')
<form method="POST" action="{{ route('admin.form-fields.update', $field) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.form-fields._form')
    <div class="mt-6 flex flex-col justify-end gap-3 sm:flex-row">
        <a href="{{ route('admin.form-fields.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-extrabold text-slate-700 hover:bg-slate-50">Cancel</a>
        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-blue-700">
            <span class="material-symbols-outlined text-[19px]">save</span>
            Save Field
        </button>
    </div>
</form>
@endsection
