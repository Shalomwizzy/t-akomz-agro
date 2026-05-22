<x-emails.layout subject="Your Farm Tour is Confirmed! — {{ $booking->reference }} | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">✅</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Your Farm Tour is Confirmed!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $booking->name }}, we're excited to have you visit T-Akomz Agro Estates.
        Your tour booking has been reviewed and approved. Pack your curiosity — it's going to be a great day!
    </p>

    {{-- Approval alert --}}
    <div class="alert-box-green">
        <p class="alert-title" style="color: #2E7A10;">🎉 Great News! Your Farm Tour Has Been Approved</p>
        <p class="alert-text">
            Your preferred date has been confirmed. Our team will be ready to welcome you at
            T-Akomz Agro Estates on the date shown below. Please arrive at least 10 minutes early.
        </p>
    </div>

    {{-- Booking Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Booking Reference</span>
            <span class="info-value mono">{{ $booking->reference }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Confirmed Date</span>
            <span class="info-value green">{{ $booking->confirmed_date ? \Carbon\Carbon::parse($booking->confirmed_date)->format('d M Y') : $booking->preferred_date->format('d M Y') }}</span>
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

    {{-- Admin note if present --}}
    @if($booking->admin_note)
    <div style="background: #F8F8F6; border: 1px solid #E0E0D8; border-left: 4px solid #2E7A10; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px;">
        <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #888; margin: 0 0 8px;">Note from Our Team</p>
        <p style="font-size: 14px; color: #333; line-height: 1.65; margin: 0; font-style: italic;">
            "{{ $booking->admin_note }}"
        </p>
    </div>
    @endif

    {{-- What to bring --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 14px;">
        What to Bring
    </p>
    <ul class="steps-list">
        <li class="step-item">
            <div class="step-num" style="background: #EBF9E0; color: #2E7A10;">👕</div>
            <div class="step-content">
                <h4>Comfortable Clothing</h4>
                <p>Wear clothes and footwear suited for a farm environment — we'll be walking through open grounds.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num" style="background: #EBF9E0; color: #2E7A10;">📷</div>
            <div class="step-content">
                <h4>Camera or Smartphone</h4>
                <p>Capture the scenery, the animals and the farm experience. Photography is encouraged!</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num" style="background: #EBF9E0; color: #2E7A10;">💧</div>
            <div class="step-content">
                <h4>Water Bottle</h4>
                <p>Stay hydrated during your tour. We'll also have refreshments available on site.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num" style="background: #EBF9E0; color: #2E7A10;">🤩</div>
            <div class="step-content">
                <h4>Curiosity &amp; Questions</h4>
                <p>Our guides love sharing knowledge about farming, sustainability and agro-business. Ask away!</p>
            </div>
        </li>
    </ul>

    <hr class="divider">

    {{-- Arrival instructions --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">📍 Arrival Instructions</p>
        <p class="alert-text">
            T-Akomz Agro Estates is located on <strong>Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</strong>.
            Please arrive at least 10 minutes before your scheduled tour time. Call us if you're running
            late or need directions — our team is always available.
        </p>
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="https://maps.google.com/?q=Ido+Ekiti+Ekiti+State+Nigeria" class="cta-btn">Get Directions</a>
    </div>

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        Need to change or cancel your tour? <a href="{{ config('app.url') }}/contact" style="color: #3B8A1E; text-decoration: none; font-weight: 600;">Contact us</a>
        at least 48 hours before your visit date. We look forward to welcoming you!
    </p>

</x-emails.layout>
