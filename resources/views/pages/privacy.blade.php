@extends('layouts.app')
@section('title', 'Privacy Policy - T-Akomz Agro Estates')
@section('content')
<div class="container-custom py-12 max-w-3xl">
    <nav class="flex items-center gap-2 text-sm text-content-muted mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
        <span>/</span><span class="text-content-primary">Privacy Policy</span>
    </nav>
    <h1 class="font-display text-3xl font-bold text-content-primary mb-2">Privacy Policy</h1>
    <p class="text-content-muted text-sm mb-10">Last updated: January 2024</p>
    <div class="prose prose-invert prose-green max-w-none text-content-secondary prose-headings:text-content-primary prose-headings:font-display">
        <h2>1. Information We Collect</h2>
        <p>We collect information you provide when creating an account, placing orders, or contacting us. This includes your name, email address, phone number, delivery address, and payment details. We also collect usage data via cookies to improve your experience on our website.</p>
        <h2>2. How We Use Your Information</h2>
        <p>Your information is used to process and deliver your orders, send order confirmations and updates, improve our services, communicate promotional offers (with your consent), and comply with legal obligations.</p>
        <h2>3. Payment Data</h2>
        <p>All payment transactions are processed by Paystack or Flutterwave, both PCI-DSS compliant payment gateways. We do not store your card details on our servers.</p>
        <h2>4. Data Sharing</h2>
        <p>We do not sell or rent your personal data to third parties. We may share data with trusted partners (delivery providers, payment processors) only as necessary to fulfil your orders.</p>
        <h2>5. Data Security</h2>
        <p>We implement industry-standard security measures including SSL encryption, secure servers, and access controls to protect your personal information.</p>
        <h2>6. Your Rights</h2>
        <p>You have the right to access, update, or delete your personal data at any time via your account settings or by contacting us at <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}">{{ \App\Models\SiteSetting::get('contact_email') }}</a>.</p>
        <h2>7. Cookies</h2>
        <p>We use cookies for session management and analytics. You can disable cookies in your browser settings, though some features may not function correctly.</p>
        <h2>8. Contact</h2>
        <p>For privacy-related questions, contact us at <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}">{{ \App\Models\SiteSetting::get('contact_email') }}</a>.</p>
    </div>
</div>
@endsection
