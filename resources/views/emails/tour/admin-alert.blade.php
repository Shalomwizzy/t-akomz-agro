<x-emails.layout subject="[Admin] New Farm Tour Booking — {{ $booking->reference }}">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #FFF8E1;">
        <span style="font-size: 30px;">🏕</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">New Farm Tour Booking</h1>
    <p class="email-subtitle" style="text-align: center;">
        A customer has submitted a farm tour booking and their payment has been received.
        Review the details below and approve or reject the booking within 24 hours.
    </p>

    {{-- Amount Highlight --}}
    <div class="highlight-box">
        <span class="amount">{{ $booking->formatted_amount }}</span>
        <span class="amount-label">Amount Received</span>
    </div>

    {{-- Booking Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Booking Reference</span>
            <span class="info-value mono">{{ $booking->reference }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Customer Name</span>
            <span class="info-value">{{ $booking->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $booking->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $booking->phone ?? 'Not provided' }}</span>
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
            <span class="info-value green">{{ $booking->preferred_date->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Submitted At</span>
            <span class="info-value">{{ $booking->created_at->format('d M Y, g:i A') }}</span>
        </div>
        @if($booking->notes)
        <div class="info-row">
            <span class="info-label">Customer Notes</span>
            <span class="info-value" style="text-align: right; max-width: 280px; font-style: italic;">{{ $booking->notes }}</span>
        </div>
        @endif
    </div>

    {{-- Action required alert --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">⚡ Action Required — Respond Within 24 Hours</p>
        <p class="alert-text">
            The customer is waiting for confirmation. Log in to the admin panel, review the
            requested date against your calendar, then approve or reject with a note.
            Approved bookings trigger an automatic confirmation email to the customer.
        </p>
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/tour-bookings/{{ $booking->id }}" class="cta-btn">Manage This Booking</a>
    </div>

    <hr class="divider">

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated admin notification from T-Akomz Agro Estates.
        Payment is held pending your approval action.
    </p>

</x-emails.layout>
