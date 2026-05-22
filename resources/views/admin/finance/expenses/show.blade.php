@extends('layouts.admin')

@section('page-title', 'Expense — ' . Str::limit($transaction->short_title, 40))
@section('breadcrumb', 'Financial Control — Expense Detail')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.finance.expenses.index') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center text-content-muted hover:text-content-primary transition-all"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-display text-xl font-bold text-content-primary leading-tight">{{ $transaction->short_title }}</h2>
                <p class="text-xs text-content-muted mt-0.5">Expense #{{ $transaction->id }} — Created {{ $transaction->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $transaction->status_color }}">
                {{ ucfirst($transaction->status) }}
            </span>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full text-content-secondary"
                  style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                {{ $transaction->category_label }}
            </span>
        </div>
    </div>

    {{-- Amount Hero --}}
    <div class="rounded-2xl p-6 relative overflow-hidden"
         style="background: linear-gradient(135deg, rgba(184,243,151,0.06) 0%, rgba(17,17,17,0.95) 100%); border: 1px solid rgba(184,243,151,0.15);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, #B8F397, transparent);"></div>
        <div class="absolute top-0 right-0 w-40 h-40 rounded-full opacity-30" style="background: radial-gradient(circle, rgba(184,243,151,0.08) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-1">Expense Amount</p>
                <p class="font-display text-4xl font-black text-content-primary">{{ $transaction->formatted_amount }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-content-muted">Expense Date</p>
                <p class="text-lg font-bold text-content-primary">{{ $transaction->expense_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @php
            $details = [
                ['label' => 'Created By', 'value' => $transaction->creator->name, 'sub' => $transaction->creator->email],
                ['label' => 'Category', 'value' => $transaction->category_label, 'sub' => 'Expense category'],
                ['label' => 'Expense Date', 'value' => $transaction->expense_date->format('l, d F Y'), 'sub' => 'When the expense is/was incurred'],
                ['label' => 'Vendor / Supplier', 'value' => $transaction->vendor_name ?? 'Not specified', 'sub' => 'Vendor or supplier name'],
                ['label' => 'Transaction Type', 'value' => ucwords(str_replace('_', ' ', $transaction->type)), 'sub' => 'Current type in the flow'],
                ['label' => 'Wallet', 'value' => $transaction->wallet->name, 'sub' => 'Source wallet'],
            ];
        @endphp
        @foreach($details as $d)
        <div class="rounded-xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
            <p class="text-xs font-semibold text-content-muted uppercase tracking-wider mb-1">{{ $d['label'] }}</p>
            <p class="text-sm font-bold text-content-primary">{{ $d['value'] }}</p>
            @if($d['sub'])
            <p class="text-xs text-content-muted mt-0.5">{{ $d['sub'] }}</p>
            @endif
        </div>
        @endforeach
    </div>

    @if($transaction->approved_by && $transaction->approver)
    <div class="rounded-xl p-4 flex items-center gap-3" style="background: rgba(59,130,246,0.06); border: 1px solid rgba(59,130,246,0.12);">
        <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <div>
            <p class="text-xs font-semibold text-blue-400">{{ $transaction->status === 'rejected' ? 'Rejected' : 'Approved' }} by: {{ $transaction->approver->name }}</p>
            <p class="text-xs text-content-muted">{{ $transaction->updated_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
    @endif

    {{-- Description --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <h3 class="text-sm font-bold text-content-primary">Full Description</h3>
        </div>
        <div class="p-5">
            <p class="text-sm text-content-secondary leading-relaxed whitespace-pre-wrap">{{ $transaction->description }}</p>
        </div>
    </div>

    {{-- Rejection Reason --}}
    @if($transaction->status === 'rejected' && $transaction->rejection_reason)
    <div class="rounded-2xl p-5" style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.2);">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h4 class="text-sm font-bold text-red-400">Rejection Reason</h4>
        </div>
        <p class="text-sm text-red-300/80 leading-relaxed">{{ $transaction->rejection_reason }}</p>
    </div>
    @endif

    {{-- Receipt --}}
    @if($transaction->receipt_file)
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <h3 class="text-sm font-bold text-content-primary">Receipt</h3>
        </div>
        <div class="p-5">
            @php $ext = pathinfo($transaction->receipt_file, PATHINFO_EXTENSION); @endphp
            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
            <img src="{{ route('admin.finance.expenses.receipt', $transaction) }}"
                 alt="Receipt"
                 class="max-w-full rounded-xl border border-white/10"
                 style="max-height: 400px; object-fit: contain;">
            @else
            <a href="{{ route('admin.finance.expenses.receipt', $transaction) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-brand-green transition-all hover:scale-105"
               style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.2);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"/>
                </svg>
                Download Receipt ({{ strtoupper($ext) }})
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- ── ADMIN APPROVAL SECTION ── --}}
    @if($transaction->status === 'pending' && !$isManager && auth()->user()->can('expense.approve'))
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(184,243,151,0.15);">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(90deg, rgba(184,243,151,0.05) 0%, transparent 100%);">
            <h3 class="text-sm font-bold text-content-primary">Admin Action Required</h3>
            <p class="text-xs text-content-muted mt-0.5">Review the expense details above and take action.</p>
        </div>

        <div class="p-5 space-y-4">
            {{-- Approve --}}
            <form action="{{ route('admin.finance.expenses.approve', $transaction) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-surface-bg transition-all hover:scale-[1.01] active:scale-[0.99]"
                        style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 15px rgba(184,243,151,0.2);"
                        onclick="return confirm('Approve this expense of {{ $transaction->formatted_amount }} from the farm wallet?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Expense ({{ $transaction->formatted_amount }})
                </button>
            </form>

            {{-- Reject with reason --}}
            <div x-data="{ showReject: false }">
                <button type="button"
                        @click="showReject = !showReject"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-semibold text-sm text-red-400 transition-all hover:scale-[1.01]"
                        style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Expense
                </button>

                <div x-show="showReject" x-cloak x-transition class="mt-4">
                    <form action="{{ route('admin.finance.expenses.reject', $transaction) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-content-secondary">Rejection Reason <span class="text-red-400">*</span></label>
                            <textarea name="rejection_reason"
                                      rows="3"
                                      placeholder="Explain why this expense is being rejected (minimum 10 characters)..."
                                      class="w-full px-4 py-3 rounded-xl text-sm text-content-primary resize-none outline-none focus:ring-2 ring-red-400/50
                                             @error('rejection_reason') ring-2 ring-red-500/50 @enderror"
                                      style="background: rgba(255,255,255,0.04); border: 1px solid rgba(239,68,68,0.2);"
                                      required>{{ old('rejection_reason') }}</textarea>
                            @error('rejection_reason')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl font-bold text-sm text-red-400 transition-all"
                                style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25);">
                            Confirm Rejection
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── MANAGER CONFIRM SPENDING SECTION ── --}}
    @if($transaction->status === 'approved' && auth()->id() === $transaction->created_by)
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(59,130,246,0.2);">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05); background: linear-gradient(90deg, rgba(59,130,246,0.06) 0%, transparent 100%);">
            <h3 class="text-sm font-bold text-content-primary">Confirm Spending</h3>
            <p class="text-xs text-content-muted mt-0.5">This expense has been approved. Once you have made the purchase, confirm spending to mark it as completed. Optionally upload a receipt.</p>
        </div>

        <form action="{{ route('admin.finance.expenses.confirm', $transaction) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-content-secondary">Receipt / Proof of Purchase <span class="text-content-muted text-xs">(optional — JPG, PNG, PDF up to 5MB)</span></label>
                <label class="flex flex-col items-center justify-center w-full h-28 rounded-xl cursor-pointer transition-all hover:border-brand-green/40"
                       style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.1);">
                    <svg class="w-8 h-8 text-content-muted mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-content-muted">Click to upload receipt</p>
                    <input type="file" name="receipt" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                </label>
                @error('receipt')
                <p class="text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-surface-bg transition-all hover:scale-[1.01]"
                    style="background: linear-gradient(135deg, #3B82F6, #1D4ED8); box-shadow: 0 4px 15px rgba(59,130,246,0.2);"
                    onclick="return confirm('Confirm that you have completed this purchase of {{ $transaction->formatted_amount }}?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Confirm Purchase Completed
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
