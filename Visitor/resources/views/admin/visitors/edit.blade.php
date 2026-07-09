@extends('layouts.admin', ['title' => 'Edit Visitor', 'heading' => 'Edit Visitor'])

@section('content')
<form method="POST" action="{{ route('admin.visitors.update', $visitor) }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.visitors._form')
    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('admin.visitors.show', $visitor) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">Cancel</a>
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Save Changes</button>
    </div>
</form>
@endsection
