<x-emails.layout subject="Update on Your Farm Tour Request — {{ $booking->reference }} | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #FFFBEB;">
        <span style="font-size: 30px;">📅</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Update on Your Farm Tour Request</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $booking->name }}, thank you for your patience. We've reviewed your farm tour
        request and unfortunately your preferred date is not available. We're sorry for
        the inconvenience — details below.
    </p>

    {{-- Unavailability alert --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">😔 Preferred Date Unavailable</p>
        <p class="alert-text">
            We're sorry — your requested date of
            <strong>{{ $booking->preferred_date->format('d M Y') }}</strong>
            is not available. This may be due to a fully booked schedule or operational constraints
            on that date. Please see below for your options.
        </p>
    </div>

    {{-- Booking Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Booking Reference</span>
            <span class="info-value mono">{{ $booking->reference }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Your Preferred Date</span>
            <span class="info-value" style="text-decoration: line-through; color: #999; font-weight: 600;">
                {{ $booking->preferred_date->format('d M Y') }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tour Package</span>
            <span class="info-value">{{ $booking->package }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Number of Persons</span>
            <span class="info-value">{{ $booking->persons }} {{ Str::plural('Person', $booking->persons) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Amount Paid</span>
            <span class="info-value">{{ $booking->formatted_amount }}</span>
        </div>
    </div>

    {{-- Admin note --}}
    @if($booking->admin_note)
    <div style="background: #FFF8E1; border: 1px solid #FDE68A; border-left: 4px solid #F5A623; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px;">
        <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #92400E; margin: 0 0 8px;">Message from Our Team</p>
        <p style="font-size: 14px; color: #333; line-height: 1.65; margin: 0; font-style: italic;">
            "{{ $booking->admin_note }}"
        </p>
    </div>
    @endif

    {{-- Alternative date if available --}}
    @if($booking->alternative_date)
    <div class="alert-box-green">
        <p class="alert-title" style="color: #2E7A10;">📆 We Have an Alternative Date for You</p>
        <p class="alert-text">
            Our team suggests <strong>{{ \Carbon\Carbon::parse($booking->alternative_date)->format('d M Y') }}</strong>
            as an alternative date for your farm tour. If this works for you, click the button below
            to book a new date or reply to this email to confirm.
        </p>
    </div>
    @endif

    {{-- Refund notice --}}
    <div class="alert-box-green">
        <p class="alert-title" style="color: #2E7A10;">💳 Full Refund Guaranteed</p>
        <p class="alert-text">
            Your payment of <strong>{{ $booking->formatted_amount }}</strong> is fully refunded.
            Refunds are processed within <strong>3–5 business days</strong> and will be returned
            to your original payment method. No deductions, no hidden charges.
        </p>
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/farm-tour" class="cta-btn">Book a New Date</a>
    </div>

    <hr class="divider">

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        We hope to host you at T-Akomz Agro Estates soon. Have questions about your refund
        or alternative dates? <a href="{{ config('app.url') }}/contact" style="color: #3B8A1E; text-decoration: none; font-weight: 600;">Contact us</a>
        — we'll respond promptly.
    </p>

</x-emails.layout>
