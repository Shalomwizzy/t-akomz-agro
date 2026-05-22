@extends('layouts.admin')

@section('page-title', $customer->name)

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.customers.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Profile Card --}}
    <div class="space-y-5">
        <div class="card p-6 text-center">
            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                 class="w-20 h-20 rounded-full mx-auto mb-4 object-cover">
            <h2 class="font-display font-bold text-content-primary">{{ $customer->name }}</h2>
            <p class="text-content-muted text-sm">{{ $customer->email }}</p>
            @if($customer->phone)<p class="text-content-muted text-sm">{{ $customer->phone }}</p>@endif
            <p class="text-xs text-content-muted mt-2">Joined {{ $customer->created_at->format('d M Y') }}</p>
        </div>

        {{-- Stats --}}
        <div class="card p-5">
            <h3 class="font-semibold text-content-primary text-sm mb-4">Customer Stats</h3>
            <div class="space-y-3">
                @foreach([
                    ['Total Orders', $orders->count()],
                    ['Total Spent', '₦' . number_format($orders->sum('total'), 2)],
                    ['Avg Order Value', $orders->count() ? '₦' . number_format($orders->avg('total'), 2) : '—'],
                    ['Wishlist Items', $customer->wishlistItems->count()],
                ] as [$label, $val])
                <div class="flex justify-between text-sm">
                    <span class="text-content-muted">{{ $label }}</span>
                    <span class="text-content-primary font-semibold">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Orders --}}
    <div class="xl:col-span-2">
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-border">
                <h3 class="font-semibold text-content-primary">Order History</h3>
            </div>
            <div class="divide-y divide-surface-border">
                @forelse($orders as $order)
                <div class="flex items-center justify-between px-5 py-4 hover:bg-surface-elevated/50 transition-colors">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="font-mono text-sm text-brand-green hover:underline">{{ $order->order_number }}</a>
                        <p class="text-xs text-content-muted mt-0.5">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} items</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-content-primary">{{ $order->formatted_total }}</p>
                        <span class="status-{{ $order->status }} text-xs">{{ $order->status_label }}</span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-content-muted text-sm">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
