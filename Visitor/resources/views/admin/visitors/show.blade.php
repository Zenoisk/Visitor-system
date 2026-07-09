@extends('layouts.admin', ['title' => $visitor->full_name, 'heading' => 'Visitor Details'])

@section('content')
@if ($visitor->is_blacklisted)
    <div class="mb-6 flex items-center gap-4 rounded-2xl border-2 border-rose-300 bg-rose-50 p-5 shadow-sm">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white">
            <span class="material-symbols-outlined text-[28px]">gpp_bad</span>
        </div>
        <div>
            <p class="text-sm font-extrabold uppercase tracking-wide text-rose-700">⚠ Blacklisted Visitor</p>
            <p class="mt-1 text-sm font-medium text-rose-600">This visitor's IC/Passport number is on the security watchlist. Exercise extreme caution before approving entry.</p>
        </div>
    </div>
@endif
<div class="grid gap-6 xl:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold">{{ $visitor->full_name }}</h3>
                <p class="mt-1 text-slate-500">{{ $visitor->visitor_no }}</p>
            </div>
            <x-status-badge :status="$visitor->status" />
        </div>
        <dl class="mt-6 grid gap-4 md:grid-cols-2">
            @foreach ([
                'IC / Passport' => $visitor->ic_passport_no,
                'Phone' => $visitor->phone,
                'Email' => $visitor->email ?? '-',
                'Company' => $visitor->company_name,
                'Person To Meet' => $visitor->person_to_meet,
                'Department' => $visitor->department,
                'Vehicle Plate No' => $visitor->vehicle_plate_no ?? '-',
                'Visit Date' => $visitor->visit_date?->format('d M Y'),
                'Checked In' => $visitor->checked_in_at?->format('d M Y H:i') ?? '-',
                'Checked Out' => $visitor->checked_out_at?->format('d M Y H:i') ?? '-',
            ] as $label => $value)
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 font-medium">{{ $value }}</dd>
                </div>
            @endforeach
            <div class="md:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose Of Visit</dt>
                <dd class="mt-1 font-medium">{{ $visitor->purpose_of_visit }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Remarks</dt>
                <dd class="mt-1 font-medium">{{ $visitor->remarks ?? '-' }}</dd>
            </div>
            @if (filled($visitor->custom_fields))
                <div class="md:col-span-2">
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Extra Information</dt>
                    <dd class="mt-3 grid gap-3 md:grid-cols-2">
                        @foreach ($visitor->custom_fields as $response)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">{{ $response['label'] ?? 'Custom Field' }}</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $response['value'] ?? '-' }}</p>
                            </div>
                        @endforeach
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    <div class="space-y-6">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-semibold">Quick Actions</h3>
            <div class="mt-4 flex flex-col gap-3">
                @if ($visitor->status === 'pending')
                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3 font-semibold text-white shadow hover:bg-blue-700">
                            <span class="material-symbols-outlined">check_circle</span> Approve Visitor
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-rose-100 py-3 font-semibold text-rose-700 hover:bg-rose-200">
                            <span class="material-symbols-outlined">cancel</span> Reject Request
                        </button>
                    </form>
                @elseif ($visitor->status === 'approved')
                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="checked_in">
                        <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 py-3 font-semibold text-white shadow hover:bg-emerald-700">
                            <span class="material-symbols-outlined">login</span> Check In Visitor
                        </button>
                    </form>
                @elseif ($visitor->status === 'checked_in')
                    <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="checked_out">
                        <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-600 py-3 font-semibold text-white shadow hover:bg-slate-700">
                            <span class="material-symbols-outlined">logout</span> Check Out Visitor
                        </button>
                    </form>
                @else
                    <div class="rounded-lg bg-slate-50 p-4 text-center text-sm font-medium text-slate-500">
                        No further actions available for this status.
                    </div>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.visitors.status', $visitor) }}" class="mt-6 border-t border-slate-100 pt-6">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="{{ $visitor->status }}">
                <label class="text-xs font-semibold text-slate-500">Add / Update Remarks</label>
                <textarea name="remarks" rows="2" class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Internal notes...">{{ $visitor->remarks }}</textarea>
                <button class="mt-3 w-full rounded-lg bg-slate-100 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Save Remarks</button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <a href="{{ route('admin.visitors.edit', $visitor) }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-slate-950 px-4 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md {{ auth()->user()?->isAdmin() ? '' : 'sm:col-span-2' }}">
                <span class="material-symbols-outlined text-[19px]">edit</span>
                Edit
            </a>
            @if (auth()->user()?->isAdmin())
                <form method="POST" action="{{ route('admin.visitors.destroy', $visitor) }}" onsubmit="return confirm('Delete this visitor record?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 text-sm font-extrabold text-rose-700 shadow-sm transition hover:-translate-y-0.5 hover:border-rose-500 hover:bg-rose-600 hover:text-white hover:shadow-md">
                        <span class="material-symbols-outlined text-[19px]">delete</span>
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
