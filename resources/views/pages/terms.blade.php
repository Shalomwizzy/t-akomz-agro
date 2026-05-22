@extends('layouts.app')
@section('title', 'Terms of Service - T-Akomz Agro Estates')
@section('content')
<div class="container-custom py-12 max-w-3xl">
    <nav class="flex items-center gap-2 text-sm text-content-muted mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
        <span>/</span><span class="text-content-primary">Terms of Service</span>
    </nav>
    <h1 class="font-display text-3xl font-bold text-content-primary mb-2">Terms of Service</h1>
    <p class="text-content-muted text-sm mb-10">Last updated: January 2024</p>
    <div class="prose prose-invert prose-green max-w-none text-content-secondary prose-headings:text-content-primary prose-headings:font-display">
        <h2>1. Acceptance of Terms</h2>
        <p>By using T-Akomz Agro Estates' website and services, you agree to these Terms of Service. If you do not agree, please do not use our services.</p>
        <h2>2. Ordering</h2>
        <p>All orders are subject to product availability. We reserve the right to refuse or cancel any order at our discretion. Order confirmations do not guarantee delivery until we confirm stock.</p>
        <h2>3. Pricing</h2>
        <p>Prices are in Nigerian Naira (₦) and include applicable taxes. We reserve the right to change prices at any time. Any price changes after an order is confirmed will not affect that order.</p>
        <h2>4. Delivery</h2>
        <p>Estimated delivery times are provided in good faith but are not guaranteed. We are not liable for delays caused by circumstances beyond our control (weather, logistics disruptions, etc.).</p>
        <h2>5. Product Quality</h2>
        <p>We guarantee the freshness and quality of our products at the time of dispatch. Claims for damaged or spoiled goods must be reported within 24 hours of delivery with photographic evidence.</p>
        <h2>6. Payments</h2>
        <p>All payments must be completed before order processing. For bank transfer orders, processing begins only after payment confirmation.</p>
        <h2>7. Accounts</h2>
        <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account.</p>
        <h2>8. Limitation of Liability</h2>
        <p>T-Akomz Agro Estates' liability is limited to the value of your order. We are not responsible for indirect or consequential damages.</p>
        <h2>9. Governing Law</h2>
        <p>These terms are governed by the laws of the Federal Republic of Nigeria.</p>
        <h2>10. Contact</h2>
        <p>For terms-related questions, contact <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}">{{ \App\Models\SiteSetting::get('contact_email') }}</a>.</p>
    </div>
</div>
@endsection
