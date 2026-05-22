@extends('layouts.admin')

@section('page-title', 'Livestock Batches')
@section('breadcrumb', 'Farm ERP · Livestock')

@section('content')
<div class="space-y-6" x-data="{ filter: 'all' }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Livestock Batches</h2>
            <p class="text-sm text-content-muted mt-0.5">Track all livestock groups, costs and performance</p>
        </div>
        <a href="{{ route('admin.livestock.create') }}" class="btn-primary text-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Batch
        </a>
    </div>

    {{-- Summary KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-content-primary">{{ $totalBatches }}</p>
            <p class="text-xs text-content-muted mt-0.5">Total Batches</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-brand-green">{{ $activeBatches }}</p>
            <p class="text-xs text-content-muted mt-0.5">Active</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-content-primary">{{ number_format($totalAnimals) }}</p>
            <p class="text-xs text-content-muted mt-0.5">Total Animals</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xl font-display font-bold text-yellow-400">₦{{ number_format($totalInvested, 0) }}</p>
            <p class="text-xs text-content-muted mt-0.5">Total Invested</p>
        </div>
    </div>

    {{-- Filter Pills --}}
    <div class="flex items-center gap-2 flex-wrap">
        <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-brand-green text-surface-bg' : 'btn-outline'" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">All</button>
        <button @click="filter = 'active'" :class="filter === 'active' ? 'bg-brand-green text-surface-bg' : 'btn-outline'" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">Active</button>
        <button @click="filter = 'partially_sold'" :class="filter === 'partially_sold' ? 'bg-yellow-400 text-surface-bg' : 'btn-outline'" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">Partially Sold</button>
        <button @click="filter = 'sold'" :class="filter === 'sold' ? 'bg-blue-400 text-surface-bg' : 'btn-outline'" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">Sold</button>
        <button @click="filter = 'closed'" :class="filter === 'closed' ? 'bg-red-400 text-surface-bg' : 'btn-outline'" class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">Closed</button>
    </div>

    @if($batches->isEmpty())
    <div class="card p-12 text-center">
        <div class="text-4xl mb-3">🐾</div>
        <h3 class="text-lg font-semibold text-content-primary">No batches yet</h3>
        <p class="text-sm text-content-muted mt-1 mb-5">Create your first livestock batch to begin tracking.</p>
        <a href="{{ route('admin.livestock.create') }}" class="btn-primary inline-flex">Create First Batch</a>
    </div>
    @else
    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.02);">
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase tracking-wider">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-content-muted uppercase tracking-wider">Animal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Animals</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Purchase Cost</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Expenses</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Total Cost</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Cost/Animal</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-content-muted uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-content-muted uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($batches as $batch)
                    <tr x-show="filter === 'all' || filter === '{{ $batch->status }}'"
                        class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl">{{ $batch->animal_type_icon }}</span>
                                <div>
                                    <a href="{{ route('admin.livestock.show', $batch) }}"
                                       class="text-sm font-semibold text-content-primary hover:text-brand-green transition-colors">
                                        {{ $batch->batch_name }}
                                    </a>
                                    <p class="text-xs text-content-muted">{{ $batch->purchase_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-content-secondary">{{ $batch->animal_type_label }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-mono text-content-primary">{{ $batch->quantity_current }}</span>
                            <span class="text-xs text-content-muted">/{{ $batch->quantity_purchased }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-mono text-content-secondary">{{ $batch->formatted_purchase_cost }}</td>
                        <td class="px-4 py-3 text-right text-sm font-mono text-content-secondary">{{ $batch->formatted_total_expenses }}</td>
                        <td class="px-4 py-3 text-right text-sm font-mono font-bold text-brand-green">{{ $batch->formatted_total_cost }}</td>
                        <td class="px-4 py-3 text-right text-sm font-mono text-content-secondary">{{ $batch->formatted_cost_per_animal }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-lg border {{ $batch->status_color }}">
                                {{ $batch->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.livestock.show', $batch) }}"
                               class="text-xs text-brand-green hover:underline font-medium">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: rgba(184,243,151,0.04); border-top: 1px solid rgba(184,243,151,0.15);">
                        <td class="px-4 py-3 text-xs font-bold text-brand-green uppercase tracking-wider" colspan="3">Totals</td>
                        <td class="px-4 py-3 text-right text-sm font-bold font-mono text-content-primary">₦{{ number_format($totalPurchaseCost, 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold font-mono text-content-primary">₦{{ number_format($totalExpenses, 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold font-mono text-brand-green">₦{{ number_format($totalInvested, 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
