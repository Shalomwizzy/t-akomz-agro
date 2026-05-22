@extends('layouts.admin')
@section('page-title', $worker->full_name)

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Back --}}
    <a href="{{ route('admin.workers.index') }}" class="text-content-muted hover:text-brand-green text-sm flex items-center gap-2 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        All Workers
    </a>

    {{-- Profile Header --}}
    <div class="bg-surface-card border border-border rounded-2xl p-6 flex items-start gap-6 flex-wrap">
        <div class="flex-shrink-0">
            @if($worker->photo)
            <img src="{{ Storage::url($worker->photo) }}" alt="{{ $worker->full_name }}" class="w-20 h-20 rounded-2xl object-cover">
            @else
            <div class="w-20 h-20 rounded-2xl bg-surface-elevated border border-border flex items-center justify-center">
                <span class="text-3xl font-bold text-content-muted">{{ strtoupper(substr($worker->full_name, 0, 1)) }}</span>
            </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl font-display font-bold text-content-primary">{{ $worker->full_name }}</h1>
                    <p class="text-content-secondary capitalize mt-0.5">{{ str_replace('_', ' ', $worker->role) }} &mdash; {{ ucfirst($worker->employment_type) }}</p>
                    <div class="flex items-center gap-3 mt-2 flex-wrap">
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full border font-semibold {{ $worker->status_color }}">{{ ucfirst($worker->status) }}</span>
                        <span class="text-xs text-content-muted">Started {{ $worker->start_date->format('M d, Y') }}</span>
                        @if($worker->phone)<span class="text-xs text-content-muted">📞 {{ $worker->phone }}</span>@endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.attendance.index') }}?worker={{ $worker->id }}" class="text-sm border border-border px-4 py-2 rounded-xl text-content-secondary hover:bg-surface-elevated transition-colors">Attendance</a>
                    <a href="{{ route('admin.workers.edit', $worker) }}" class="text-sm bg-surface-elevated border border-border px-4 py-2 rounded-xl text-content-secondary hover:border-brand-green transition-colors">Edit</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Total Earned</p>
            <p class="text-2xl font-display font-bold text-content-primary mt-1">₦{{ number_format($stats['total_earned'], 0) }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Payrolls</p>
            <p class="text-2xl font-display font-bold text-content-primary mt-1">{{ $stats['payrolls_count'] }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Months on Farm</p>
            <p class="text-2xl font-display font-bold text-brand-green mt-1">{{ $stats['months_on_farm'] }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">This Month Days</p>
            <p class="text-2xl font-display font-bold text-content-primary mt-1">{{ $stats['this_month_att'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Worker Info --}}
        <div class="space-y-4">
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h3 class="font-semibold text-content-primary text-sm">Employment Info</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-content-muted">Salary</dt><dd class="text-content-primary font-semibold">{{ $worker->salary_label }}</dd></div>
                    <div class="flex justify-between"><dt class="text-content-muted">Type</dt><dd class="text-content-secondary capitalize">{{ str_replace('_', ' ', $worker->salary_type) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-content-muted">Employment</dt><dd class="text-content-secondary capitalize">{{ $worker->employment_type }}</dd></div>
                    @if($worker->end_date)<div class="flex justify-between"><dt class="text-content-muted">End Date</dt><dd class="text-content-secondary">{{ $worker->end_date->format('M d, Y') }}</dd></div>@endif
                    @if($worker->id_number)<div class="flex justify-between"><dt class="text-content-muted">ID Number</dt><dd class="text-content-secondary font-mono text-xs">{{ $worker->id_number }}</dd></div>@endif
                </dl>
            </div>
            @if($worker->bank_name || $worker->bank_account)
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h3 class="font-semibold text-content-primary text-sm">Bank Details</h3>
                <dl class="space-y-2 text-sm">
                    @if($worker->bank_name)<div class="flex justify-between"><dt class="text-content-muted">Bank</dt><dd class="text-content-secondary">{{ $worker->bank_name }}</dd></div>@endif
                    @if($worker->bank_account)<div class="flex justify-between"><dt class="text-content-muted">Account</dt><dd class="text-content-secondary font-mono">{{ $worker->bank_account }}</dd></div>@endif
                </dl>
            </div>
            @endif
            @if($worker->emergency_contact || $worker->address)
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h3 class="font-semibold text-content-primary text-sm">Contact</h3>
                <dl class="space-y-2 text-sm">
                    @if($worker->address)<div><dt class="text-content-muted text-xs">Address</dt><dd class="text-content-secondary">{{ $worker->address }}</dd></div>@endif
                    @if($worker->emergency_contact)<div><dt class="text-content-muted text-xs">Emergency</dt><dd class="text-content-secondary">{{ $worker->emergency_contact }}</dd></div>@endif
                </dl>
            </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Payroll History --}}
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="font-semibold text-content-primary">Payroll History</h3>
                    <a href="{{ route('admin.payroll.index') }}?worker_id={{ $worker->id }}" class="text-xs text-brand-green hover:underline">View All</a>
                </div>
                @if($worker->payrolls->isEmpty())
                <div class="py-10 text-center text-content-muted text-sm">No payroll records yet.</div>
                @else
                <div class="divide-y divide-border">
                    @foreach($worker->payrolls->take(8) as $payroll)
                    <div class="px-6 py-3 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-content-primary">{{ $payroll->period_label }}</p>
                            <p class="text-xs text-content-muted">{{ $payroll->days_present }}/{{ $payroll->total_days_in_period }} days — {{ $payroll->attendance_rate }}% attendance</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold font-mono text-content-primary">{{ $payroll->formatted_payable }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $payroll->status_color }}">{{ ucfirst($payroll->status) }}</span>
                        </div>
                        <a href="{{ route('admin.payroll.show', $payroll) }}" class="text-xs text-content-muted hover:text-brand-green flex-shrink-0">→</a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Recent Attendance --}}
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h3 class="font-semibold text-content-primary">Recent Attendance</h3>
                    <a href="{{ route('admin.attendance.index') }}" class="text-xs text-brand-green hover:underline">Mark Attendance</a>
                </div>
                @if($worker->attendances->isEmpty())
                <div class="py-10 text-center text-content-muted text-sm">No attendance records yet.</div>
                @else
                <div class="divide-y divide-border">
                    @foreach($worker->attendances->take(14) as $att)
                    <div class="px-6 py-2.5 flex items-center justify-between gap-3">
                        <span class="text-sm text-content-secondary">{{ $att->date->format('EEE, M d Y') === false ? $att->date->format('D, M d') : $att->date->format('D, M d') }}</span>
                        <div class="flex items-center gap-2">
                            @if($att->check_in_time)<span class="text-xs text-content-muted">{{ $att->check_in_time }} – {{ $att->check_out_time ?? '?' }}</span>@endif
                            <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $att->status_color }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
