<x-emails.layout subject="Order Delivered Successfully! — {{ $order->order_number }} | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">🎉</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Order Delivered Successfully!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $order->user->name }}, your T-Akomz order has been delivered!
        Thank you for choosing us — we hope your family enjoys the fresh produce
        straight from our farm in Ido Ekiti.
    </p>

    {{-- Order summary --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Order Reference</span>
            <span class="info-value mono">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Delivered On</span>
            <span class="info-value">{{ now()->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value green">Delivered</span>
        </div>
        <div class="info-row">
            <span class="info-label">Order Total</span>
            <span class="info-value">{{ $order->formatted_total ?? '₦' . number_format($order->total, 2) }}</span>
        </div>
    </div>

    {{-- Freshness alert --}}
    <div class="alert-box-green">
        <p class="alert-title" style="color: #2E7A10;">🌾 Freshness Guaranteed</p>
        <p class="alert-text">
            Your produce was packaged to export-grade standards and delivered as fast as possible.
            Store perishables in the refrigerator promptly. If anything doesn't meet your expectations,
            please contact us within 24 hours and we'll make it right — guaranteed.
        </p>
    </div>

    {{-- Review section --}}
    <p style="font-size: 15px; color: #333; text-align: center; line-height: 1.7; margin-bottom: 8px;">
        We'd love to hear what you think! Your review helps other customers and
        helps us serve you better.
    </p>

    {{-- CTAs --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/account/orders/{{ $order->id }}" class="cta-btn-green"
           style="margin-right: 12px;">Leave a Review</a>
    </div>
    <div style="text-align: center; margin-top: 12px;">
        <a href="{{ config('app.url') }}/shop" class="cta-btn-outline">Shop Again</a>
    </div>

    <hr class="divider">

    {{-- Referral nudge --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">Tell a Friend</p>
        <p class="alert-text">
            Know someone who'd love fresh farm produce delivered to their door? Share T-Akomz
            Agro Estates with family and friends. Fresh from Ido Ekiti — for every Nigerian home.
        </p>
    </div>

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        Something not right? <a href="{{ config('app.url') }}/contact" style="color: #3B8A1E; text-decoration: none; font-weight: 600;">Contact us</a>
        within 24 hours of delivery and we'll resolve it promptly.
        Thank you for being part of the T-Akomz farm family.
    </p>

</x-emails.layout>
