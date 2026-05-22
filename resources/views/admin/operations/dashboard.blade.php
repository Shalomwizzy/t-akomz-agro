@extends('layouts.admin')

@section('page-title', 'Farm Operations Overview')
@section('breadcrumb', 'ERP · Operations Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Farm Operations</h2>
            <p class="text-sm text-content-muted mt-0.5">Real-time overview of livestock batches, capital and alerts</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.livestock.create') }}" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Batch
            </a>
            <a href="{{ route('admin.farm-supplies.index') }}" class="btn-outline text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Supplies
            </a>
        </div>
    </div>

    {{-- ── KPI Cards Row ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Active Batches --}}
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider font-semibold">Active Batches</p>
                    <p class="text-3xl font-display font-bold text-brand-green mt-1">{{ $activeBatches }}</p>
                    <p class="text-xs text-content-muted mt-1">of {{ $totalBatches }} total</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.15);">
                    🌾
                </div>
            </div>
        </div>

        {{-- Total Animals --}}
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider font-semibold">Total Animals</p>
                    <p class="text-3xl font-display font-bold text-content-primary mt-1">{{ number_format($totalAnimals) }}</p>
                    <p class="text-xs text-content-muted mt-1">across all batches</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.15);">
                    🐾
                </div>
            </div>
        </div>

        {{-- Total Capital --}}
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider font-semibold">Total Invested</p>
                    <p class="text-2xl font-display font-bold text-content-primary mt-1">₦{{ number_format($totalInvested, 0) }}</p>
                    <p class="text-xs text-content-muted mt-1">purchase + expenses</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(245,166,35,0.1); border: 1px solid rgba(245,166,35,0.15);">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider font-semibold">Alerts</p>
                    <div class="mt-2 space-y-1">
                        @if($pendingExpenses > 0)
                        <a href="{{ route('admin.finance.expenses.index') }}" class="flex items-center gap-1.5 text-yellow-400 text-xs font-semibold hover:underline">
                            <span class="w-5 h-5 rounded-full bg-yellow-400/20 flex items-center justify-center text-[10px] font-bold">{{ $pendingExpenses }}</span>
                            Pending Approvals
                        </a>
                        @endif
                        @if($lowStockCount > 0)
                        <a href="{{ route('admin.farm-supplies.index') }}" class="flex items-center gap-1.5 text-red-400 text-xs font-semibold hover:underline">
                            <span class="w-5 h-5 rounded-full bg-red-400/20 flex items-center justify-center text-[10px] font-bold">{{ $lowStockCount }}</span>
                            Low Stock Items
                        </a>
                        @endif
                        @if($pendingExpenses === 0 && $lowStockCount === 0)
                        <p class="text-brand-green text-xs font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            All clear
                        </p>
                        @endif
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.15);">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Financial Snapshot Row ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.2);">
                <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <div>
                <p class="text-xs text-content-muted uppercase tracking-wider">Wallet Balance</p>
                <p class="text-xl font-display font-bold text-brand-green">₦{{ number_format($walletBalance, 2) }}</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
            <div>
                <p class="text-xs text-content-muted uppercase tracking-wider">Total Batch Expenses</p>
                <p class="text-xl font-display font-bold text-red-400">₦{{ number_format($totalBatchExp, 2) }}</p>
            </div>
        </div>

        <div class="card p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2);">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <p class="text-xs text-content-muted uppercase tracking-wider">Total Capital Deployed</p>
                <p class="text-xl font-display font-bold text-blue-400">₦{{ number_format($totalInvested, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- ── Batch Cards Grid ── --}}
    @if($batches->isEmpty())
    <div class="card p-12 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl"
             style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.12);">
            🐾
        </div>
        <h3 class="text-lg font-semibold text-content-primary">No livestock batches yet</h3>
        <p class="text-sm text-content-muted mt-1 mb-6">Start tracking your farm's livestock by creating the first batch.</p>
        <a href="{{ route('admin.livestock.create') }}" class="btn-primary inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create First Batch
        </a>
    </div>
    @else
    <div>
        <h3 class="text-sm font-bold text-content-muted uppercase tracking-wider mb-3">Livestock Batches</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($batches as $batch)
            @php
                $survivalRate = $batch->quantity_purchased > 0
                    ? round(($batch->quantity_current / $batch->quantity_purchased) * 100, 1)
                    : 100;
            @endphp
            <div class="card p-5 hover:border-brand-green/20 transition-all duration-200"
                 style="border: 1px solid rgba(255,255,255,0.06);">
                {{-- Card Header --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                             style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                            {{ $batch->animal_type_icon }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-content-primary leading-tight">{{ $batch->batch_name }}</h4>
                            <p class="text-xs text-content-muted mt-0.5">{{ $batch->animal_type_label }} · Started {{ $batch->purchase_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="badge text-xs px-2 py-1 rounded-lg border {{ $batch->status_color }} flex-shrink-0">
                        {{ $batch->status_label }}
                    </span>
                </div>

                {{-- Animal Survival Progress Bar --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-content-muted">Survival Rate</span>
                        <span class="text-xs font-bold {{ $survivalRate >= 90 ? 'text-brand-green' : ($survivalRate >= 70 ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ $survivalRate }}%
                        </span>
                    </div>
                    <div class="w-full bg-white/5 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-500 {{ $survivalRate >= 90 ? 'bg-brand-green' : ($survivalRate >= 70 ? 'bg-yellow-400' : 'bg-red-400') }}"
                             style="width: {{ $survivalRate }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-[11px] text-content-muted">{{ $batch->quantity_current }}/{{ $batch->quantity_purchased }} animals</span>
                        @if($batch->mortality_count > 0)
                        <span class="text-[11px] text-red-400">{{ $batch->mortality_count }} lost</span>
                        @endif
                    </div>
                </div>

                {{-- Cost Breakdown Table --}}
                <div class="rounded-xl overflow-hidden mb-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <table class="w-full text-xs">
                        <tbody>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2 text-content-muted">Purchase Cost</td>
                                <td class="px-3 py-2 text-right font-mono text-content-secondary">{{ $batch->formatted_purchase_cost }}</td>
                            </tr>
                            @if($batch->feed_cost > 0)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2 text-content-muted">Feed</td>
                                <td class="px-3 py-2 text-right font-mono text-content-secondary">₦{{ number_format($batch->feed_cost, 2) }}</td>
                            </tr>
                            @endif
                            @if($batch->treatment_cost > 0)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2 text-content-muted">Treatment/Vaccination</td>
                                <td class="px-3 py-2 text-right font-mono text-content-secondary">₦{{ number_format($batch->treatment_cost, 2) }}</td>
                            </tr>
                            @endif
                            @if($batch->labor_cost > 0)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2 text-content-muted">Labor</td>
                                <td class="px-3 py-2 text-right font-mono text-content-secondary">₦{{ number_format($batch->labor_cost, 2) }}</td>
                            </tr>
                            @endif
                            @if($batch->transport_cost > 0)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2 text-content-muted">Transport</td>
                                <td class="px-3 py-2 text-right font-mono text-content-secondary">₦{{ number_format($batch->transport_cost, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr style="background: rgba(184,243,151,0.05);">
                                <td class="px-3 py-2.5 font-bold text-brand-green">Total Cost</td>
                                <td class="px-3 py-2.5 text-right font-bold font-mono text-brand-green">{{ $batch->formatted_total_cost }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-content-muted">Cost per animal</p>
                        <p class="text-sm font-bold text-content-primary">{{ $batch->formatted_cost_per_animal }}</p>
                    </div>
                    <a href="{{ route('admin.livestock.show', $batch) }}" class="btn-outline text-xs py-2">
                        View Details
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
