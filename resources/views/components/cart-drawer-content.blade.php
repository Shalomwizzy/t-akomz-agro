@php
    $cartItems = [];
    $cartTotal = 0;
    $cart = session('cart', []);

    if (!empty($cart)) {
        $products = \App\Models\Product::with('images')
                        ->whereIn('id', array_keys($cart))
                        ->get()
                        ->keyBy('id');
        foreach ($cart as $productId => $qty) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                $cartItems[] = [
                    'product'  => $product,
                    'quantity' => $qty,
                    'subtotal' => $product->price * $qty,
                ];
                $cartTotal += $product->price * $qty;
            }
        }
    }
@endphp

@if(empty($cartItems))
    <div class="flex flex-col items-center justify-center h-full py-16 text-center">
        <div class="w-20 h-20 bg-surface-elevated rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-content-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <p class="text-content-secondary font-medium mb-1">Your cart is empty</p>
        <p class="text-content-muted text-sm mb-6">Find something fresh from the farm!</p>
        <a href="{{ route('shop.index') }}" @click="cartOpen = false" class="btn-primary">Browse Products</a>
    </div>
@else
    <div class="space-y-4 mb-6">
        @foreach($cartItems as $item)
        <div class="flex gap-3">
            <img src="{{ $item['product']->primary_image_url }}"
                 alt="{{ $item['product']->name }}"
                 class="w-16 h-16 rounded-lg object-cover flex-shrink-0 bg-surface-elevated">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-content-primary line-clamp-2">{{ $item['product']->name }}</p>
                <p class="text-xs text-content-muted">{{ $item['product']->unit }}</p>
                <div class="flex items-center justify-between mt-1.5">
                    <div class="flex items-center gap-2">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                            <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                            <button type="submit" class="w-6 h-6 flex items-center justify-center rounded bg-surface-elevated text-content-secondary hover:text-brand-green text-sm">−</button>
                        </form>
                        <span class="text-sm font-medium w-5 text-center">{{ $item['quantity'] }}</span>
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" class="w-6 h-6 flex items-center justify-center rounded bg-surface-elevated text-content-secondary hover:text-brand-green text-sm">+</button>
                        </form>
                    </div>
                    <span class="text-sm font-semibold text-brand-green">₦{{ number_format($item['subtotal'], 2) }}</span>
                </div>
            </div>
            <form action="{{ route('cart.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                <button type="submit" class="p-1 text-content-muted hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <div class="border-t border-surface-border pt-4 space-y-3">
        <div class="flex items-center justify-between text-sm">
            <span class="text-content-muted">Subtotal</span>
            <span class="font-semibold text-content-primary">₦{{ number_format($cartTotal, 2) }}</span>
        </div>
        <p class="text-xs text-content-muted">Delivery calculated at checkout</p>
        <div class="grid grid-cols-2 gap-3 pt-2">
            <a href="{{ route('cart.index') }}" @click="cartOpen = false" class="btn-ghost py-2.5 text-sm text-center">View Cart</a>
            <a href="{{ route('checkout.index') }}" @click="cartOpen = false" class="btn-primary py-2.5 text-sm text-center">Checkout</a>
        </div>
    </div>
@endif
