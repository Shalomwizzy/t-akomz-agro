@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')

{{-- Filters --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Order #, name, phone, email..."
               class="input py-2 text-sm flex-1 min-w-48">

        <select name="status" class="input py-2 text-sm w-auto">
            <option value="">All Statuses</option>
            @foreach(['PENDING','CONFIRMED','PROCESSING','PACKED','DISPATCHED','OUT_FOR_DELIVERY','SHIPPED','DELIVERED','CANCELLED','REFUNDED'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ str_replace('_', ' ', $s) }}</option>
            @endforeach
        </select>

        <select name="payment" class="input py-2 text-sm w-auto">
            <option value="">All Payments</option>
            @foreach(['PENDING','PAID','FAILED','REFUNDED'] as $p)
            <option value="{{ $p }}" {{ request('payment') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}" class="input py-2 text-sm w-auto" placeholder="From">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="input py-2 text-sm w-auto" placeholder="To">

        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
        <a href="{{ route('admin.orders.export') }}?{{ request()->getQueryString() }}" class="btn-outline py-2 px-4 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export xlsx
        </a>
    </form>
</div>

{{-- Summary --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-content-muted">{{ $orders->total() }} orders found</p>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Order #</th>
                    <th class="table-header text-left">Customer</th>
                    <th class="table-header text-left">Items</th>
                    <th class="table-header text-left">Total</th>
                    <th class="table-header text-left">Payment</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Date</th>
                    <th class="table-header text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-brand-green hover:underline text-xs">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="table-cell">
                        <p class="text-content-primary text-xs font-medium">{{ $order->customer_name }}</p>
                        <p class="text-content-muted text-xs">{{ $order->customer_phone }}</p>
                    </td>
                    <td class="table-cell">
                        <span class="text-content-muted text-xs">{{ $order->items_count ?? $order->items->count() }} item(s)</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-content-primary font-semibold text-xs">{{ $order->formatted_total }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-xs {{ $order->payment_status === 'PAID' ? 'text-brand-green' : ($order->payment_status === 'FAILED' ? 'text-red-400' : 'text-yellow-400') }}">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="table-cell">
                        <span class="status-{{ $order->status }} text-xs">{{ $order->status_label }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="text-content-muted text-xs">{{ $order->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="table-cell">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-brand-green hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-content-muted text-sm">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $orders->withQueryString()->links() }}</div>
@endsection
