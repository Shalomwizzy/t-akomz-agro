@extends('layouts.app')
@section('title', 'Refund Policy - T-Akomz Agro Estates')
@section('content')
<div class="container-custom py-12 max-w-3xl">
    <h1 class="font-display text-3xl font-bold text-content-primary mb-2">Refund Policy</h1>
    <p class="text-content-muted text-sm mb-10">Last updated: January 2024</p>
    <div class="prose prose-invert prose-green max-w-none text-content-secondary prose-headings:text-content-primary prose-headings:font-display">
        <h2>Our Commitment</h2>
        <p>At T-Akomz Agro Estates, we stand behind the quality of every product we sell. If something is wrong with your order, we will make it right.</p>
        <h2>Eligible Refund Situations</h2>
        <ul>
            <li>Products arrive spoiled, damaged, or significantly different from what was described</li>
            <li>Wrong items delivered</li>
            <li>Order not delivered within 7 business days of the estimated delivery date</li>
        </ul>
        <h2>How to Request a Refund</h2>
        <ol>
            <li>Report the issue within <strong>24 hours</strong> of receiving your order</li>
            <li>Take clear photos of the affected products</li>
            <li>Contact us via WhatsApp, email, or your account order page</li>
            <li>Include your order number and photos in the report</li>
        </ol>
        <h2>Refund Options</h2>
        <p>We offer three options for eligible claims:</p>
        <ul>
            <li><strong>Full Refund</strong> — returned to your original payment method within 3–5 business days</li>
            <li><strong>Replacement</strong> — we resend the affected items at no extra cost</li>
            <li><strong>Store Credit</strong> — credited to your account for future orders</li>
        </ul>
        <h2>Non-Refundable Situations</h2>
        <ul>
            <li>Change of mind after delivery</li>
            <li>Perishables not stored correctly after delivery</li>
            <li>Reports made more than 24 hours after delivery</li>
        </ul>
        <h2>Contact Us</h2>
        <p>To start a refund claim: <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email') }}">{{ \App\Models\SiteSetting::get('contact_email') }}</a> or WhatsApp {{ \App\Models\SiteSetting::get('whatsapp_number') }}</p>
    </div>
</div>
@endsection
