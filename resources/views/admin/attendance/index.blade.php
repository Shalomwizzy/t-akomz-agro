@extends('layouts.admin')
@section('page-title', 'Attendance')

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-display font-bold text-content-primary">Attendance Tracker</h1>
        <p class="text-content-secondary text-sm mt-1">Record daily attendance for all active farm workers.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Daily Attendance Sheet --}}
        <div class="xl:col-span-2">
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="font-semibold text-content-primary">Daily Attendance Sheet</h2>
                        <p class="text-xs text-content-muted mt-0.5">{{ $date->format('l, F j, Y') }}</p>
                    </div>
                    <form method="GET" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ $date->toDateString() }}"
                               class="bg-surface-elevated border border-border rounded-xl px-3 py-1.5 text-sm text-content-primary focus:outline-none focus:border-brand-green"
                               onchange="this.form.submit()">
                    </form>
                </div>

                @if($workers->isEmpty())
                <div class="py-12 text-center text-content-muted text-sm">
                    No active workers. <a href="{{ route('admin.workers.create') }}" class="text-brand-green hover:underline">Register workers first.</a>
                </div>
                @else
                <form method="POST" action="{{ route('admin.attendance.bulk-store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date->toDateString() }}">

                    <div class="divide-y divide-border">
                        @foreach($workers as $worker)
                        @php $existing = $records->get($worker->id); @endphp
                        <div class="px-6 py-4">
                            <div class="flex items-center gap-4 flex-wrap">
                                {{-- Worker info --}}
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    @if($worker->photo)
                                    <img src="{{ Storage::url($worker->photo) }}" alt="" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                                    @else
                                    <div class="w-9 h-9 rounded-xl bg-surface-elevated border border-border flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-content-muted">{{ strtoupper(substr($worker->full_name, 0, 1)) }}</span>
                                    </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-content-primary text-sm truncate">{{ $worker->full_name }}</p>
                                        <p class="text-xs text-content-muted capitalize">{{ str_replace('_', ' ', $worker->role) }}</p>
                                    </div>
                                </div>

                                {{-- Status selector --}}
                                <div class="flex items-center gap-2 flex-wrap flex-shrink-0">
                                    @foreach(['present' => 'Present', 'absent' => 'Absent', 'half_day' => 'Half Day', 'leave' => 'Leave', 'holiday' => 'Holiday'] as $val => $label)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio"
                                               name="attendance[{{ $worker->id }}][status]"
                                               value="{{ $val }}"
                                               {{ ($existing?->status ?? 'present') === $val ? 'checked' : '' }}
                                               class="w-3.5 h-3.5 accent-[#B8F397]">
                                        <span class="text-xs {{ $val === 'present' ? 'text-brand-green' : ($val === 'absent' ? 'text-red-400' : ($val === 'half_day' ? 'text-yellow-400' : 'text-content-muted')) }}">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Check-in/out + notes --}}
                            <div class="flex items-center gap-3 mt-3 pl-12 flex-wrap">
                                <div class="flex items-center gap-1.5">
                                    <label class="text-xs text-content-muted">In</label>
                                    <input type="time" name="attendance[{{ $worker->id }}][check_in]"
                                           value="{{ $existing?->check_in_time }}"
                                           class="bg-surface-elevated border border-border rounded-lg px-2 py-1 text-xs text-content-primary focus:outline-none focus:border-brand-green">
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <label class="text-xs text-content-muted">Out</label>
                                    <input type="time" name="attendance[{{ $worker->id }}][check_out]"
                                           value="{{ $existing?->check_out_time }}"
                                           class="bg-surface-elevated border border-border rounded-lg px-2 py-1 text-xs text-content-primary focus:outline-none focus:border-brand-green">
                                </div>
                                <input type="text" name="attendance[{{ $worker->id }}][notes]"
                                       value="{{ $existing?->notes }}"
                                       placeholder="Notes (optional)"
                                       class="flex-1 min-w-32 bg-surface-elevated border border-border rounded-lg px-3 py-1 text-xs text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-border bg-surface-elevated/50">
                        <button type="submit"
                                class="bg-brand-green text-surface-bg font-semibold px-6 py-2.5 rounded-xl hover:bg-brand-dark transition-colors text-sm">
                            Save Attendance for {{ $date->format('M d') }}
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>

        {{-- Monthly Summary --}}
        <div class="space-y-4">
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-border flex items-center justify-between gap-3">
                    <h2 class="font-semibold text-content-primary text-sm">Monthly Summary</h2>
                    <form method="GET">
                        <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                               class="bg-surface-elevated border border-border rounded-lg px-2 py-1 text-xs text-content-primary focus:outline-none focus:border-brand-green"
                               onchange="this.form.submit()">
                        <input type="hidden" name="date" value="{{ $date->toDateString() }}">
                    </form>
                </div>
                <div class="divide-y divide-border">
                    @foreach($monthlySummary as $row)
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-medium text-content-primary truncate">{{ $row['worker']->full_name }}</p>
                            <div class="flex items-center gap-1.5 flex-shrink-0 text-xs">
                                <span class="text-brand-green font-semibold">{{ $row['present'] }}P</span>
                                @if($row['half_days'] > 0)<span class="text-yellow-400">{{ $row['half_days'] }}H</span>@endif
                                @if($row['absent'] > 0)<span class="text-red-400">{{ $row['absent'] }}A</span>@endif
                            </div>
                        </div>
                        <div class="mt-1.5 h-1.5 bg-surface-elevated rounded-full overflow-hidden">
                            <div class="h-full bg-brand-green rounded-full" style="width: {{ $row['rate'] }}%"></div>
                        </div>
                        <p class="text-xs text-content-muted mt-0.5">{{ $row['rate'] }}% attendance</p>
                    </div>
                    @endforeach
                    @if($monthlySummary->isEmpty())
                    <div class="py-8 text-center text-content-muted text-sm">No active workers.</div>
                    @endif
                </div>
            </div>

            {{-- Quick nav --}}
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-2">
                <h3 class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-3">Quick Actions</h3>
                <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 text-sm text-content-secondary hover:text-brand-green transition-colors py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Generate Payroll
                </a>
                <a href="{{ route('admin.workers.index') }}" class="flex items-center gap-3 text-sm text-content-secondary hover:text-brand-green transition-colors py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Manage Workers
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
