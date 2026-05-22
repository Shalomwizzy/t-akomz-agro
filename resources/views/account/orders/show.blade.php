@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-3">
            <a href="{{ route('account.dashboard') }}" class="hover:text-brand-green transition-colors">Account</a>
            <span>/</span>
            <a href="{{ route('account.orders') }}" class="hover:text-brand-green transition-colors">Orders</a>
            <span>/</span>
            <span class="text-content-primary font-mono">{{ $order->order_number }}</span>
        </nav>
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-bold text-content-primary">Order Details</h1>
            <span class="status-{{ $order->status }} text-sm">{{ $order->status_label }}</span>
        </div>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0 space-y-5">

            {{-- Order Progress --}}
            @php
                $steps = ['PENDING' => '📋', 'CONFIRMED' => '✅', 'PROCESSING' => '📦', 'SHIPPED' => '🚚', 'DELIVERED' => '🏠'];
                $statuses = array_keys($steps);
                $currentIdx = array_search($order->status, $statuses) !== false ? array_search($order->status, $statuses) : 0;
            @endphp
            @if(!in_array($order->status, ['CANCELLED', 'REFUNDED']))
            <div class="card p-5">
                <div class="flex items-center justify-between relative">
                    <div class="absolute top-5 left-5 right-5 h-0.5 bg-surface-border -z-0"></div>
                    <div class="absolute top-5 left-5 h-0.5 bg-brand-green -z-0 transition-all"
                         style="width: {{ $currentIdx > 0 ? min(100, ($currentIdx / (count($steps) - 1)) * 100) : 0 }}%"></div>
                    @foreach($steps as $key => [$icon])
                    @php $idx = array_search($key, $statuses); @endphp
                    <div class="flex flex-col items-center z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base transition-all
                            {{ $idx <= $currentIdx ? 'bg-brand-green/20 ring-2 ring-brand-green' : 'bg-surface-elevated ring-1 ring-surface-border' }}">
                            {{ $icon }}
                        </div>
                        <span class="text-xs mt-2 {{ $idx <= $currentIdx ? 'text-brand-green font-medium' : 'text-content-muted' }}">{{ $key }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Items --}}
            <div class="card p-5">
                <h2 class="font-semibold text-content-primary mb-4">Items Ordered</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-surface-elevated rounded-xl flex items-center justify-center text-sm text-content-muted font-bold flex-shrink-0">
                            x{{ $item->quantity }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-content-primary">{{ $item->product_name }}</p>
                            <p class="text-xs text-content-muted">{{ $item->unit }} · ₦{{ number_format($item->unit_price, 2) }} each</p>
                        </div>
                        <span class="text-sm font-semibold text-content-primary">₦{{ number_format($item->line_total, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-surface-border mt-4 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-content-muted">Subtotal</span><span class="text-content-primary">₦{{ number_format($order->subtotal, 2) }}</span></div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-brand-green"><span>Discount</span><span>-₦{{ number_format($order->discount_amount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-content-muted">Delivery</span><span class="text-content-primary">₦{{ number_format($order->delivery_fee, 2) }}</span></div>
                    <div class="flex justify-between font-bold border-t border-surface-border pt-2">
                        <span class="text-content-primary">Total</span>
                        <span class="text-brand-green text-base">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            {{-- Delivery + Payment --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary text-sm mb-3">Delivery</h3>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex gap-3"><span class="text-content-muted w-20">Type</span><span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $order->delivery_type) }}</span></div>
                        @if($order->delivery_address)
                        <div class="flex gap-3"><span class="text-content-muted w-20">Address</span><span class="text-content-secondary">{{ $order->delivery_address }}</span></div>
                        @endif
                    </div>
                </div>
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary text-sm mb-3">Payment</h3>
                    <div class="space-y-1.5 text-sm">
                        <div class="flex gap-3"><span class="text-content-muted w-20">Method</span><span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span></div>
                        <div class="flex gap-3"><span class="text-content-muted w-20">Status</span>
                            <span class="{{ $order->payment_status === 'PAID' ? 'text-brand-green' : 'text-yellow-400' }} capitalize font-medium">{{ $order->payment_status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            @if($order->timelines && $order->timelines->count())
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    @foreach($order->timelines->sortByDesc('created_at') as $timeline)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-brand-green mt-1 flex-shrink-0"></div>
                            @if(!$loop->last)<div class="w-px flex-1 bg-surface-border mt-1"></div>@endif
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-medium text-content-primary capitalize">{{ str_replace('_', ' ', $timeline->status) }}</p>
                            @if($timeline->note)<p class="text-xs text-content-muted mt-0.5">{{ $timeline->note }}</p>@endif
                            <p class="text-xs text-content-muted mt-1">{{ $timeline->created_at->format('d M Y, g:i A') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-wrap gap-3">
                @if($order->payment_status === 'PAID')
                <a href="{{ route('account.orders.invoice', $order) }}"
                   class="btn-outline py-2.5 px-5 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Invoice
                </a>
                @endif
                @if($order->is_cancellable)
                <form action="{{ route('account.orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    <button type="submit" class="btn-danger py-2.5 px-5 text-sm">Cancel Order</button>
                </form>
                @endif
                <a href="{{ route('account.orders') }}" class="btn-ghost py-2.5 px-5 text-sm">← Back to Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
