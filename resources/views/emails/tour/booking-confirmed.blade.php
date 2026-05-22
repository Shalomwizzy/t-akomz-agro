<x-emails.layout subject="Farm Tour Booked — Payment Received | T-Akomz Agro Estates">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">🌿</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Farm Tour Booked — Payment Received!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $booking->name }}, thank you for booking a farm tour at T-Akomz Agro Estates.
        We've received your payment and your request is under review. Our team will confirm
        your date within 24 hours.
    </p>

    {{-- Amount Highlight --}}
    <div class="highlight-box">
        <span class="amount">{{ $booking->formatted_amount }}</span>
        <span class="amount-label">Amount Paid</span>
    </div>

    {{-- Booking Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Booking Reference</span>
            <span class="info-value mono">{{ $booking->reference }}</span>
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
            <span class="info-label">Preferred Date</span>
            <span class="info-value">{{ $booking->preferred_date->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Approval Status</span>
            <span class="info-value" style="color: #D97706; font-weight: 700;">Pending Approval</span>
        </div>
        @if($booking->notes)
        <div class="info-row">
            <span class="info-label">Your Notes</span>
            <span class="info-value" style="text-align: right; max-width: 280px;">{{ $booking->notes }}</span>
        </div>
        @endif
    </div>

    {{-- Pending review alert --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">⏳ Awaiting Date Confirmation</p>
        <p class="alert-text">
            Our team will review your preferred date and availability, then send you a confirmation
            email within 24 hours. If your preferred date is unavailable, we'll suggest the nearest
            alternative and arrange a refund if needed.
        </p>
    </div>

    {{-- What to expect steps --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 14px;">
        What Happens Next
    </p>
    <ul class="steps-list">
        <li class="step-item">
            <div class="step-num">✓</div>
            <div class="step-content">
                <h4>Payment Received</h4>
                <p>Your booking payment of {{ $booking->formatted_amount }} has been received and recorded.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h4>Date Confirmation</h4>
                <p>We'll verify availability for {{ $booking->preferred_date->format('d M Y') }} and send you a confirmation email within 24 hours.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h4>Your Visit Day</h4>
                <p>Arrive at Oke-Ido Road, Ido Ekiti, Ekiti State. Our team will welcome you and guide you through the farm experience.</p>
            </div>
        </li>
    </ul>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/contact" class="cta-btn">Contact Us with Questions</a>
    </div>

    <hr class="divider">

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        Keep your booking reference <strong style="color: #3B8A1E;">{{ $booking->reference }}</strong> handy.
        You'll need it for any queries about your tour booking.
    </p>

</x-emails.layout>
