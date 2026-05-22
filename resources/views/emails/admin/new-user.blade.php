<x-emails.layout subject="[Admin] New Customer Registered — T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #FFF8E1;">
        <span style="font-size: 30px;">👤</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">New Customer Registered</h1>
    <p class="email-subtitle" style="text-align: center;">
        A new customer has created an account on T-Akomz Agro Estates.
        Review their profile below and consider a follow-up outreach.
    </p>

    {{-- Customer Info --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Full Name</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email Address</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Registered At</span>
            <span class="info-value">{{ $user->created_at->format('d M Y, g:i A') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">User ID</span>
            <span class="info-value mono">#{{ $user->id }}</span>
        </div>
    </div>

    {{-- Follow-up alert --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">Follow-Up Recommended</p>
        <p class="alert-text">
            New customers are more likely to place their first order within 48 hours of registration.
            Consider reaching out via email or WhatsApp to welcome them and highlight your current
            featured products.
        </p>
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/customers/{{ $user->id }}" class="cta-btn">View Customer Profile</a>
    </div>

    <hr class="divider">

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated admin notification from T-Akomz Agro Estates.
        Only ADMIN and SUPER_ADMIN users receive this alert.
    </p>

</x-emails.layout>
