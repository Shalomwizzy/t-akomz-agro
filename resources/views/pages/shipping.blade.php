@extends('layouts.app')
@section('title', 'Shipping Policy - T-Akomz Agro Estates')
@section('content')
<div class="container-custom py-12 max-w-3xl">
    <h1 class="font-display text-3xl font-bold text-content-primary mb-2">Shipping Policy</h1>
    <p class="text-content-muted text-sm mb-10">Last updated: January 2024</p>
    <div class="prose prose-invert prose-green max-w-none text-content-secondary prose-headings:text-content-primary prose-headings:font-display">
        <h2>Delivery Coverage</h2>
        <p>We deliver to all 36 states and FCT in Nigeria. Some remote areas may have limited or no availability — contact us to confirm before ordering.</p>
        <h2>Delivery Options & Fees</h2>
        <table>
            <thead><tr><th>Option</th><th>Timeframe</th><th>Fee</th></tr></thead>
            <tbody>
                <tr><td>Standard Delivery</td><td>2–5 business days</td><td>₦{{ number_format(\App\Models\SiteSetting::get('delivery_fee_standard', 2500)) }}</td></tr>
                <tr><td>Express Delivery</td><td>1–2 business days</td><td>₦{{ number_format(\App\Models\SiteSetting::get('delivery_fee_express', 5000)) }}</td></tr>
                <tr><td>Farm Pickup</td><td>Scheduled</td><td>Free</td></tr>
            </tbody>
        </table>
        <h2>Order Processing</h2>
        <p>Orders placed before 12PM (noon) are processed same day. Orders after 12PM are processed the next business day. Orders are not processed on Sundays.</p>
        <h2>Packaging</h2>
        <p>All perishable products are packed in food-grade insulated bags with ice packs to maintain freshness during transit. Our packaging is designed to keep products at safe temperatures for up to 12 hours.</p>
        <h2>Tracking</h2>
        <p>You'll receive SMS/email updates as your order moves through each stage. Track your order anytime via your account or our <a href="{{ route('orders.track') }}">order tracking page</a>.</p>
        <h2>Missed Deliveries</h2>
        <p>If no one is available to receive your order, our driver will attempt delivery twice. After two failed attempts, the order will be held for 24 hours before being returned. You may be charged a re-delivery fee.</p>
        <h2>Damaged in Transit</h2>
        <p>While we take utmost care in packaging, if products arrive damaged due to transit, please refer to our <a href="{{ route('refund-policy') }}">Refund Policy</a> for how to report and resolve this.</p>
    </div>
</div>
@endsection
