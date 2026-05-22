@extends('layouts.app')

@section('title', 'About Us - T-Akomz Agro Estates')
@section('meta_description', 'Learn about T-Akomz Agro Estates — Nigeria\'s premier integrated farm, delivering fresh poultry, livestock, eggs and organic produce direct from farm to table.')

@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden bg-surface-card border-b border-surface-border">
    <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #B8F397 0, #B8F397 1px, transparent 0, transparent 50%); background-size: 20px 20px;"></div>
    <div class="container-custom py-20 relative">
        <div class="max-w-2xl">
            <p class="text-brand-green text-sm font-semibold tracking-widest uppercase mb-3">Our Story</p>
            <h1 class="font-display text-4xl md:text-6xl font-bold text-content-primary leading-tight mb-5">
                From Our Farm <br><span class="text-gradient">To Your Table</span>
            </h1>
            <p class="text-content-secondary text-lg leading-relaxed">
                T-Akomz Agro Estates is a world-class integrated farm in the heart of Nigeria,
                dedicated to producing the freshest, safest, and most nutritious agricultural products.
            </p>
        </div>
    </div>
</div>

{{-- Story Section --}}
<div class="container-custom py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
        <div>
            <h2 class="font-display text-3xl font-bold text-content-primary mb-5">Who We Are</h2>
            <div class="space-y-4 text-content-secondary leading-relaxed">
                <p>Founded in 2008 with just 200 birds and a dream, T-Akomz Agro Estates has grown into one of Nigeria's most respected agricultural enterprises. We operate a fully integrated farm covering poultry production, livestock breeding, aquaculture, and organic crop farming.</p>
                <p>Our name "T-Akomz" represents our core values: Transparency, Accountability, Knowledge, Opportunity, Mastery, and Zeal — principles that guide every decision we make from the farm floor to your delivery.</p>
                <p>We believe Nigerians deserve access to safe, high-quality protein and fresh produce. That's why we cut out the middlemen and deliver directly to consumers, businesses, and exporters across the country.</p>
            </div>
        </div>
        <div class="relative">
            <div class="aspect-square bg-surface-elevated rounded-3xl overflow-hidden">
                @php $aboutImg = \App\Models\SiteSetting::get('about_image_1'); @endphp
                <img src="{{ $aboutImg ? \Illuminate\Support\Facades\Storage::disk('public')->url($aboutImg) : asset('images/about-farm.jpg') }}"
                     alt="T-Akomz Farm"
                     class="w-full h-full object-cover"
                     onerror="this.src='https://placehold.co/600x600/111111/B8F397?text=Our+Farm'">
            </div>
            <div class="absolute -bottom-4 -left-4 bg-surface-card border border-surface-border rounded-2xl px-5 py-3 shadow-card">
                <p class="font-display text-3xl font-bold text-brand-green">15+</p>
                <p class="text-xs text-content-muted">Years in Operation</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-20">
        @foreach([
            ['5,000+', 'Birds Processed Monthly', '🐔'],
            ['50,000+', 'Eggs Per Month', '🥚'],
            ['200+', 'Livestock', '🐄'],
            ['10,000+', 'Happy Customers', '😊'],
        ] as [$num, $label, $icon])
        <div class="card p-6 text-center">
            <div class="text-3xl mb-2">{{ $icon }}</div>
            <p class="font-display text-3xl font-bold text-brand-green">{{ $num }}</p>
            <p class="text-xs text-content-muted mt-1">{{ $label }}</p>
        </div>
        @endforeach
    </div>

    {{-- Values --}}
    <div class="text-center mb-12">
        <h2 class="font-display text-3xl font-bold text-content-primary mb-2">Our Values</h2>
        <p class="text-content-muted">The principles that guide everything we do.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
        @foreach([
            ['🌱', 'Farm Fresh', 'Every product leaves our farm at peak freshness. No cold storage for weeks — we harvest and ship.'],
            ['🔬', 'Safety First', 'NAFDAC-compliant practices, veterinary-supervised poultry, and rigorous quality checks at every stage.'],
            ['🌍', 'Sustainable', 'We practise regenerative farming — composting, minimal waste, and responsible land stewardship.'],
            ['🤝', 'Community', 'We employ 150+ Nigerians and support smallholder farmers through our outgrower programme.'],
            ['📦', 'Direct Delivery', 'Farm-to-door with cold-chain packaging, ensuring freshness reaches your kitchen intact.'],
            ['🏆', 'Export Quality', 'Our standards exceed domestic requirements — built for global export markets.'],
        ] as [$icon, $title, $desc])
        <div class="card p-6">
            <div class="text-3xl mb-3">{{ $icon }}</div>
            <h3 class="font-display font-semibold text-content-primary mb-2">{{ $title }}</h3>
            <p class="text-content-muted text-sm leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>

    {{-- CTA --}}
    <div class="card p-10 text-center bg-brand-green/5 border-brand-green/20">
        <h2 class="font-display text-3xl font-bold text-content-primary mb-3">Ready to experience the difference?</h2>
        <p class="text-content-muted mb-8">Order fresh produce today and taste what real quality means.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3.5">Shop Now</a>
            <a href="{{ route('contact') }}" class="btn-outline px-8 py-3.5">Get in Touch</a>
        </div>
    </div>
</div>
@endsection
