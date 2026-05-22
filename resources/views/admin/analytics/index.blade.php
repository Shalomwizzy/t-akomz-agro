@extends('layouts.admin')

@section('page-title', 'Analytics')

@section('content')

{{-- Period Filter --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.analytics.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
        <label class="text-sm text-content-muted">Period:</label>
        @foreach([['7','Last 7 days'],['30','Last 30 days'],['90','Last 90 days'],['365','Last year']] as [$d, $label])
        <button type="submit" name="days" value="{{ $d }}"
                class="text-sm px-4 py-2 rounded-lg transition-colors
                    {{ request('days', '30') === $d ? 'bg-brand-green text-surface-bg font-semibold' : 'bg-surface-elevated text-content-muted hover:text-brand-green' }}">
            {{ $label }}
        </button>
        @endforeach
    </form>
</div>

{{-- KPI Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['Revenue', '₦' . number_format($revenue, 0), '💰'],
        ['Orders', $ordersCount, '📦'],
        ['Avg Order Value', '₦' . number_format($avgOrder, 0), '📊'],
        ['New Customers', $newCustomers, '👥'],
    ] as [$label, $val, $icon])
    <div class="card p-5">
        <div class="text-2xl mb-2">{{ $icon }}</div>
        <p class="font-display text-2xl font-bold text-content-primary">{{ $val }}</p>
        <p class="text-xs text-content-muted mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

    {{-- Daily Revenue Chart --}}
    <div class="card p-5">
        <h2 class="font-semibold text-content-primary mb-5">Daily Revenue</h2>
        @php $maxRev = $dailyRevenue->max() ?: 1; @endphp
        <div class="relative h-40 flex items-end gap-0.5">
            @foreach($dailyRevenue as $date => $rev)
            @php $h = ($rev / $maxRev) * 100; @endphp
            <div class="flex-1 bg-brand-green/60 hover:bg-brand-green rounded-t-sm transition-colors cursor-default group relative"
                 style="height: {{ max(2, $h) }}%">
                <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-surface-card border border-surface-border rounded px-1 py-0.5 text-xs text-content-muted whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none z-10">
                    {{ $date }}: ₦{{ number_format($rev, 0) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Revenue by Category --}}
    <div class="card p-5">
        <h2 class="font-semibold text-content-primary mb-5">Revenue by Category</h2>
        <div class="space-y-3">
            @foreach($byCategory as $cat)
            @php
                $maxCat = $byCategory->max('revenue') ?: 1;
                $pct = round(($cat->revenue / $maxCat) * 100);
            @endphp
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-content-secondary">{{ $cat->name }}</span>
                    <span class="text-content-muted text-xs">₦{{ number_format($cat->revenue, 0) }}</span>
                </div>
                <div class="h-2 bg-surface-elevated rounded-full overflow-hidden">
                    <div class="h-full bg-brand-green/70 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Products --}}
<div class="card overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-surface-border">
        <h2 class="font-semibold text-content-primary">Top Selling Products ({{ $days }} days)</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">#</th>
                    <th class="table-header text-left">Product</th>
                    <th class="table-header text-left">Units Sold</th>
                    <th class="table-header text-left">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $product)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell font-bold text-content-muted text-sm">{{ $i + 1 }}</td>
                    <td class="table-cell text-sm text-content-primary font-medium">{{ $product->name }}</td>
                    <td class="table-cell text-sm text-content-secondary">{{ number_format($product->units) }}</td>
                    <td class="table-cell text-sm text-brand-green font-semibold">₦{{ number_format($product->revenue, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Payment + Delivery Breakdown --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card p-5">
        <h3 class="font-semibold text-content-primary mb-4">Payment Methods</h3>
        <div class="space-y-2">
            @foreach($byPayment as $method => $count)
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $method) }}</span>
                <span class="text-content-primary font-semibold">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="card p-5">
        <h3 class="font-semibold text-content-primary mb-4">Delivery Types</h3>
        <div class="space-y-2">
            @foreach($byDelivery as $type => $count)
            <div class="flex justify-between text-sm">
                <span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $type) }}</span>
                <span class="text-content-primary font-semibold">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
