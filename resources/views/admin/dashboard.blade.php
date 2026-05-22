@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')

{{-- Download Client Guide --}}
@if(auth()->user()->isAdmin())
<div class="mb-6 flex justify-end">
    <a href="{{ route('download.guide') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all hover:scale-105 active:scale-95"
       style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.2); color: #B8F397;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download Website Guide
    </a>
</div>
@endif

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    @php
        $kpis = [
            ['label' => 'Monthly Revenue',   'value' => '₦' . number_format($stats['monthly_revenue'], 0), 'sub' => ($stats['revenue_change'] >= 0 ? '+' : '') . $stats['revenue_change'] . '% vs last month', 'positive' => $stats['revenue_change'] >= 0, 'icon' => '₦', 'iconbg' => 'rgba(184,243,151,0.12)', 'iconborder' => 'rgba(184,243,151,0.25)', 'iconcolor' => 'rgb(184,243,151)'],
            ['label' => 'Orders This Month', 'value' => $stats['monthly_orders'],                           'sub' => 'Total all time: ' . $stats['total_orders'],                                                  'positive' => true,                              'icon' => '📦', 'iconbg' => 'rgba(59,130,246,0.12)',  'iconborder' => 'rgba(59,130,246,0.25)',  'iconcolor' => 'rgb(147,197,253)'],
            ['label' => 'New Customers',     'value' => $stats['new_customers'],                            'sub' => 'Joined this month',                                                                          'positive' => true,                              'icon' => '👥', 'iconbg' => 'rgba(167,139,250,0.12)', 'iconborder' => 'rgba(167,139,250,0.25)', 'iconcolor' => 'rgb(167,139,250)'],
            ['label' => 'Active Products',   'value' => $stats['active_products'],                          'sub' => $stats['low_stock_count'] . ' products low stock',                                           'positive' => $stats['low_stock_count'] === 0,   'icon' => '🥗', 'iconbg' => 'rgba(250,204,21,0.12)',  'iconborder' => 'rgba(250,204,21,0.25)',  'iconcolor' => 'rgb(250,204,21)'],
        ];
    @endphp
    @foreach($kpis as $kpi)
    <div class="admin-stat-card group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                 style="background: {{ $kpi['iconbg'] }}; border: 1px solid {{ $kpi['iconborder'] }};">
                {{ $kpi['icon'] }}
            </div>
            <div class="text-xs font-bold px-2 py-1 rounded-lg {{ $kpi['positive'] ? '' : 'text-red-400' }}"
                 style="{{ $kpi['positive'] ? 'background: rgba(184,243,151,0.08); color: rgb(184,243,151);' : 'background: rgba(239,68,68,0.08);' }}">
                {{ $kpi['sub'] }}
            </div>
        </div>
        <p class="text-xs font-semibold text-content-muted uppercase tracking-widest mb-2">{{ $kpi['label'] }}</p>
        <p class="font-display text-3xl font-black text-content-primary">{{ $kpi['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 card p-5">
        <h2 class="font-semibold text-content-primary mb-5">Revenue (Last 12 Months)</h2>
        <div class="relative h-48 flex items-end gap-1.5">
            @php
                $maxRevenue = $revenueChart->max('revenue') ?: 1;
            @endphp
            @foreach($revenueChart as $month)
            @php $height = ($month['revenue'] / $maxRevenue) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1 group">
                <div class="w-full bg-brand-green/80 rounded-t-sm hover:bg-brand-green transition-colors relative"
                     style="height: {{ max(4, $height) }}%"
                     title="₦{{ number_format($month['revenue'], 0) }}">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-content-muted whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                        ₦{{ number_format($month['revenue'] / 1000, 0) }}K
                    </div>
                </div>
                <span class="text-xs text-content-muted" style="font-size: 9px">{{ $month['month'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Order Status --}}
    <div class="card p-5">
        <h2 class="font-semibold text-content-primary mb-5">Order Status</h2>
        <div class="space-y-3">
            @foreach($orderStatusBreakdown as $statusRow)
            @php
                $colors = [
                    'PENDING' => 'bg-yellow-400',
                    'CONFIRMED' => 'bg-blue-400',
                    'PROCESSING' => 'bg-indigo-400',
                    'SHIPPED' => 'bg-orange-400',
                    'DELIVERED' => 'bg-brand-green',
                    'CANCELLED' => 'bg-red-400',
                ];
                $color = $colors[$statusRow['status']] ?? 'bg-surface-elevated';
                $total = $orderStatusBreakdown->sum('count') ?: 1;
                $pct = round(($statusRow['count'] / $total) * 100);
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $statusRow['status']) }}</span>
                    <span class="text-content-muted">{{ $statusRow['count'] }} ({{ $pct }}%)</span>
                </div>
                <div class="h-1.5 bg-surface-elevated rounded-full overflow-hidden">
                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Top Products --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-content-primary">Top Products</h2>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-brand-green hover:underline">View all</a>
        </div>
        <div class="space-y-4">
            @foreach($topProducts as $product)
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-surface-elevated text-content-muted text-xs flex items-center justify-center font-bold">{{ $loop->index + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-content-primary truncate">{{ $product->name }}</p>
                    <p class="text-xs text-content-muted">{{ $product->total_qty ?? 0 }} units sold</p>
                </div>
                <span class="text-sm font-semibold text-brand-green">₦{{ number_format($product->revenue ?? 0, 0) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Low Stock + New Customers --}}
    <div class="space-y-6">
        {{-- Low Stock --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-content-primary">Low Stock Alert</h2>
                <a href="{{ route('admin.inventory.index') }}" class="text-xs text-yellow-400 hover:underline">Manage</a>
            </div>
            @if($lowStockProducts->count())
            <div class="space-y-2">
                @foreach($lowStockProducts->take(5) as $product)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-content-secondary truncate max-w-48">{{ $product->name }}</span>
                    <span class="text-yellow-400 font-semibold ml-2">{{ $product->stock }} left</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-content-muted">All products are well-stocked ✓</p>
            @endif
        </div>

        {{-- Latest Customers --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-content-primary">New Customers</h2>
                <a href="{{ route('admin.customers.index') }}" class="text-xs text-brand-green hover:underline">View all</a>
            </div>
            <div class="space-y-3">
                @foreach($latestCustomers as $customer)
                <div class="flex items-center gap-3">
                    <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-content-primary truncate">{{ $customer->name }}</p>
                        <p class="text-xs text-content-muted">{{ $customer->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
@if($recentOrders->count())
<div class="mt-8 card overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
        <h2 class="font-semibold text-content-primary">Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-brand-green hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <th class="table-header text-left">Order</th>
                    <th class="table-header text-left">Customer</th>
                    <th class="table-header text-left">Amount</th>
                    <th class="table-header text-left">Payment</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Date</th>
                    <th class="table-header text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell font-mono text-brand-green text-xs">{{ $order->order_number }}</td>
                    <td class="table-cell text-content-primary text-xs">{{ $order->customer_name }}</td>
                    <td class="table-cell font-semibold text-xs">{{ $order->formatted_total }}</td>
                    <td class="table-cell">
                        <span class="text-xs {{ $order->payment_status === 'PAID' ? 'text-brand-green' : 'text-yellow-400' }}">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="table-cell">
                        @php
                            $sc = match($order->status) {
                                'DELIVERED' => 'bg-brand-green/15 text-brand-green',
                                'CANCELLED' => 'bg-red-500/15 text-red-400',
                                'PENDING'   => 'bg-yellow-500/15 text-yellow-400',
                                default     => 'bg-blue-500/15 text-blue-400',
                            };
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $sc }}">{{ $order->status }}</span>
                    </td>
                    <td class="table-cell text-content-muted text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="table-cell">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-brand-green hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
