@extends('layouts.admin')
@section('page-title', 'Payroll — ' . $payroll->worker->full_name)

@section('content')
<div class="p-6 lg:p-8 space-y-8">

    {{-- Back --}}
    <a href="{{ route('admin.payroll.index') }}" class="text-content-muted hover:text-brand-green text-sm flex items-center gap-2 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        All Payrolls
    </a>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Payroll Summary --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Header card --}}
            <div class="bg-surface-card border border-border rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h1 class="text-xl font-display font-bold text-content-primary">{{ $payroll->worker->full_name }}</h1>
                        <p class="text-content-secondary text-sm mt-0.5">{{ $payroll->period_label }}</p>
                        <p class="text-xs text-content-muted mt-1 capitalize">{{ str_replace('_', ' ', $payroll->worker->role) }} · {{ $payroll->worker->employment_type }}</p>
                    </div>
                    <span class="inline-flex items-center text-sm px-3 py-1.5 rounded-full border font-semibold {{ $payroll->status_color }}">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>

                {{-- Salary Breakdown --}}
                <div class="mt-6 border border-border rounded-xl overflow-hidden">
                    <div class="px-5 py-3 bg-surface-elevated border-b border-border">
                        <p class="text-xs font-semibold text-content-muted uppercase tracking-wider">Salary Breakdown</p>
                    </div>
                    <div class="divide-y divide-border text-sm">
                        <div class="px-5 py-3 flex justify-between">
                            <span class="text-content-secondary">Base Salary ({{ $payroll->days_present }} days present + {{ $payroll->half_days }} half days / {{ $payroll->total_days_in_period }} working days)</span>
                            <span class="font-mono font-semibold text-content-primary">₦{{ number_format($payroll->base_salary, 2) }}</span>
                        </div>
                        @if($payroll->bonuses > 0)
                        <div class="px-5 py-3 flex justify-between">
                            <span class="text-content-secondary">Bonuses</span>
                            <span class="font-mono font-semibold text-brand-green">+ ₦{{ number_format($payroll->bonuses, 2) }}</span>
                        </div>
                        @endif
                        @if($payroll->deductions > 0)
                        <div class="px-5 py-3 flex justify-between">
                            <span class="text-content-secondary">Deductions</span>
                            <span class="font-mono font-semibold text-red-400">- ₦{{ number_format($payroll->deductions, 2) }}</span>
                        </div>
                        @endif
                        <div class="px-5 py-4 flex justify-between bg-surface-elevated">
                            <span class="font-bold text-content-primary">Total Payable</span>
                            <span class="font-mono font-bold text-2xl text-brand-green">{{ $payroll->formatted_payable }}</span>
                        </div>
                    </div>
                </div>

                {{-- Attendance Rate --}}
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-content-muted mb-1">
                        <span>Attendance Rate</span>
                        <span>{{ $payroll->attendance_rate }}%</span>
                    </div>
                    <div class="h-2 bg-surface-elevated rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $payroll->attendance_rate >= 80 ? 'bg-brand-green' : ($payroll->attendance_rate >= 60 ? 'bg-yellow-400' : 'bg-red-400') }}"
                             style="width: {{ $payroll->attendance_rate }}%"></div>
                    </div>
                    <div class="flex gap-4 mt-2 text-xs text-content-muted">
                        <span><span class="text-brand-green font-semibold">{{ $payroll->days_present }}</span> Present</span>
                        <span><span class="text-yellow-400 font-semibold">{{ $payroll->half_days }}</span> Half Days</span>
                        <span><span class="text-red-400 font-semibold">{{ $payroll->days_absent }}</span> Absent</span>
                    </div>
                </div>

                @if($payroll->notes)
                <p class="mt-4 text-xs text-content-muted bg-surface-elevated rounded-xl px-4 py-3">{{ $payroll->notes }}</p>
                @endif
            </div>

            {{-- Attendance Log for Period --}}
            <div class="bg-surface-card border border-border rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-semibold text-content-primary">Attendance Log for Period</h3>
                </div>
                @if($attendance->isEmpty())
                <div class="py-6 px-6 space-y-1">
                    <p class="text-content-muted text-sm text-center">No attendance records found for this period.</p>
                    @if($payroll->base_salary > 0 && $payroll->worker->salary_type === 'fixed')
                    <p class="text-xs text-center text-yellow-400/80 bg-yellow-500/5 border border-yellow-500/15 rounded-xl px-4 py-2 mt-3">
                        Since no attendance was tracked, the full salary ({{ $payroll->worker->formatted_salary }}) was used automatically. Record attendance in the Attendance section to enable pro-rated calculation in future payrolls.
                    </p>
                    @endif
                </div>
                @else
                <div class="divide-y divide-border max-h-72 overflow-y-auto">
                    @foreach($attendance as $att)
                    <div class="px-6 py-2.5 flex items-center justify-between gap-3 text-sm">
                        <span class="text-content-secondary">{{ $att->date->format('D, M d') }}</span>
                        <div class="flex items-center gap-2">
                            @if($att->check_in_time)<span class="text-xs text-content-muted font-mono">{{ $att->check_in_time }}–{{ $att->check_out_time ?? '?' }}</span>@endif
                            <span class="text-xs px-2 py-0.5 rounded-full border font-semibold {{ $att->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        {{-- Action Panel --}}
        <div class="space-y-4">

            {{-- Payment Status --}}
            @if($payroll->status === 'paid' && $payroll->payment)
            <div class="bg-green-500/10 border border-green-500/20 rounded-2xl p-5 space-y-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-semibold text-green-400">Salary Paid</span>
                </div>
                <dl class="space-y-1.5 text-sm">
                    <div class="flex justify-between"><dt class="text-green-400/70">Amount</dt><dd class="text-green-300 font-mono font-bold">{{ $payroll->payment->formatted_amount }}</dd></div>
                    <div class="flex justify-between"><dt class="text-green-400/70">Date</dt><dd class="text-green-300">{{ $payroll->payment->date_paid->format('M d, Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-green-400/70">Method</dt><dd class="text-green-300 capitalize">{{ str_replace('_', ' ', $payroll->payment->payment_method) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-green-400/70">Paid By</dt><dd class="text-green-300">{{ $payroll->payment->paidBy?->name ?? 'System' }}</dd></div>
                    @if($payroll->payment->reference)<div class="flex justify-between"><dt class="text-green-400/70">Reference</dt><dd class="text-green-300 font-mono text-xs">{{ $payroll->payment->reference }}</dd></div>@endif
                </dl>
                @if($payroll->payment->walletTransaction)
                <a href="{{ route('admin.finance.expenses.show', $payroll->payment->walletTransaction) }}"
                   class="mt-2 block text-xs text-green-400/70 hover:text-green-400 transition-colors">
                    View wallet transaction →
                </a>
                @endif
            </div>
            @endif

            {{-- Approve --}}
            @if($payroll->status === 'draft')
            <div class="bg-surface-card border border-border rounded-2xl p-5">
                <h3 class="font-semibold text-content-primary mb-3">Approve Payroll</h3>
                <p class="text-xs text-content-muted mb-4">Review the salary breakdown above, then approve to make this payroll ready for payment.</p>
                <form method="POST" action="{{ route('admin.payroll.approve', $payroll) }}">
                    @csrf
                    <button type="submit"
                            class="w-full bg-blue-500/20 border border-blue-500/30 text-blue-400 font-semibold py-2.5 rounded-xl hover:bg-blue-500/30 transition-colors text-sm">
                        Approve Payroll
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.payroll.destroy', $payroll) }}" class="mt-2"
                      onsubmit="return confirm('Delete this payroll?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-red-400 hover:text-red-300 text-xs py-2">Delete Draft</button>
                </form>
            </div>
            @endif

            {{-- Process Payment --}}
            @if($payroll->status === 'approved')
            <div class="bg-surface-card border border-border rounded-2xl p-5">
                <h3 class="font-semibold text-content-primary mb-1">Process Payment</h3>
                <p class="text-xs text-content-muted mb-1">Wallet balance: <span class="font-semibold text-content-primary">{{ $wallet->formatted_balance }}</span></p>
                @if($wallet->balance < $payroll->total_payable)
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 mb-4">
                    <p class="text-xs text-red-400">⚠ Insufficient wallet balance. Fund the wallet before proceeding.</p>
                    <a href="{{ route('admin.finance.fund') }}" class="text-xs text-red-400 hover:underline mt-1 block">Fund wallet →</a>
                </div>
                @endif
                <form method="POST" action="{{ route('admin.payroll.pay', $payroll) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Payment Method <span class="text-red-400">*</span></label>
                        <select name="payment_method" required class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Reference <span class="text-content-muted font-normal">(optional)</span></label>
                        <input type="text" name="reference" placeholder="Transfer ref, cheque no…"
                               class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-content-secondary mb-1.5">Notes <span class="text-content-muted font-normal">(optional)</span></label>
                        <input type="text" name="notes"
                               class="w-full bg-surface-elevated border border-border rounded-xl px-4 py-2.5 text-sm text-content-primary focus:outline-none focus:border-brand-green">
                    </div>
                    <div class="border border-border rounded-xl p-3 bg-surface-elevated text-sm">
                        <p class="text-content-muted text-xs mb-1">This will:</p>
                        <ul class="text-xs text-content-secondary space-y-0.5">
                            <li>✓ Deduct <span class="text-brand-green font-semibold">{{ $payroll->formatted_payable }}</span> from farm wallet</li>
                            <li>✓ Log a <span class="text-content-primary font-medium">labor</span> expense transaction</li>
                            <li>✓ Mark this payroll as <span class="text-brand-green font-semibold">Paid</span></li>
                        </ul>
                    </div>
                    <button type="submit"
                            class="w-full bg-brand-green text-surface-bg font-semibold py-3 rounded-xl hover:bg-brand-dark transition-colors text-sm flex items-center justify-center gap-2"
                            {{ $wallet->balance < $payroll->total_payable ? 'disabled style=opacity:0.4;cursor:not-allowed' : '' }}>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Pay {{ $payroll->formatted_payable }}
                    </button>
                </form>
            </div>
            @endif

            {{-- Worker quick info --}}
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-3">
                <h3 class="text-xs font-semibold text-content-muted uppercase tracking-wider">Worker Details</h3>
                <dl class="space-y-2 text-sm">
                    @if($payroll->worker->phone)<div class="flex justify-between"><dt class="text-content-muted">Phone</dt><dd class="text-content-secondary">{{ $payroll->worker->phone }}</dd></div>@endif
                    @if($payroll->worker->bank_name)<div class="flex justify-between"><dt class="text-content-muted">Bank</dt><dd class="text-content-secondary">{{ $payroll->worker->bank_name }}</dd></div>@endif
                    @if($payroll->worker->bank_account)<div class="flex justify-between"><dt class="text-content-muted">Account</dt><dd class="text-content-secondary font-mono">{{ $payroll->worker->bank_account }}</dd></div>@endif
                </dl>
                <a href="{{ route('admin.workers.show', $payroll->worker) }}" class="text-xs text-brand-green hover:underline">View full profile →</a>
            </div>

            {{-- Meta --}}
            <div class="bg-surface-card border border-border rounded-2xl p-5 space-y-2 text-xs text-content-muted">
                <p>Generated by <span class="text-content-secondary">{{ $payroll->generator?->name ?? 'System' }}</span></p>
                <p>Generated on <span class="text-content-secondary">{{ $payroll->created_at->format('M d, Y H:i') }}</span></p>
                @if($payroll->approver)<p>Approved by <span class="text-content-secondary">{{ $payroll->approver->name }}</span></p>@endif
            </div>
        </div>
    </div>
</div>
@endsection
