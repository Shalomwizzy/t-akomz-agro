@extends('layouts.app')

@section('title', 'My Orders - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <h1 class="font-display text-3xl font-bold text-content-primary">My Orders</h1>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0">
            @if($orders->count())
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="card p-5">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="font-mono font-bold text-brand-green">{{ $order->order_number }}</p>
                            <p class="text-xs text-content-muted mt-0.5">Placed {{ $order->created_at->format('d M Y, g:i A') }}</p>
                        </div>
                        <span class="status-{{ $order->status }}">{{ $order->status_label }}</span>
                    </div>

                    {{-- Items Preview --}}
                    <div class="flex gap-2 mb-4 overflow-x-auto pb-1 scrollbar-hide">
                        @foreach($order->items->take(4) as $item)
                        <div class="flex-shrink-0 text-center">
                            <div class="w-14 h-14 bg-surface-elevated rounded-xl flex items-center justify-center text-xs text-content-muted font-medium">
                                x{{ $item->quantity }}
                            </div>
                            <p class="text-xs text-content-muted mt-1 w-14 truncate">{{ $item->product_name }}</p>
                        </div>
                        @endforeach
                        @if($order->items->count() > 4)
                        <div class="flex-shrink-0 w-14 h-14 bg-surface-elevated rounded-xl flex items-center justify-center text-xs text-content-muted">
                            +{{ $order->items->count() - 4 }}
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between border-t border-surface-border pt-4">
                        <div class="text-sm">
                            <span class="text-content-muted">Total:</span>
                            <span class="text-brand-green font-bold ml-1">{{ $order->formatted_total }}</span>
                        </div>
                        <div class="flex gap-2">
                            @if($order->is_cancellable)
                            <form action="{{ route('account.orders.cancel', $order) }}" method="POST"
                                  onsubmit="return confirm('Cancel this order?')">
                                @csrf
                                <button type="submit" class="text-xs text-red-400 hover:underline">Cancel</button>
                            </form>
                            @endif
                            <a href="{{ route('account.orders.show', $order) }}" class="btn-outline text-xs py-1.5 px-4">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $orders->links() }}</div>

            @else
            <div class="text-center py-24">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="font-display text-xl font-semibold text-content-primary mb-2">No orders yet</h3>
                <p class="text-content-muted mb-6">When you place an order, it'll show up here.</p>
                <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3">Start Shopping</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
