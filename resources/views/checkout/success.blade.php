@extends('layouts.app')

@section('title', 'Order Confirmed - T-Akomz Agro Estates')

@section('content')
<div class="container-custom py-16">
    <div class="max-w-lg mx-auto text-center">

        {{-- Animated Checkmark --}}
        <div class="relative w-28 h-28 mx-auto mb-8">
            <div class="absolute inset-0 rounded-full bg-brand-green/10 animate-ping"></div>
            <div class="relative w-28 h-28 rounded-full bg-brand-green/20 flex items-center justify-center">
                <svg class="w-14 h-14 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"
                          style="stroke-dasharray: 30; stroke-dashoffset: 30; animation: drawCheck 0.6s ease forwards 0.3s;">
                    </path>
                </svg>
            </div>
        </div>

        <style>
            @keyframes drawCheck { to { stroke-dashoffset: 0; } }
        </style>

        <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary mb-3">Order Confirmed!</h1>
        <p class="text-content-muted mb-2">Thank you for shopping with T-Akomz Agro Estates.</p>

        @if($order)
        <p class="text-content-secondary mb-8">
            Your order number is
            <span class="text-brand-green font-bold font-mono text-lg">{{ $order->order_number }}</span>
        </p>

        {{-- Order Details Card --}}
        <div class="card p-6 text-left mb-6">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-content-muted">Order Total</span>
                    <span class="text-content-primary font-bold">{{ $order->formatted_total }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-content-muted">Payment</span>
                    <span class="text-content-primary capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-content-muted">Delivery Type</span>
                    <span class="text-content-primary capitalize">{{ str_replace('_', ' ', $order->delivery_type) }}</span>
                </div>
                @if($order->delivery_type !== 'pickup')
                <div class="flex justify-between">
                    <span class="text-content-muted">Delivery Address</span>
                    <span class="text-content-primary text-right max-w-48">{{ $order->delivery_address }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-content-muted">Estimated Delivery</span>
                    <span class="text-content-primary">
                        @if($order->delivery_type === 'express')
                        1–2 Business Days
                        @elseif($order->delivery_type === 'pickup')
                        Ready for Pickup
                        @else
                        2–5 Business Days
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Items Summary --}}
        <div class="card p-5 text-left mb-8">
            <h3 class="text-sm font-semibold text-content-primary mb-3">Items Ordered</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-elevated rounded-lg flex items-center justify-center text-xs text-content-muted font-bold flex-shrink-0">
                        x{{ $item->quantity }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-content-primary">{{ $item->product_name }}</p>
                        <p class="text-xs text-content-muted">{{ $item->unit }}</p>
                    </div>
                    <span class="text-sm text-content-primary">₦{{ number_format($item->line_total, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-content-secondary mb-8">Your order has been placed. Check your email for confirmation details.</p>
        @endif

        {{-- What's Next --}}
        <div class="card p-5 text-left mb-8 bg-brand-green/5 border-brand-green/20">
            <h3 class="font-semibold text-content-primary text-sm mb-3">What happens next?</h3>
            <ol class="space-y-2">
                @foreach([
                    'You\'ll receive an email confirmation shortly.',
                    'Our team will process and pack your order.',
                    'Your items will be dispatched and you\'ll get a tracking update.',
                    'Fresh produce delivered to your door!',
                ] as $i => $step)
                <li class="flex items-start gap-3 text-sm text-content-secondary">
                    <span class="w-5 h-5 rounded-full bg-brand-green/20 text-brand-green text-xs flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    {{ $step }}
                </li>
                @endforeach
            </ol>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            @if($order)
            @auth
            <a href="{{ route('account.orders.show', $order) }}" class="btn-primary flex-1 py-3">
                Track Your Order
            </a>
            @else
            <a href="{{ route('orders.track') }}?order={{ $order->order_number }}" class="btn-primary flex-1 py-3">
                Track Your Order
            </a>
            @endauth
            @else
            <a href="{{ route('orders.track') }}" class="btn-primary flex-1 py-3">
                Track Your Order
            </a>
            @endif
            <a href="{{ route('shop.index') }}" class="btn-outline flex-1 py-3">
                Continue Shopping
            </a>
        </div>

        <p class="text-xs text-content-muted mt-6">
            Need help?
            <a href="https://wa.me/{{ \App\Models\SiteSetting::get('whatsapp_number') }}" class="text-brand-green hover:underline">Chat on WhatsApp</a>
            or email us at
            <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}" class="text-brand-green hover:underline">{{ \App\Models\SiteSetting::get('contact_email') }}</a>
        </p>
    </div>
</div>
@endsection
