@extends('layouts.admin')

@section('page-title', $isManager ? 'My Expense Requests' : 'All Expense Transactions')
@section('breadcrumb', 'Financial Control — ' . ($isManager ? 'Your expense history' : 'Full transaction ledger'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">{{ $isManager ? 'My Expense Requests' : 'All Transactions' }}</h2>
            <p class="text-sm text-content-muted mt-0.5">
                {{ $isManager ? 'Track your submitted expense requests and their status' : 'Full ledger of all wallet transactions' }}
            </p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            @can('expense.create')
            @if($isManager)
            {{-- Manager: direct spend (immediate deduction) --}}
            <a href="{{ route('admin.finance.expenses.direct') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-surface-bg transition-all hover:scale-[1.01]"
               style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Log Expense
            </a>
            @else
            {{-- Admin: expense request (approval flow) --}}
            <a href="{{ route('admin.finance.expenses.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-surface-bg transition-all hover:scale-[1.01]"
               style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New Request
            </a>
            @endif
            @endcan
            @can('wallet.view')
            <a href="{{ route('admin.finance.export', request()->query()) }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-content-secondary transition-all hover:text-brand-green"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            @endcan
            <a href="{{ route('admin.finance.dashboard') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-content-secondary transition-all hover:text-content-primary"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <form action="{{ route('admin.finance.expenses.index') }}" method="GET"
              class="px-5 py-4 flex flex-wrap gap-3 items-end">

            {{-- Manager filter (admin only) --}}
            @if(!$isManager && $managers->isNotEmpty())
            <div class="flex-1 min-w-[160px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">Manager</label>
                <select name="manager_id"
                        class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">All Staff</option>
                    @foreach($managers as $mgr)
                    <option value="{{ $mgr->id }}" {{ request('manager_id') == $mgr->id ? 'selected' : '' }}>
                        {{ $mgr->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Type filter --}}
            <div class="flex-1 min-w-[140px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">Type</label>
                <select name="type"
                        class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">All Types</option>
                    <option value="funding" {{ request('type') === 'funding' ? 'selected' : '' }}>Credits (Funding)</option>
                    <option value="expense_request" {{ request('type') === 'expense_request' ? 'selected' : '' }}>Pending Requests</option>
                    <option value="expense_approved" {{ request('type') === 'expense_approved' ? 'selected' : '' }}>Approved Expenses</option>
                    <option value="expense_spent" {{ request('type') === 'expense_spent' ? 'selected' : '' }}>Completed (Spent)</option>
                </select>
            </div>

            {{-- Status filter --}}
            <div class="flex-1 min-w-[140px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">All Statuses</option>
                    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'completed' => 'Completed'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category filter --}}
            <div class="flex-1 min-w-[140px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">Category</label>
                <select name="category"
                        class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                        style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                    <option value="">All Categories</option>
                    @foreach(['feed' => 'Feed', 'labor' => 'Labor', 'fuel' => 'Fuel', 'veterinary' => 'Veterinary', 'equipment' => 'Equipment', 'maintenance' => 'Maintenance', 'logistics' => 'Logistics', 'other' => 'Other'] as $val => $label)
                    <option value="{{ $val }}" {{ request('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="flex-1 min-w-[140px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
            </div>

            {{-- Date To --}}
            <div class="flex-1 min-w-[140px] space-y-1.5">
                <label class="block text-xs font-semibold text-content-muted uppercase tracking-wider">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 rounded-lg text-sm text-content-primary outline-none"
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-semibold text-surface-bg transition-all"
                        style="background: #B8F397; color: #050505;">
                    Filter
                </button>
                <a href="{{ route('admin.finance.expenses.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-content-muted hover:text-content-primary transition-all"
                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                    Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <p class="text-sm font-semibold text-content-secondary">
                {{ $transactions->total() }} transaction{{ $transactions->total() !== 1 ? 's' : '' }} found
            </p>
            <p class="text-xs text-content-muted">Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}</p>
        </div>

        @if($transactions->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: rgba(184,243,151,0.06); border: 1px solid rgba(184,243,151,0.1);">
                <svg class="w-7 h-7 text-brand-green/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-content-secondary">No transactions match your filters</p>
            <p class="text-xs text-content-muted mt-1.5">Try adjusting the filters or <a href="{{ route('admin.finance.expenses.index') }}" class="text-brand-green hover:underline">clear all filters</a>.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3 whitespace-nowrap">Date</th>
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Title</th>
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3 hidden md:table-cell">Category</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Amount</th>
                        @if(!$isManager)
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3 hidden lg:table-cell">Created By</th>
                        @endif
                        <th class="text-center text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Status</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-white/[0.015] transition-colors group">
                        <td class="px-5 py-4 text-xs text-content-muted whitespace-nowrap">{{ $txn->expense_date->format('d M Y') }}</td>
                        <td class="px-3 py-4 max-w-[200px]">
                            <p class="text-sm font-semibold text-content-primary truncate">{{ $txn->short_title }}</p>
                            @if($txn->vendor_name)
                            <p class="text-xs text-content-muted truncate">{{ $txn->vendor_name }}</p>
                            @endif
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            <span class="text-xs font-medium px-2 py-1 rounded-lg text-content-secondary"
                                  style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.07);">
                                {{ $txn->category_label }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-right">
                            <span class="text-sm font-bold font-mono {{ $txn->type === 'funding' ? 'text-brand-green' : 'text-content-primary' }}">
                                {{ $txn->type === 'funding' ? '+' : '' }}{{ $txn->formatted_amount }}
                            </span>
                        </td>
                        @if(!$isManager)
                        <td class="px-3 py-4 hidden lg:table-cell">
                            <p class="text-sm text-content-secondary">{{ $txn->creator->name }}</p>
                            <p class="text-xs text-content-muted">{{ $txn->creator->email }}</p>
                        </td>
                        @endif
                        <td class="px-3 py-4 text-center">
                            <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full border {{ $txn->status_color }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.finance.expenses.show', $txn) }}"
                               class="text-xs font-bold text-brand-green/60 hover:text-brand-green transition-colors group-hover:text-brand-green">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-5 py-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
            {{ $transactions->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
