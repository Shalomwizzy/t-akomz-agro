@extends('layouts.app')

@section('title', 'FAQs - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-12">
        <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary">Frequently Asked Questions</h1>
        <p class="text-content-muted mt-2">Everything you need to know about ordering from us.</p>
    </div>
</div>

<div class="container-custom py-12">
    <div class="max-w-3xl mx-auto">
        @php
        $faqs = [
            ['Ordering', [
                ['How do I place an order?', 'Browse our shop, add items to your cart, and proceed to checkout. You can pay online via Paystack or Flutterwave, bank transfer, or cash on delivery.'],
                ['Is there a minimum order amount?', 'Yes, our minimum order is ₦' . number_format(\App\Models\SiteSetting::get('min_order_amount', 3000)) . '. This ensures we can efficiently process and deliver your order.'],
                ['Can I modify my order after placing it?', 'Orders can be modified within 1 hour of placement. Contact us immediately via WhatsApp if you need to make changes.'],
            ]],
            ['Delivery', [
                ['How long does delivery take?', 'Standard delivery takes 2–5 business days. Express delivery takes 1–2 business days. We deliver nationwide across Nigeria.'],
                ['How do you ensure freshness during delivery?', 'We use food-grade insulated packaging with ice packs for temperature-sensitive items. Products are packed fresh on the day of dispatch.'],
                ['Do you deliver to my location?', 'We deliver to all 36 states and FCT in Nigeria. Delivery fees vary by location and are shown at checkout.'],
                ['Can I pick up from the farm?', 'Absolutely! Farm pickup is free. Our farm is located in Benue State. Contact us to schedule your pickup slot.'],
            ]],
            ['Products', [
                ['Are your products organic?', 'Many of our products are certified organic — look for the "Organic" badge on product pages. All our products are free from harmful chemicals and growth hormones.'],
                ['How fresh are your products?', 'Poultry is processed fresh daily. Eggs are collected and packed within 24 hours. Livestock is processed to order. All produce is harvested at peak ripeness.'],
                ['Do you offer wholesale prices?', 'Yes! We have wholesale pricing for bulk orders (restaurants, supermarkets, exporters). Contact us for a wholesale quote.'],
            ]],
            ['Payments & Returns', [
                ['What payment methods do you accept?', 'We accept card payments via Paystack and Flutterwave, bank transfers, and cash on delivery for eligible locations.'],
                ['What is your return policy?', 'If you receive damaged or spoiled items, report within 24 hours with photos. We will arrange a replacement or full refund at no cost to you.'],
                ['Is my payment information secure?', 'Absolutely. All payments are processed by Paystack and Flutterwave — PCI-DSS compliant payment gateways. We never store your card details.'],
            ]],
        ];
        @endphp

        <div class="space-y-8" x-data="{ open: null }">
            @foreach($faqs as $groupIdx => [$group, $questions])
            <div>
                <h2 class="font-display font-bold text-content-primary text-xl mb-4">{{ $group }}</h2>
                <div class="space-y-2">
                    @foreach($questions as $qIdx => $qa)
                    @php $key = $groupIdx . '-' . $qIdx; @endphp
                    <div class="card overflow-hidden">
                        <button @click="open = open === '{{ $key }}' ? null : '{{ $key }}'"
                                class="w-full text-left px-5 py-4 flex items-center justify-between gap-4">
                            <span class="font-medium text-content-primary text-sm">{{ $qa[0] }}</span>
                            <svg class="w-4 h-4 text-content-muted flex-shrink-0 transition-transform"
                                 :class="open === '{{ $key }}' ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open === '{{ $key }}'" x-transition class="px-5 pb-4">
                            <p class="text-content-muted text-sm leading-relaxed">{{ $qa[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center card p-8 bg-brand-green/5 border-brand-green/20">
            <h3 class="font-display font-semibold text-content-primary mb-2">Still have questions?</h3>
            <p class="text-content-muted text-sm mb-5">Our team is ready to help you. Chat with us on WhatsApp for the fastest response.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="https://wa.me/{{ \App\Models\SiteSetting::get('whatsapp_number') }}" target="_blank" rel="noopener noreferrer"  class="btn-primary px-6 py-3 text-sm">
                    Chat on WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="btn-outline px-6 py-3 text-sm">Send a Message</a>
            </div>
        </div>
    </div>
</div>
@endsection
