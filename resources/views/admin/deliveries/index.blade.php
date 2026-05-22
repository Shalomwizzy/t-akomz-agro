@extends('layouts.admin')

@section('page-title', 'Deliveries')

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Pending', $stats['pending'], 'text-yellow-400'],
        ['Out for Delivery', $stats['out_for_delivery'], 'text-orange-400'],
        ['Delivered Today', $stats['delivered_today'], 'text-brand-green'],
        ['Total Active', $stats['active'], 'text-blue-400'],
    ] as [$label, $count, $color])
    <div class="card p-4 text-center">
        <p class="font-display text-2xl font-bold {{ $color }}">{{ $count }}</p>
        <p class="text-xs text-content-muted mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.deliveries.index') }}" method="GET" class="flex flex-wrap gap-3">
        <select name="delivery_type" class="input py-2 text-sm w-auto">
            <option value="">All Types</option>
            <option value="standard" {{ request('delivery_type') === 'standard' ? 'selected' : '' }}>Standard</option>
            <option value="express" {{ request('delivery_type') === 'express' ? 'selected' : '' }}>Express</option>
            <option value="pickup" {{ request('delivery_type') === 'pickup' ? 'selected' : '' }}>Pickup</option>
        </select>
        <select name="status" class="input py-2 text-sm w-auto">
            <option value="">All Status</option>
            <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
            <option value="PROCESSING" {{ request('status') === 'PROCESSING' ? 'selected' : '' }}>Processing</option>
            <option value="DISPATCHED" {{ request('status') === 'DISPATCHED' ? 'selected' : '' }}>Dispatched</option>
            <option value="OUT_FOR_DELIVERY" {{ request('status') === 'OUT_FOR_DELIVERY' ? 'selected' : '' }}>Out for Delivery</option>
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        <a href="{{ route('admin.deliveries.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
    </form>
</div>

{{-- Active Deliveries --}}
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border">
        <h2 class="font-semibold text-content-primary">Active Deliveries</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Order #</th>
                    <th class="table-header text-left">Customer</th>
                    <th class="table-header text-left">Address</th>
                    <th class="table-header text-left">Type</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Date</th>
                    <th class="table-header text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-xs text-brand-green hover:underline">{{ $order->order_number }}</a>
                    </td>
                    <td class="table-cell">
                        <p class="text-xs text-content-primary font-medium">{{ $order->customer_name }}</p>
                        <p class="text-xs text-content-muted">{{ $order->customer_phone }}</p>
                    </td>
                    <td class="table-cell">
                        <p class="text-xs text-content-secondary max-w-40 truncate">{{ $order->delivery_address ?? 'Farm Pickup' }}</p>
                    </td>
                    <td class="table-cell">
                        <span class="text-xs text-content-muted capitalize">{{ str_replace('_', ' ', $order->delivery_type) }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="status-{{ $order->status }} text-xs">{{ $order->status_label }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-xs text-content-muted">{{ $order->created_at->format('d M') }}</span>
                    </td>
                    <td class="table-cell">
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-1">
                            @csrf
                            <select name="status" class="input py-1 px-2 text-xs w-auto">
                                <option value="DISPATCHED" {{ $order->status === 'DISPATCHED' ? 'selected' : '' }}>Dispatched</option>
                                <option value="OUT_FOR_DELIVERY" {{ $order->status === 'OUT_FOR_DELIVERY' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="DELIVERED" {{ $order->status === 'DELIVERED' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="btn-primary py-1 px-2 text-xs">↑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-12 text-center text-content-muted text-sm">No active deliveries.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $orders->withQueryString()->links() }}</div>
@endsection
