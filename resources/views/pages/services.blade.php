@extends('layouts.app')
@section('title', 'Our Services - T-Akomz Agro Estates')
@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-12">
        <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary">Our Services</h1>
        <p class="text-content-muted mt-2">Beyond retail — how T-Akomz Agro Estates can partner with your business.</p>
    </div>
</div>
<div class="container-custom py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        @foreach([
            ['🛒', 'Online Retail', 'Order fresh farm produce directly from our website. Secure payment, fast delivery, freshness guarantee.', route('shop.index'), 'Shop Now'],
            ['🏭', 'Wholesale & Bulk', 'Special pricing for restaurants, supermarkets, hotels, and catering businesses. Minimum bulk order applies.', route('contact'), 'Get a Quote'],
            ['✈️', 'Export Services', 'NAFDAC-certified, export-grade produce for international buyers. We handle documentation and cold-chain logistics.', route('contact'), 'Enquire Now'],
            ['🌱', 'Outgrower Programme', 'Join our network of partner farmers. We provide training, inputs, and guaranteed off-take for your produce.', route('contact'), 'Apply Now'],
            ['👨‍🏫', 'Agro Training', 'Professional training in modern poultry management, livestock husbandry, and sustainable farming practices.', route('contact'), 'Learn More'],
            ['🏕️', 'Farm Tours', 'Educational and corporate farm visits. See our operations firsthand and learn from our experts.', route('farm-tour'), 'Book a Tour'],
        ] as [$icon, $title, $desc, $url, $cta])
        <div class="card p-6 flex gap-5">
            <div class="text-4xl flex-shrink-0">{{ $icon }}</div>
            <div>
                <h3 class="font-display font-bold text-content-primary text-xl mb-2">{{ $title }}</h3>
                <p class="text-content-muted text-sm leading-relaxed mb-4">{{ $desc }}</p>
                <a href="{{ $url }}" class="text-brand-green text-sm hover:underline font-medium">{{ $cta }} →</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card p-10 text-center bg-brand-green/5 border-brand-green/20">
        <h2 class="font-display text-2xl font-bold text-content-primary mb-3">Need something specific?</h2>
        <p class="text-content-muted mb-6">Our team handles custom requests — wholesale pricing, export documentation, and more.</p>
        <a href="{{ route('contact') }}" class="btn-primary px-8 py-3.5">Contact Us</a>
    </div>
</div>
@endsection
