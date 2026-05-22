@extends('layouts.admin')

@section('page-title', 'Financial Control')
@section('breadcrumb', 'T-Akomz Farm Wallet — Finance Overview')

@section('content')
<div class="space-y-6">

    {{-- Header row --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Financial Control</h2>
            <p class="text-sm text-content-muted mt-0.5">Farm wallet and expense management for T-Akomz Agro Estates</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!$isManager)
            @can('wallet.fund')
            <a href="{{ route('admin.finance.fund') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-surface-bg"
               style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 15px rgba(184,243,151,0.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Fund Wallet
            </a>
            @endcan
            @else
            {{-- Manager primary action: Log Expense (immediate deduction) --}}
            <a href="{{ route('admin.finance.expenses.direct') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all text-surface-bg"
               style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%); box-shadow: 0 4px 15px rgba(184,243,151,0.3);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Log Expense
            </a>
            @endif
            <a href="{{ route('admin.finance.expenses.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-content-secondary transition-all hover:text-content-primary"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                My History
            </a>
        </div>
    </div>

    {{-- Manager: My Budget card --}}
    @if($isManager && $myStats)
    <div class="rounded-2xl p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, rgba(184,243,151,0.08) 0%, rgba(17,17,17,0.95) 100%); border: 1px solid rgba(184,243,151,0.2);">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, #B8F397, transparent);"></div>
        <div class="absolute top-0 right-0 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(184,243,151,0.06) 0%, transparent 70%); transform: translate(30%,-30%);"></div>
        <div class="flex items-start justify-between mb-5">
            <div>
                <p class="text-xs font-bold text-brand-green/70 uppercase tracking-wider">My Budget</p>
                <h3 class="font-display text-xl font-bold text-content-primary mt-0.5">Manager Budget Overview</h3>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(184,243,151,0.12); border: 1px solid rgba(184,243,151,0.2);">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-xl p-3 text-center" style="background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.15);">
                <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider mb-1">Allocated</p>
                <p class="font-display font-bold text-blue-400 text-lg font-mono">₦{{ number_format($myStats['allocated'], 2) }}</p>
            </div>
            <div class="rounded-xl p-3 text-center" style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);">
                <p class="text-xs text-red-400 font-semibold uppercase tracking-wider mb-1">Spent</p>
                <p class="font-display font-bold text-red-400 text-lg font-mono">₦{{ number_format($myStats['spent'], 2) }}</p>
            </div>
            <div class="rounded-xl p-3 text-center" style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.15);">
                <p class="text-xs text-brand-green font-semibold uppercase tracking-wider mb-1">Remaining</p>
                <p class="font-display font-bold text-brand-green text-lg font-mono">₦{{ number_format($myStats['remaining'], 2) }}</p>
            </div>
        </div>
        @if($myStats['allocated'] > 0)
        @php $usedPct = min(100, round(($myStats['spent'] / $myStats['allocated']) * 100, 1)); @endphp
        <div class="mt-4">
            <div class="flex justify-between text-xs text-content-muted mb-1.5">
                <span>Budget utilisation</span>
                <span class="font-semibold {{ $usedPct >= 90 ? 'text-red-400' : ($usedPct >= 70 ? 'text-yellow-400' : 'text-brand-green') }}">{{ $usedPct }}%</span>
            </div>
            <div class="w-full h-2 rounded-full" style="background: rgba(255,255,255,0.06);">
                <div class="h-2 rounded-full transition-all duration-500"
                     style="width: {{ $usedPct }}%; background: {{ $usedPct >= 90 ? '#EF4444' : ($usedPct >= 70 ? '#EAB308' : '#B8F397') }};"></div>
            </div>
        </div>
        @endif
        <div class="mt-4 flex items-center gap-3">
            <a href="{{ route('admin.finance.expenses.direct') }}"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold text-sm text-surface-bg transition-all hover:scale-[1.01]"
               style="background: linear-gradient(135deg, #B8F397 0%, #6FAE4B 100%);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Log Expense
            </a>
            <a href="{{ route('admin.finance.expenses.index') }}"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold text-sm text-content-secondary transition-all hover:text-content-primary"
               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                View History
            </a>
        </div>
    </div>
    @endif

    {{-- KPI Cards — admin/super_admin only --}}
    @if(!$isManager)
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Funded --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
             style="background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(17,17,17,0.9) 60%); border: 1px solid rgba(59,130,246,0.15);">
            <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, #3B82F6, transparent);"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.2);">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-blue-400 uppercase tracking-wider">Total Funded</span>
            </div>
            <p class="font-display text-2xl font-bold text-content-primary">{{ $wallet->formatted_funded }}</p>
            <p class="text-xs text-content-muted mt-1">Lifetime deposits into wallet</p>
        </div>

        {{-- Total Spent --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
             style="background: linear-gradient(135deg, rgba(239,68,68,0.08) 0%, rgba(17,17,17,0.9) 60%); border: 1px solid rgba(239,68,68,0.15);">
            <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, #EF4444, transparent);"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.2);">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-red-400 uppercase tracking-wider">Total Spent</span>
            </div>
            <p class="font-display text-2xl font-bold text-content-primary">{{ $wallet->formatted_spent }}</p>
            <p class="text-xs text-content-muted mt-1">Approved & completed expenses</p>
        </div>

        {{-- Balance --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
             style="background: linear-gradient(135deg, {{ $wallet->balance > 0 ? 'rgba(184,243,151,0.10)' : 'rgba(239,68,68,0.08)' }} 0%, rgba(17,17,17,0.9) 60%); border: 1px solid {{ $wallet->balance > 0 ? 'rgba(184,243,151,0.2)' : 'rgba(239,68,68,0.2)' }};">
            <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, {{ $wallet->balance > 0 ? '#B8F397' : '#EF4444' }}, transparent);"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $wallet->balance > 0 ? 'rgba(184,243,151,0.15)' : 'rgba(239,68,68,0.15)' }}; border: 1px solid {{ $wallet->balance > 0 ? 'rgba(184,243,151,0.25)' : 'rgba(239,68,68,0.2)' }};">
                    <svg class="w-5 h-5 {{ $wallet->balance > 0 ? 'text-brand-green' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold {{ $wallet->balance > 0 ? 'text-brand-green' : 'text-red-400' }} uppercase tracking-wider">Balance</span>
            </div>
            <p class="font-display text-3xl font-black {{ $wallet->balance > 0 ? 'text-brand-green' : 'text-red-400' }}">{{ $wallet->formatted_balance }}</p>
            <p class="text-xs text-content-muted mt-1">Available for approved expenses</p>
        </div>

        {{-- Pending Approvals --}}
        <div class="rounded-2xl p-5 relative overflow-hidden"
             style="background: linear-gradient(135deg, rgba(234,179,8,0.08) 0%, rgba(17,17,17,0.9) 60%); border: 1px solid {{ $pendingCount > 0 ? 'rgba(234,179,8,0.25)' : 'rgba(255,255,255,0.08)' }};">
            <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-2xl" style="background: linear-gradient(90deg, #EAB308, transparent);"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(234,179,8,0.15); border: 1px solid rgba(234,179,8,0.2);">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-yellow-400 uppercase tracking-wider">Pending</span>
            </div>
            <p class="font-display text-2xl font-bold {{ $pendingCount > 0 ? 'text-yellow-400' : 'text-content-primary' }}">{{ $pendingCount }}</p>
            <p class="text-xs text-content-muted mt-1">Expense{{ $pendingCount !== 1 ? 's' : '' }} awaiting approval</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Pending Approvals (admin only) or Recent Transactions (manager) --}}
        <div class="xl:col-span-2 space-y-4">

            @if(!$isManager && $pendingExpenses->isNotEmpty())
            {{-- Pending Expenses Quick-Action (Admin) --}}
            <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
                <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(234,179,8,0.15); border: 1px solid rgba(234,179,8,0.2);">
                            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-content-primary">Pending Approvals</h3>
                            <p class="text-xs text-content-muted">Expense requests requiring your action</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold bg-yellow-400/20 text-yellow-400 px-2.5 py-1 rounded-full border border-yellow-400/30">{{ $pendingExpenses->count() }}</span>
                </div>

                <div class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @foreach($pendingExpenses as $expense)
                    <div class="px-5 py-4 flex items-center gap-4 hover:bg-white/[0.02] transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-content-primary truncate">{{ $expense->short_title }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full border {{ $expense->status_color }}">{{ ucfirst($expense->status) }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-content-muted">
                                <span>{{ $expense->category_label }}</span>
                                <span class="w-1 h-1 rounded-full bg-content-muted/40"></span>
                                <span>{{ $expense->creator->name }}</span>
                                <span class="w-1 h-1 rounded-full bg-content-muted/40"></span>
                                <span>{{ $expense->expense_date->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-display font-bold text-content-primary">{{ $expense->formatted_amount }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form action="{{ route('admin.finance.expenses.approve', $expense) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-surface-bg transition-all hover:scale-105"
                                        style="background: linear-gradient(135deg, #B8F397, #6FAE4B);"
                                        onclick="return confirm('Approve this expense of {{ $expense->formatted_amount }}?')">
                                    Approve
                                </button>
                            </form>
                            <a href="{{ route('admin.finance.expenses.show', $expense) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold text-content-secondary transition-all hover:text-content-primary"
                               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                Review
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recent Transactions --}}
            <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
                <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.15);">
                            <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-content-primary">Recent Transactions</h3>
                            <p class="text-xs text-content-muted">Latest 20 wallet activities</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.finance.expenses.index') }}" class="text-xs font-semibold text-brand-green hover:underline">View all</a>
                </div>

                @if($recentTransactions->isEmpty())
                <div class="py-12 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                         style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.12);">
                        <svg class="w-6 h-6 text-brand-green/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-content-secondary">No transactions yet</p>
                    <p class="text-xs text-content-muted mt-1">Transactions will appear here once activity begins.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3">Date</th>
                                <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Title</th>
                                <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3 hidden sm:table-cell">Category</th>
                                <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Amount</th>
                                <th class="text-center text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Status</th>
                                <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                            @foreach($recentTransactions as $txn)
                            <tr class="hover:bg-white/[0.015] transition-colors">
                                <td class="px-5 py-3.5 text-xs text-content-muted whitespace-nowrap">{{ $txn->expense_date->format('d M Y') }}</td>
                                <td class="px-3 py-3.5 max-w-[160px]">
                                    <p class="text-sm font-medium text-content-primary truncate">{{ $txn->short_title }}</p>
                                    <p class="text-xs text-content-muted truncate">{{ $txn->creator->name }}</p>
                                </td>
                                <td class="px-3 py-3.5 hidden sm:table-cell">
                                    <span class="text-xs font-medium text-content-secondary">{{ $txn->category_label }}</span>
                                </td>
                                <td class="px-3 py-3.5 text-right">
                                    <span class="text-sm font-bold font-mono {{ $txn->type === 'funding' ? 'text-brand-green' : 'text-content-primary' }}">
                                        {{ $txn->type === 'funding' ? '+' : '' }}{{ $txn->formatted_amount }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 text-center">
                                    <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full border {{ $txn->status_color }}">{{ ucfirst($txn->status) }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.finance.expenses.show', $txn) }}"
                                       class="text-xs font-semibold text-brand-green/70 hover:text-brand-green transition-colors">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Right: Category Breakdown --}}
        <div class="space-y-4">
            <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
                <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h3 class="text-sm font-bold text-content-primary">Spending by Category</h3>
                    <p class="text-xs text-content-muted mt-0.5">Completed expenses breakdown</p>
                </div>

                @if($categoryBreakdown->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-sm text-content-muted">No completed expenses yet.</p>
                </div>
                @else
                @php
                    $catColors = [
                        'feed'         => ['bar' => '#B8F397', 'bg' => 'rgba(184,243,151,0.12)', 'text' => '#B8F397'],
                        'labor'        => ['bar' => '#60A5FA', 'bg' => 'rgba(96,165,250,0.12)',  'text' => '#60A5FA'],
                        'fuel'         => ['bar' => '#F59E0B', 'bg' => 'rgba(245,158,11,0.12)',  'text' => '#F59E0B'],
                        'veterinary'   => ['bar' => '#A78BFA', 'bg' => 'rgba(167,139,250,0.12)', 'text' => '#A78BFA'],
                        'equipment'    => ['bar' => '#34D399', 'bg' => 'rgba(52,211,153,0.12)',  'text' => '#34D399'],
                        'maintenance'  => ['bar' => '#FB923C', 'bg' => 'rgba(251,146,60,0.12)',  'text' => '#FB923C'],
                        'logistics'    => ['bar' => '#38BDF8', 'bg' => 'rgba(56,189,248,0.12)',  'text' => '#38BDF8'],
                        'other'        => ['bar' => '#9CA3AF', 'bg' => 'rgba(156,163,175,0.12)', 'text' => '#9CA3AF'],
                    ];
                @endphp
                <div class="p-5 space-y-4">
                    @foreach($categoryBreakdown as $cat)
                    @php
                        $pct = $totalSpentBreakdown > 0 ? round(($cat->total / $totalSpentBreakdown) * 100, 1) : 0;
                        $color = $catColors[$cat->category] ?? $catColors['other'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" style="background: {{ $color['bar'] }};"></div>
                                <span class="text-xs font-semibold text-content-secondary">{{ ucwords(str_replace('_',' ',$cat->category)) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold" style="color: {{ $color['text'] }};">{{ $pct }}%</span>
                                <span class="text-xs text-content-muted font-mono">₦{{ number_format($cat->total, 2) }}</span>
                            </div>
                        </div>
                        <div class="w-full h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                            <div class="h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%; background: {{ $color['bar'] }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="px-5 pb-5">
                    <div class="rounded-xl p-3 flex items-center justify-between"
                         style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <span class="text-xs text-content-muted">Total recorded spend</span>
                        <span class="text-sm font-bold font-mono text-content-primary">₦{{ number_format($totalSpentBreakdown, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Wallet Health Card (admin/super-admin only) --}}
            @if(!$isManager)
            <div class="rounded-2xl p-5 relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(184,243,151,0.06) 0%, rgba(17,17,17,0.95) 100%); border: 1px solid rgba(184,243,151,0.12);">
                <div class="absolute top-0 right-0 w-32 h-32 rounded-full" style="background: radial-gradient(circle, rgba(184,243,151,0.06) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
                <h4 class="text-xs font-bold text-content-muted uppercase tracking-wider mb-3">Wallet: {{ $wallet->name }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-content-muted">Total Funded</span>
                        <span class="font-semibold text-blue-400 font-mono">{{ $wallet->formatted_funded }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-content-muted">Total Spent</span>
                        <span class="font-semibold text-red-400 font-mono">{{ $wallet->formatted_spent }}</span>
                    </div>
                    <div class="my-2" style="border-top: 1px solid rgba(255,255,255,0.06);"></div>
                    <div class="flex justify-between">
                        <span class="text-sm font-bold text-content-primary">Balance</span>
                        <span class="font-display font-black text-base {{ $wallet->balance >= 0 ? 'text-brand-green' : 'text-red-400' }} font-mono">{{ $wallet->formatted_balance }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Admin: Manager Budgets table --}}
    @if(!$isManager && $managers->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background: rgba(17,17,17,0.8); border: 1px solid rgba(255,255,255,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.15);">
                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-content-primary">Manager Budgets</h3>
                    <p class="text-xs text-content-muted">Per-manager allocation vs spending</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-content-muted px-2.5 py-1 rounded-full"
                  style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                {{ $managers->count() }} manager{{ $managers->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3">Manager</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Allocated</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Spent</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3">Remaining</th>
                        <th class="text-left text-xs font-semibold text-content-muted uppercase tracking-wider px-3 py-3 hidden md:table-cell">Utilisation</th>
                        <th class="text-right text-xs font-semibold text-content-muted uppercase tracking-wider px-5 py-3">History</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: rgba(255,255,255,0.04);">
                    @foreach($managers as $mgr)
                    @php
                        $stats   = $managerStats[$mgr->id] ?? ['allocated' => 0, 'spent' => 0, 'remaining' => 0];
                        $usedPct = $stats['allocated'] > 0 ? min(100, round(($stats['spent'] / $stats['allocated']) * 100, 1)) : 0;
                        $barColor = $usedPct >= 90 ? '#EF4444' : ($usedPct >= 70 ? '#EAB308' : '#B8F397');
                    @endphp
                    <tr class="hover:bg-white/[0.015] transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-surface-bg"
                                     style="background: linear-gradient(135deg, #B8F397, #6FAE4B);">
                                    {{ strtoupper(substr($mgr->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-content-primary">{{ $mgr->name }}</p>
                                    <p class="text-xs text-content-muted">{{ $mgr->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-right">
                            <span class="text-sm font-bold font-mono text-blue-400">₦{{ number_format($stats['allocated'], 2) }}</span>
                        </td>
                        <td class="px-3 py-4 text-right">
                            <span class="text-sm font-bold font-mono text-red-400">₦{{ number_format($stats['spent'], 2) }}</span>
                        </td>
                        <td class="px-3 py-4 text-right">
                            <span class="text-sm font-bold font-mono {{ $stats['remaining'] > 0 ? 'text-brand-green' : 'text-content-muted' }}">
                                ₦{{ number_format($stats['remaining'], 2) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            @if($stats['allocated'] > 0)
                            <div class="flex items-center gap-3 min-w-[120px]">
                                <div class="flex-1 h-1.5 rounded-full" style="background: rgba(255,255,255,0.06);">
                                    <div class="h-1.5 rounded-full transition-all duration-500"
                                         style="width: {{ $usedPct }}%; background: {{ $barColor }};"></div>
                                </div>
                                <span class="text-xs font-semibold w-10 text-right"
                                      style="color: {{ $barColor }};">{{ $usedPct }}%</span>
                            </div>
                            @else
                            <span class="text-xs text-content-muted">No allocation</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.finance.expenses.index', ['manager_id' => $mgr->id]) }}"
                               class="text-xs font-bold text-brand-green/60 hover:text-brand-green transition-colors whitespace-nowrap">
                                View History →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
