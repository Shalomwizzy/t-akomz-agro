@extends('layouts.admin')
@section('page-title', 'Payroll')

@section('content')
<div class="p-6 lg:p-8 space-y-8" x-data="{ showGenerate: false }">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-content-primary">Payroll Management</h1>
            <p class="text-content-secondary text-sm mt-1">Generate, approve, and process salary payments.</p>
        </div>
        <button @click="showGenerate = !showGenerate"
                class="inline-flex items-center gap-2 bg-brand-green text-surface-bg font-semibold px-4 py-2.5 rounded-xl hover:bg-brand-dark transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Generate Payroll
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-card border {{ $stats['draft'] > 0 ? 'border-yellow-500/30' : 'border-border' }} rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Draft Payrolls</p>
            <p class="text-3xl font-display font-bold {{ $stats['draft'] > 0 ? 'text-yellow-400' : 'text-content-primary' }} mt-1">{{ $stats['draft'] }}</p>
        </div>
        <div class="bg-surface-card border {{ $stats['approved'] > 0 ? 'border-blue-500/30' : 'border-border' }} rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Awaiting Payment</p>
            <p class="text-3xl font-display font-bold {{ $stats['approved'] > 0 ? 'text-blue-400' : 'text-content-primary' }} mt-1">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-surface-card border border-border rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Paid This Month</p>
            <p class="text-2xl font-display font-bold text-brand-green mt-1">₦{{ number_format($stats['paid_this_month'], 0) }}</p>
        </div>
        <div class="bg-surface-card border {{ $stats['unpaid_total'] > 0 ? 'border-red-500/30' : 'border-border' }} rounded-2xl p-5">
            <p class="text-xs text-content-muted font-medium uppercase tracking-wider">Total Unpaid</p>
            <p class="text-2xl font-display font-bold {{ $stats['unpaid_total'] > 0 ? 'text-red-400' : 'text-content-primary' }} mt-1">₦{{ number_format($stats['unpaid_total'], 0) }}</p>
        </div>
    </div>

    {{-- Generate Payroll Form --}}
    <div x-show="showGenerate" x-cloak x-transition:enter="transition ease-out duration-200"
         class="bg-surface-card border border-border rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-border flex items-center justify-between">
            <h2 class="font-semibold text-content-primary">Generate New Payroll</h2>
            <button @click="showGenerate = false" class="text-content-muted hover:text-content-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.payroll.generate') }}" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Worker <span class="text-red-400">*</span></label>
                    <select name="worker_id" required class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                        <option value="">Select worker…</option>
                        @foreach($workers as $worker)
                        <option value="{{ $worker->id }}">{{ $worker->full_name }} — {{ $worker->salary_label }}</option>
                        @endforeach
                    </select>
                    @error('worker_id')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Period Start <span class="text-red-400">*</span></label>
                    <input type="date" name="period_start" value="{{ old('period_start', now()->startOfMonth()->toDateString()) }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                    @error('period_start')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Period End <span class="text-red-400">*</span></label>
                    <input type="date" name="period_end" value="{{ old('period_end', now()->endOfMonth()->toDateString()) }}" required
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                    @error('period_end')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Override Base Salary (₦) <span class="text-content-muted font-normal">optional</span></label>
                    <input type="number" name="override_amount" value="{{ old('override_amount') }}" min="0" step="0.01"
                           placeholder="Leave blank to auto-calculate from attendance"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Bonus (₦) <span class="text-content-muted font-normal">optional</span></label>
                    <input type="number" name="bonuses" value="{{ old('bonuses', 0) }}" min="0" step="0.01"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Deductions (₦) <span class="text-content-muted font-normal">optional</span></label>
                    <input type="number" name="deductions" value="{{ old('deductions', 0) }}" min="0" step="0.01"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                </div>
                <div>
                    <label class="block text-xs font-medium text-content-secondary mb-1.5">Notes</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="e.g. May 2026 salary"
                           class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green">
                </div>
            </div>
            <div class="mt-4 p-4 bg-surface-elevated border border-border rounded-xl text-xs text-content-muted space-y-1">
                <p><strong class="text-content-secondary">Auto-calculation:</strong> For <strong class="text-content-primary">fixed-salary</strong> workers — if no attendance has been recorded for the period, the full salary is paid automatically. If attendance IS recorded, salary is pro-rated by days present. For <strong class="text-content-primary">daily-rate</strong> workers, pay is always based on days present.</p>
                <p><strong class="text-content-secondary">Override:</strong> Fill in "Override Base Salary" to manually set the exact amount instead of using attendance data.</p>
            </div>
            <button type="submit" class="mt-4 bg-brand-green text-surface-bg font-semibold px-6 py-2.5 rounded-xl hover:bg-brand-dark transition-colors text-sm">
                Generate Payroll
            </button>
        </form>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="status" class="bg-surface-card border border-border rounded-xl px-3 py-2 text-sm text-content-primary focus:outline-none focus:border-brand-green">
            <option value="">All Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
        <select name="worker_id" class="bg-surface-card border border-border rounded-xl px-3 py-2 text-sm text-content-primary focus:outline-none focus:border-brand-green">
            <option value="">All Workers</option>
            @foreach($workers as $w)
            <option value="{{ $w->id }}" {{ request('worker_id') == $w->id ? 'selected' : '' }}>{{ $w->full_name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-brand-green text-surface-bg px-4 py-2 rounded-xl text-sm font-semibold hover:bg-brand-dark transition-colors">Filter</button>
        @if(request()->hasAny(['status','worker_id']))<a href="{{ route('admin.payroll.index') }}" class="px-4 py-2 rounded-xl text-sm border border-border text-content-secondary hover:bg-surface-elevated transition-colors">Clear</a>@endif
    </form>

    {{-- Payroll Table --}}
    <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
        @if($payrolls->isEmpty())
        <div class="py-16 text-center">
            <p class="text-content-secondary font-medium">No payroll records yet</p>
            <p class="text-content-muted text-sm mt-1">Generate the first payroll using the button above.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="border-b border-border bg-surface-elevated">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Worker</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Attendance</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-content-muted uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach($payrolls as $payroll)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-content-primary">{{ $payroll->worker->full_name }}</td>
                    <td class="px-6 py-4 text-content-secondary text-xs">{{ $payroll->period_label }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-surface-elevated rounded-full overflow-hidden">
                                <div class="h-full bg-brand-green rounded-full" style="width: {{ $payroll->attendance_rate }}%"></div>
                            </div>
                            <span class="text-xs text-content-muted">{{ $payroll->days_present }}/{{ $payroll->total_days_in_period }} days</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono font-bold text-content-primary">{{ $payroll->formatted_payable }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center text-xs px-2.5 py-1 rounded-full border font-semibold {{ $payroll->status_color }}">{{ ucfirst($payroll->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.payroll.show', $payroll) }}" class="text-brand-green hover:underline text-xs font-semibold">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-border">{{ $payrolls->links() }}</div>
        @endif
    </div>
</div>
@endsection
