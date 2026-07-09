@extends('layouts.admin', ['title' => 'Activity Logs', 'heading' => 'System Audit Trail'])

@section('content')
<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-700">Audit & Accountability</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Recent System Activity</h1>
        </div>
    </div>
</section>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-4">Date / Time</th>
                    <th class="px-5 py-4">User</th>
                    <th class="px-5 py-4">Action</th>
                    <th class="px-5 py-4">Visitor</th>
                    <th class="px-5 py-4">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold text-slate-600">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td class="px-5 py-4">
                            @if($log->user)
                                <p class="font-extrabold text-slate-900">{{ $log->user->name }}</p>
                                <p class="text-xs text-slate-500 capitalize">{{ $log->user->role }}</p>
                            @else
                                <span class="rounded bg-slate-200 px-2 py-1 text-xs font-bold text-slate-700">System / Public</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="rounded bg-blue-50 px-2 py-1 text-xs font-bold uppercase tracking-wide text-blue-700">{{ str_replace('_', ' ', $log->action) }}</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-700">
                            @if($log->visitor)
                                <a href="{{ route('admin.visitors.show', $log->visitor) }}" class="text-blue-600 hover:underline">{{ $log->visitor->full_name }}</a>
                            @else
                                <span class="text-slate-400">Visitor Deleted</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-600">{{ $log->description }}</p>
                            @if($log->old_status && $log->new_status)
                                <p class="mt-1 text-xs font-medium text-slate-500">Status changed: <span class="font-bold text-slate-700">{{ $log->old_status }}</span> &rarr; <span class="font-bold text-slate-700">{{ $log->new_status }}</span></p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center font-semibold text-slate-500">No activity logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div class="mt-5">{{ $logs->links() }}</div>
@endsection
