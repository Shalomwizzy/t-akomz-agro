<x-emails.layout subject="Your Order is On Its Way! — {{ $order->order_number }} | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">🚚</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Your Order is On Its Way!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $order->user->name }}, great news! Your T-Akomz order has been dispatched
        and our delivery team is heading to you. Please ensure someone is available to receive it.
    </p>

    {{-- Order Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Order Reference</span>
            <span class="info-value mono">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dispatched On</span>
            <span class="info-value">{{ now()->format('d M Y, g:i A') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estimated Arrival</span>
            <span class="info-value green">Within 24 – 48 Hours</span>
        </div>
        @if(!empty($order->tracking_number))
        <div class="info-row">
            <span class="info-label">Tracking Number</span>
            <span class="info-value mono">{{ $order->tracking_number }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Delivering To</span>
            <span class="info-value" style="text-align: right; max-width: 280px;">
                {{ $order->delivery_address ?? 'See account' }}
                @if(!empty($order->delivery_city)), {{ $order->delivery_city }}@endif
                @if(!empty($order->delivery_state)), {{ $order->delivery_state }}@endif
            </span>
        </div>
    </div>

    {{-- Rider alert --}}
    <div class="alert-box-green">
        <p class="alert-title" style="color: #2E7A10;">🏍 Your Rider is On the Move</p>
        <p class="alert-text">
            Your rider is heading to your delivery address right now. They will call you
            shortly before arrival. Please keep your phone nearby and ensure someone is
            available at the delivery location to receive your fresh produce.
        </p>
    </div>

    {{-- Freshness tip --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">Keep It Fresh</p>
        <p class="alert-text">
            Once your order arrives, refrigerate perishable items immediately. Our produce
            is packaged to stay fresh during transit, but proper storage on receipt ensures
            the best quality and shelf life.
        </p>
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/account/orders/{{ $order->id }}" class="cta-btn">Track Your Order</a>
    </div>

    <hr class="divider">

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        Having trouble receiving your delivery? Reply to this email or
        <a href="{{ config('app.url') }}/contact" style="color: #3B8A1E; text-decoration: none; font-weight: 600;">contact us</a>
        and we'll coordinate with our delivery team right away.
    </p>

</x-emails.layout>
