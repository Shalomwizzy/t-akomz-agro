@extends('layouts.admin')

@section('page-title', 'Farm Supplies Inventory')
@section('breadcrumb', 'Farm ERP · Supplies')

@section('content')
<div class="space-y-6" x-data="{ catFilter: 'all' }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-content-primary">Farm Supplies</h2>
            <p class="text-sm text-content-muted mt-0.5">Track feed, medication, equipment and all farm inputs</p>
        </div>
        <a href="{{ route('admin.farm-supplies.create') }}" class="btn-primary text-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Supply Item
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-content-primary">{{ $totalItems }}</p>
            <p class="text-xs text-content-muted mt-0.5">Total Items</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-yellow-400">{{ $lowStockCount }}</p>
            <p class="text-xs text-content-muted mt-0.5">Low Stock</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-display font-bold text-red-400">{{ $outOfStockCount }}</p>
            <p class="text-xs text-content-muted mt-0.5">Out of Stock</p>
        </div>
    </div>

    {{-- Alert Banner --}}
    @if($lowStockCount > 0 || $outOfStockCount > 0)
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
         style="background: rgba(245,166,35,0.08); border: 1px solid rgba(245,166,35,0.2); color: rgb(245,166,35);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>
            @if($outOfStockCount > 0)<strong>{{ $outOfStockCount }} item(s) are completely out of stock</strong> — restock immediately.@endif
            @if($lowStockCount > 0) {{ $outOfStockCount > 0 ? 'Also, ' : '' }}<strong>{{ $lowStockCount }} item(s) are running low</strong> — consider restocking soon.@endif
        </span>
    </div>
    @endif

    {{-- Category Filter Pills --}}
    <div class="flex items-center gap-2 flex-wrap">
        @php
        $categories = ['all' => 'All', 'feed' => '🌾 Feed', 'medication' => '💊 Medication', 'vaccination' => '💉 Vaccination', 'equipment' => '🔧 Equipment', 'seeds' => '🌱 Seeds', 'chemicals' => '🧪 Chemicals', 'fuel' => '⛽ Fuel', 'other' => '📦 Other'];
        @endphp
        @foreach($categories as $val => $label)
        <button @click="catFilter = '{{ $val }}'"
                :class="catFilter === '{{ $val }}' ? 'bg-brand-green text-surface-bg' : 'btn-outline'"
                class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-all">
            {{ $label }}
        </button>
        @endforeach
    </div>

    @if($supplies->isEmpty())
    <div class="card p-12 text-center">
        <div class="text-4xl mb-3">📦</div>
        <h3 class="text-lg font-semibold text-content-primary">No supply items yet</h3>
        <p class="text-sm text-content-muted mt-1 mb-5">Add your first farm supply item to begin tracking inventory.</p>
        <a href="{{ route('admin.farm-supplies.create') }}" class="btn-primary inline-flex">Add First Supply</a>
    </div>
    @else
    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($supplies as $supply)
        <div x-show="catFilter === 'all' || catFilter === '{{ $supply->category }}'"
             class="card p-5 hover:border-brand-green/20 transition-all duration-200 flex flex-col"
             style="border: 1px solid rgba(255,255,255,0.06);">
            {{-- Card Header --}}
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">{{ $supply->category_icon }}</span>
                    <div>
                        <h4 class="font-semibold text-content-primary text-sm leading-tight">{{ $supply->name }}</h4>
                        <p class="text-xs text-content-muted mt-0.5">{{ $supply->category_label }}</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-md border {{ $supply->status_color }} flex-shrink-0">
                    {{ $supply->status_label }}
                </span>
            </div>

            {{-- Stock Display --}}
            <div class="flex-1 py-3 px-4 rounded-xl text-center mb-3"
                 style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                <p class="text-3xl font-display font-bold {{ $supply->stock_quantity <= 0 ? 'text-red-400' : ($supply->stock_quantity <= $supply->low_stock_threshold ? 'text-yellow-400' : 'text-brand-green') }}">
                    {{ number_format($supply->stock_quantity, 2) }}
                </p>
                <p class="text-xs text-content-muted mt-0.5">{{ $supply->unit }}</p>
            </div>

            {{-- Threshold note --}}
            <p class="text-xs text-content-muted mb-4 text-center">
                Low stock alert below {{ number_format($supply->low_stock_threshold, 2) }} {{ $supply->unit }}
            </p>

            {{-- Actions --}}
            <div class="flex items-center gap-2 mt-auto">
                <a href="{{ route('admin.farm-supplies.show', $supply) }}"
                   class="btn-primary text-xs py-1.5 flex-1 justify-center">
                    Manage
                </a>
                <a href="{{ route('admin.farm-supplies.show', $supply) }}#history"
                   class="btn-outline text-xs py-1.5">History</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
