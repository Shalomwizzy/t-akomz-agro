@extends('layouts.app')

@section('title', 'Your Cart - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-3">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
            <span>/</span>
            <span class="text-content-primary">Cart</span>
        </nav>
        <h1 class="font-display text-3xl font-bold text-content-primary">Shopping Cart</h1>
    </div>
</div>

<div class="container-custom py-8">
    @php
        $cartItems = session('cart', []);
        $products  = [];
        $subtotal  = 0;

        if (!empty($cartItems)) {
            $pids = array_keys($cartItems);
            $dbProducts = \App\Models\Product::whereIn('id', $pids)->with('images')->get()->keyBy('id');

            foreach ($cartItems as $pid => $qty) {
                if (isset($dbProducts[$pid])) {
                    $p = $dbProducts[$pid];
                    $lineTotal = $p->price * $qty;
                    $subtotal += $lineTotal;
                    $products[] = ['product' => $p, 'qty' => $qty, 'line_total' => $lineTotal];
                }
            }
        }

        $couponSession  = session('coupon');
        $couponDiscount = session('discount', 0);
        $couponCode     = $couponSession['code'] ?? '';
    @endphp

    @if(count($products))
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Cart Items --}}
        <div class="flex-1 min-w-0 space-y-4">
            @foreach($products as $item)
            @php $p = $item['product']; @endphp
            <div class="card p-5 flex gap-4">
                {{-- Image --}}
                <a href="{{ route('shop.product', [$p->category->slug, $p->slug]) }}" class="flex-shrink-0">
                    <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}"
                         class="w-20 h-20 object-cover rounded-xl">
                </a>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('shop.product', [$p->category->slug, $p->slug]) }}"
                       class="font-display font-semibold text-content-primary hover:text-brand-green transition-colors line-clamp-2">
                        {{ $p->name }}
                    </a>
                    <p class="text-xs text-content-muted mt-0.5">{{ $p->unit }}</p>
                    <p class="text-brand-green font-semibold mt-1">{{ $p->formatted_price }}</p>
                </div>

                {{-- Qty + Remove --}}
                <div class="flex flex-col items-end justify-between flex-shrink-0">
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <button type="submit" class="text-content-muted hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>

                    <div class="flex items-center gap-0 border border-surface-border rounded-lg overflow-hidden">
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" name="quantity" value="{{ max(1, $item['qty'] - 1) }}">
                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-content-muted hover:text-brand-green hover:bg-surface-elevated transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                        </form>
                        <span class="w-10 h-8 flex items-center justify-center text-sm text-content-primary border-x border-surface-border">{{ $item['qty'] }}</span>
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            <input type="hidden" name="quantity" value="{{ $item['qty'] + 1 }}">
                            <button type="submit" {{ $item['qty'] >= $p->stock ? 'disabled' : '' }}
                                    class="w-8 h-8 flex items-center justify-center text-content-muted hover:text-brand-green hover:bg-surface-elevated transition-colors disabled:opacity-40">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </form>
                    </div>

                    <span class="text-sm font-semibold text-content-primary">₦{{ number_format($item['line_total'], 2) }}</span>
                </div>
            </div>
            @endforeach

            {{-- Continue Shopping --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('shop.index') }}" class="flex items-center gap-2 text-sm text-content-muted hover:text-brand-green transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Continue Shopping
                </a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-content-muted hover:text-red-400 transition-colors">
                        Clear Cart
                    </button>
                </form>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:w-80 flex-shrink-0 space-y-4">

            {{-- Coupon --}}
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-3">Promo Code</h3>
                @if($couponCode)
                <div class="flex items-center justify-between bg-brand-green/10 border border-brand-green/30 rounded-xl px-3 py-2.5 mb-2">
                    <span class="text-brand-green text-sm font-medium">{{ $couponCode }}</span>
                    <span class="text-brand-green text-sm">-₦{{ number_format($couponDiscount, 2) }}</span>
                </div>
                <form action="{{ route('coupon.remove') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-red-400 hover:underline">Remove coupon</button>
                </form>
                @else
                <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="Enter code" class="input text-sm flex-1 py-2.5 px-3">
                    <button type="submit" class="btn-outline py-2.5 px-4 text-sm">Apply</button>
                </form>
                @if(session('error'))
                <p class="text-red-400 text-xs mt-1">{{ session('error') }}</p>
                @endif
                @endif
            </div>

            {{-- Summary --}}
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-content-muted">Subtotal</span>
                        <span class="text-content-primary">₦{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($couponDiscount > 0)
                    <div class="flex justify-between text-brand-green">
                        <span>Discount</span>
                        <span>-₦{{ number_format($couponDiscount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-content-muted">Delivery</span>
                        <span class="text-content-secondary">Calculated at checkout</span>
                    </div>
                    <div class="border-t border-surface-border pt-3 flex justify-between font-bold">
                        <span class="text-content-primary">Total</span>
                        <span class="text-brand-green text-lg">₦{{ number_format(max(0, $subtotal - $couponDiscount), 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn-primary w-full py-3.5 mt-5 text-sm">
                    Proceed to Checkout →
                </a>

                {{-- Payment Badges --}}
                <div class="flex items-center justify-center gap-3 mt-4">
                    <span class="text-xs text-content-muted">Secured by</span>
                    <span class="text-xs font-semibold text-content-muted bg-surface-elevated px-2 py-0.5 rounded">Paystack</span>
                    <span class="text-xs font-semibold text-content-muted bg-surface-elevated px-2 py-0.5 rounded">Flutterwave</span>
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- Empty Cart --}}
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="text-7xl mb-6">🛒</div>
        <h3 class="font-display text-2xl font-bold text-content-primary mb-2">Your cart is empty</h3>
        <p class="text-content-muted mb-8 max-w-sm">Looks like you haven't added any fresh produce yet. Let's change that!</p>
        <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3.5">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
