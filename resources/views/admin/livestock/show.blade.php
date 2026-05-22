@extends('layouts.admin')

@section('page-title', $batch->batch_name)
@section('breadcrumb', 'Farm ERP · Livestock · ' . $batch->batch_name)

@section('content')
<div class="space-y-6" x-data="{ mortalityForm: false }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex items-start gap-4">
            <a href="{{ route('admin.livestock.index') }}" class="btn-outline text-sm p-2 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-3xl">{{ $batch->animal_type_icon }}</span>
                    <h2 class="font-display text-2xl font-bold text-content-primary">{{ $batch->batch_name }}</h2>
                    <span class="badge text-xs px-2.5 py-1 rounded-lg border {{ $batch->status_color }}">{{ $batch->status_label }}</span>
                </div>
                <p class="text-sm text-content-muted mt-1">
                    {{ $batch->animal_type_label }} · Purchased {{ $batch->purchase_date->format('M d, Y') }}
                    @if($batch->expected_sale_date) · Expected sale {{ $batch->expected_sale_date->format('M d, Y') }} @endif
                    · Created by {{ $batch->creator?->name ?? '—' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.livestock.expenses.create', $batch) }}" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </a>
            <button @click="mortalityForm = !mortalityForm" class="btn-outline text-sm text-red-400 border-red-400/30 hover:bg-red-400/10">
                Record Mortality
            </button>
        </div>
    </div>

    {{-- Mortality Form (Alpine toggled) --}}
    <div x-show="mortalityForm" x-cloak x-transition
         class="card p-5 border border-red-500/20" style="background: rgba(239,68,68,0.04);">
        <h3 class="text-sm font-bold text-red-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Record Animal Loss
        </h3>
        <form action="{{ route('admin.livestock.mortality', $batch) }}" method="POST" class="flex items-end gap-4 flex-wrap">
            @csrf
            <div class="flex-1 min-w-[160px]">
                <label class="label" for="mortality_count">Number of Animals Lost <span class="text-red-400">*</span></label>
                <input type="number" id="mortality_count" name="count"
                       min="1" max="{{ $batch->quantity_current }}"
                       placeholder="e.g. 5"
                       class="input @error('count') border-red-500 @enderror">
                @error('count')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-content-muted mt-1">Max: {{ $batch->quantity_current }}</p>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="label" for="mortality_notes">Notes <span class="text-content-muted text-xs">(optional)</span></label>
                <input type="text" id="mortality_notes" name="notes"
                       placeholder="Cause of loss..."
                       class="input">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-danger text-sm">Confirm Loss</button>
                <button type="button" @click="mortalityForm = false" class="btn-outline text-sm">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Cost Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Purchase Cost</p>
            <p class="text-lg font-display font-bold text-content-primary">{{ $batch->formatted_purchase_cost }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Feed</p>
            <p class="text-lg font-display font-bold text-content-primary">₦{{ number_format($batch->feed_cost, 2) }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Treatment/Vaccination</p>
            <p class="text-lg font-display font-bold text-content-primary">₦{{ number_format($batch->treatment_cost, 2) }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Labor</p>
            <p class="text-lg font-display font-bold text-content-primary">₦{{ number_format($batch->labor_cost, 2) }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Transport</p>
            <p class="text-lg font-display font-bold text-content-primary">₦{{ number_format($batch->transport_cost, 2) }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Other</p>
            <p class="text-lg font-display font-bold text-content-primary">₦{{ number_format($batch->other_cost, 2) }}</p>
        </div>
        <div class="card p-4 text-center col-span-1" style="background: rgba(184,243,151,0.06); border-color: rgba(184,243,151,0.2);">
            <p class="text-xs text-brand-green mb-1 font-semibold">Total Cost</p>
            <p class="text-xl font-display font-bold text-brand-green">{{ $batch->formatted_total_cost }}</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-content-muted mb-1">Cost per Animal</p>
            <p class="text-xl font-display font-bold text-yellow-400">{{ $batch->formatted_cost_per_animal }}</p>
        </div>
    </div>

    {{-- Animal Count + Mortality --}}
    <div class="card p-5">
        <h3 class="text-sm font-bold text-content-muted uppercase tracking-wider mb-4">Livestock Count</h3>
        <div class="flex items-center gap-8 flex-wrap">
            <div class="text-center">
                <p class="text-xs text-content-muted mb-1">Purchased</p>
                <p class="text-3xl font-display font-bold text-content-primary">{{ $batch->quantity_purchased }}</p>
            </div>
            <div class="text-content-muted text-2xl">→</div>
            <div class="text-center">
                <p class="text-xs text-content-muted mb-1">Current</p>
                <p class="text-3xl font-display font-bold text-brand-green">{{ $batch->quantity_current }}</p>
            </div>
            @if($batch->mortality_count > 0)
            <div class="text-center">
                <p class="text-xs text-content-muted mb-1">Mortality</p>
                <p class="text-3xl font-display font-bold text-red-400">{{ $batch->mortality_count }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-content-muted mb-1">Survival Rate</p>
                @php $survival = $batch->quantity_purchased > 0 ? round(($batch->quantity_current / $batch->quantity_purchased) * 100, 1) : 100; @endphp
                <p class="text-3xl font-display font-bold {{ $survival >= 90 ? 'text-brand-green' : ($survival >= 70 ? 'text-yellow-400' : 'text-red-400') }}">{{ $survival }}%</p>
            </div>
            @endif
        </div>
        @if($batch->notes)
        <div class="mt-4 pt-4 border-t border-white/5">
            <p class="text-xs text-content-muted mb-1">Notes</p>
            <p class="text-sm text-content-secondary">{{ $batch->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Expense History --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <h3 class="text-sm font-bold text-content-primary">Expense History</h3>
            <a href="{{ route('admin.livestock.expenses.create', $batch) }}" class="btn-primary text-xs py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </a>
        </div>

        @if($batch->batchExpenses->isEmpty())
        <div class="p-8 text-center">
            <p class="text-sm text-content-muted">No expenses recorded yet.</p>
            <a href="{{ route('admin.livestock.expenses.create', $batch) }}" class="btn-outline text-sm mt-3 inline-flex">Record First Expense</a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">Vendor</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase">Amount</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-content-muted uppercase">Receipt</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase">By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($batch->batchExpenses->sortByDesc('expense_date') as $expense)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-4 py-3 text-sm text-content-muted font-mono">{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-md border {{ $expense->type_color }}">{{ $expense->type_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-content-secondary max-w-xs truncate">{{ $expense->description }}</td>
                        <td class="px-4 py-3 text-sm text-content-muted">{{ $expense->vendor_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm font-mono font-bold text-content-primary">{{ $expense->formatted_amount }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($expense->receipt_file)
                            <a href="{{ Storage::url($expense->receipt_file) }}" target="_blank" rel="noopener noreferrer" 
                               class="text-xs text-blue-400 hover:underline">View</a>
                            @else
                            <span class="text-xs text-content-muted">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-content-muted">{{ $expense->creator?->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: rgba(184,243,151,0.04); border-top: 1px solid rgba(184,243,151,0.15);">
                        <td colspan="4" class="px-4 py-3 text-xs font-bold text-brand-green uppercase">Total Expenses</td>
                        <td class="px-4 py-3 text-right text-sm font-bold font-mono text-brand-green">{{ $batch->formatted_total_expenses }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
