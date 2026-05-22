@extends('layouts.admin')
@section('page-title', 'Workers — HR')

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-content-primary">Workers & HR</h1>
            <p class="text-content-secondary text-sm mt-1">Manage farm workers, roles, and employment records.</p>
        </div>
        <a href="{{ route('admin.workers.create') }}"
           class="inline-flex items-center gap-2 bg-brand-green text-surface-bg font-semibold px-4 py-2.5 rounded-xl hover:bg-brand-dark transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Register Worker
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Total Workers</p>
            <p class="text-3xl font-display font-bold text-content-primary mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Active</p>
            <p class="text-3xl font-display font-bold text-brand-green mt-1">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Monthly Labor Cost</p>
            <p class="text-2xl font-display font-bold text-content-primary mt-1">₦{{ number_format($stats['monthly_cost'], 0) }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5 {{ $stats['unpaid'] > 0 ? 'border-yellow-500/30' : '' }}">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Unpaid Payrolls</p>
            <p class="text-3xl font-display font-bold {{ $stats['unpaid'] > 0 ? 'text-yellow-400' : 'text-content-primary' }} mt-1">{{ $stats['unpaid'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or role…"
               class="bg-surface-card border border-border rounded-xl px-4 py-2 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green flex-1 min-w-40">
        <select name="status" class="bg-surface-card border border-border rounded-xl px-3 py-2 text-sm text-content-primary focus:outline-none focus:border-brand-green">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="bg-brand-green text-surface-bg px-4 py-2 rounded-xl text-sm font-semibold hover:bg-brand-dark transition-colors">Filter</button>
        @if(request()->hasAny(['search','status','role'])) <a href="{{ route('admin.workers.index') }}" class="px-4 py-2 rounded-xl text-sm border border-border text-content-secondary hover:bg-surface-elevated transition-colors">Clear</a> @endif
    </form>

    {{-- Table --}}
    <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
        @if($workers->isEmpty())
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-surface-elevated border border-border flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-content-secondary font-medium">No workers registered yet</p>
            <a href="{{ route('admin.workers.create') }}" class="mt-3 inline-flex items-center gap-2 text-brand-green text-sm hover:underline">
                Register your first worker
            </a>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="border-b border-border bg-surface-elevated">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Worker</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Employment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($workers as $worker)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($worker->photo)
                            <img src="{{ Storage::url($worker->photo) }}" alt="" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                            @else
                            <div class="w-9 h-9 rounded-xl bg-surface-elevated border border-border flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-content-muted">{{ strtoupper(substr($worker->full_name, 0, 1)) }}</span>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-content-primary">{{ $worker->full_name }}</p>
                                <p class="text-xs text-content-muted">{{ $worker->phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-content-secondary capitalize">{{ str_replace('_', ' ', $worker->role) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-lg bg-surface-elevated border border-border text-content-secondary capitalize">{{ $worker->employment_type }}</span>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-content-primary">{{ $worker->salary_label }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full border font-semibold {{ $worker->status_color }}">
                            {{ ucfirst($worker->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-content-muted text-xs">{{ $worker->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.workers.show', $worker) }}" class="text-brand-green hover:underline text-xs font-semibold">View</a>
                            <a href="{{ route('admin.workers.edit', $worker) }}" class="text-content-muted hover:text-content-secondary text-xs">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-border">{{ $workers->links() }}</div>
        @endif
    </div>
</div>
@endsection
