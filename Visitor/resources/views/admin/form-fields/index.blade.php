@extends('layouts.admin', ['title' => 'Form Builder', 'heading' => 'Form Builder'])

@section('content')
<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Visitor Form</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Add and manage extra form fields</h1>
            <p class="mt-2 max-w-2xl text-sm font-semibold leading-6 text-slate-500">These fields appear below the standard visitor details on the QR registration form.</p>
        </div>
        <a href="{{ route('admin.form-fields.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">
            <span class="material-symbols-outlined text-[19px]">add</span>
            Add New Field
        </a>
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-4">Field</th>
                    <th class="px-5 py-4">Type</th>
                    <th class="px-5 py-4">Required</th>
                    <th class="px-5 py-4">Visible</th>
                    <th class="px-5 py-4">Order</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($fields as $field)
                    <tr class="transition hover:bg-blue-50/40">
                        <td class="px-5 py-4">
                            <p class="font-extrabold text-slate-950">{{ $field->label }}</p>
                            <p class="text-xs font-semibold text-slate-500">{{ $field->placeholder ?: 'No placeholder' }}</p>
                        </td>
                        <td class="px-5 py-4 font-semibold capitalize text-slate-700">{{ $field->field_type }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ $field->is_required ? 'Yes' : 'No' }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ $field->is_active ? 'Visible' : 'Hidden' }}</td>
                        <td class="px-5 py-4 font-semibold text-slate-700">{{ $field->sort_order }}</td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.form-fields.edit', $field) }}" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-bold text-blue-700 hover:bg-blue-100">
                                    Edit
                                    <span class="material-symbols-outlined text-[17px]">arrow_forward</span>
                                </a>
                                <form method="POST" action="{{ route('admin.form-fields.destroy', $field) }}" onsubmit="return confirm('Delete this custom form field?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-bold text-rose-700 hover:bg-rose-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center font-semibold text-slate-500">No extra fields yet. Add your first field to customize the registration form.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
