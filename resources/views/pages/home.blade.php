@extends('layouts.app')

@section('title', 'Farm Fresh. Delivered to Your Door')
@section('meta_description', 'Premium poultry, eggs, livestock & organic produce from T-Akomz Agro Estates. 50 acres of certified farm in Ekiti State. Order online and get fresh delivery.')

@push('head')
<style>
/* ─── Hero ─── */
.hero-bg {
    background-color: #050505;
    background-image:
        radial-gradient(ellipse 100% 70% at 50% 0%, rgba(184,243,151,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 70%, rgba(111,174,75,0.04) 0%, transparent 55%);
}
/* ─── Animated pulse ─── */
@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.7; }
    70% { transform: scale(2); opacity: 0; }
    100% { transform: scale(2); opacity: 0; }
}
.pulse-ring { animation: pulse-ring 2.2s ease-out infinite; }

/* ─── Float ─── */
@keyframes gentle-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
.float-el { animation: gentle-float 5s ease-in-out infinite; }
.float-el-2 { animation: gentle-float 7s ease-in-out infinite; }

/* ─── Scroll indicator ─── */
@keyframes scroll-dot {
    0%, 100% { transform: translateY(0); opacity: 1; }
    60% { transform: translateY(8px); opacity: 0.3; }
}
.scroll-dot { animation: scroll-dot 1.8s ease-in-out infinite; }

/* ─── Gradient text ─── */
.hero-gradient-text {
    background: linear-gradient(135deg, #B8F397 0%, #7FC85A 50%, #B8F397 100%);
    background-size: 200% 100%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 4s linear infinite;
}
@keyframes shimmer {
    0% { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
}

/* ─── Glass badge cards ─── */
.glass-card {
    background: linear-gradient(145deg, rgba(20,20,20,0.95), rgba(12,12,12,0.9));
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 1px 0 rgba(255,255,255,0.05) inset;
    backdrop-filter: blur(12px);
}

/* ─── Category card ─── */
.cat-card {
    background: linear-gradient(160deg, #131313, #0d0d0d);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.3s, box-shadow 0.3s, transform 0.3s;
}
.cat-card:hover {
    border-color: rgba(184,243,151,0.3);
    box-shadow: 0 12px 40px rgba(0,0,0,0.5), 0 0 0 1px rgba(184,243,151,0.08);
    transform: translateY(-3px);
}
.cat-card:active { transform: translateY(-1px); }

/* ─── Step card ─── */
.step-card {
    background: linear-gradient(160deg, #141414, #0d0d0d);
    border: 1px solid rgba(255,255,255,0.06);
}

/* ─── USP card ─── */
.usp-card {
    background: linear-gradient(145deg, #131313, #0d0d0d);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
}
.usp-card:hover { border-color: rgba(184,243,151,0.2); transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.4); }

/* ─── Testimonial ─── */
.testi-card {
    background: linear-gradient(145deg, #131313, #0e0e0e);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.3s, box-shadow 0.3s;
}
.testi-card:hover { border-color: rgba(184,243,151,0.2); box-shadow: 0 16px 48px rgba(0,0,0,0.4); }

/* ─── Blog card ─── */
.blog-card {
    background: linear-gradient(160deg, #131313, #0d0d0d);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.3s, box-shadow 0.3s, transform 0.3s;
}
.blog-card:hover { border-color: rgba(184,243,151,0.2); transform: translateY(-3px); box-shadow: 0 16px 48px rgba(0,0,0,0.5); }

/* ─── Newsletter box ─── */
.nl-box {
    background: radial-gradient(ellipse at 50% -10%, rgba(184,243,151,0.1) 0%, transparent 65%),
                linear-gradient(160deg, #141414, #0a0a0a);
    border: 1px solid rgba(184,243,151,0.14);
}

/* ─── Section divider ─── */
.s-divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(184,243,151,0.12), transparent); }

/* ─── Stat block ─── */
.stat-num {
    background: linear-gradient(135deg, #B8F397, #6FAE4B);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}

/* ─── Light mode overrides ─── */
:root.light .hero-bg { background-color: #f5f8f5; background-image: radial-gradient(ellipse 100% 70% at 50% 0%, rgba(34,113,23,0.06) 0%, transparent 60%); }
:root.light .hero-gradient-text { background: linear-gradient(135deg, #1a7010, #22711a, #1a7010); -webkit-background-clip: text; background-clip: text; }
:root.light .glass-card { background: rgba(255,255,255,0.95); border-color: rgba(0,0,0,0.08); box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
:root.light .cat-card, :root.light .step-card, :root.light .usp-card, :root.light .testi-card, :root.light .blog-card { background: #ffffff; border-color: rgba(0,0,0,0.07); }
:root.light .cat-card:hover, :root.light .usp-card:hover, :root.light .blog-card:hover { border-color: rgba(34,113,23,0.2); box-shadow: 0 12px 40px rgba(0,0,0,0.08); }
:root.light .stat-num { background: linear-gradient(135deg, #1a7010, #22711a); -webkit-background-clip: text; background-clip: text; }
:root.light .nl-box { background: radial-gradient(ellipse at 50% -10%, rgba(34,113,23,0.07) 0%, transparent 65%), #ffffff; border-color: rgba(34,113,23,0.14); }
:root.light .s-divider { background: linear-gradient(90deg, transparent, rgba(34,113,23,0.12), transparent); }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════
     HERO — mobile-first, everything below 640px
════════════════════════════════════════════ --}}
<section class="hero-bg relative min-h-[100svh] flex flex-col items-center justify-center overflow-hidden px-4">

    {{-- Dot grid overlay --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(rgba(184,243,151,0.05) 1px, transparent 1px);
                background-size: 32px 32px;
                mask-image: radial-gradient(ellipse at 50% 30%, black 20%, transparent 75%);"></div>

    {{-- Ambient glow blobs --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] pointer-events-none"
         style="background: radial-gradient(ellipse, rgba(184,243,151,0.06) 0%, transparent 70%); filter: blur(40px);"></div>

    {{-- Mobile floating trust pills (visible on mobile) --}}
    <div class="flex items-center justify-center gap-2 flex-wrap mb-8 mt-4 sm:hidden">
        <span class="glass-card px-3 py-1.5 rounded-full text-xs font-semibold text-content-primary flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-brand-green rounded-full"></span> 50 Acres Farm
        </span>
        <span class="glass-card px-3 py-1.5 rounded-full text-xs font-semibold text-content-primary flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full"></span> Same-Day Delivery
        </span>
        <span class="glass-card px-3 py-1.5 rounded-full text-xs font-semibold text-content-primary flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span> 200+ Customers
        </span>
    </div>

    {{-- Live badge (desktop) --}}
    <div class="hidden sm:inline-flex items-center gap-2.5 px-4 py-2 rounded-full mb-8"
         style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.18);">
        <div class="relative flex-shrink-0">
            <div class="w-2 h-2 bg-brand-green rounded-full"></div>
            <div class="absolute inset-0 w-2 h-2 bg-brand-green rounded-full pulse-ring"></div>
        </div>
        <span class="text-brand-green text-sm font-semibold">Fresh harvest available now</span>
    </div>

    {{-- Headline --}}
    <h1 class="font-display font-black text-center leading-[0.95] tracking-tight mb-5
               text-[2.6rem] xs:text-5xl sm:text-6xl md:text-7xl lg:text-[84px]">
        <span class="block text-content-primary">Farm Fresh.</span>
        <span class="block hero-gradient-text mt-1">Delivered</span>
        <span class="block text-content-primary mt-1">to Your Door.</span>
    </h1>

    <p class="text-content-secondary text-base sm:text-lg md:text-xl text-center max-w-md mx-auto mb-8 leading-relaxed font-light px-2">
        Premium poultry, eggs & organic produce — straight from our
        <span class="text-content-primary font-semibold">50-acre certified farm</span>
        in Ido Ekiti, Ekiti State.
    </p>

    {{-- CTA Buttons --}}
    <div class="flex flex-col xs:flex-row items-center gap-3 mb-10 w-full max-w-xs xs:max-w-none xs:justify-center">
        <a href="{{ route('shop.index') }}"
           class="w-full xs:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl font-bold text-base"
           style="background: linear-gradient(135deg, #B8F397, #6FAE4B); color: #050505;">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Shop Now
        </a>
        <a href="{{ route('about') }}"
           class="w-full xs:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl font-semibold text-base text-content-primary border transition-all"
           style="border-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.04);">
            Our Farm
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Trust row — desktop only --}}
    <div class="hidden sm:flex flex-wrap items-center justify-center gap-5 text-xs text-content-muted mb-4">
        @foreach(['No Preservatives','Same-Day Delivery','Certified Organic','Secure Payment'] as $trust)
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ $trust }}
        </div>
        @endforeach
    </div>

    {{-- Desktop floating badges --}}
    <div class="absolute top-[20%] left-[4%] hidden xl:block float-el">
        <div class="glass-card rounded-2xl p-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-green/10 border border-brand-green/20 flex items-center justify-center text-lg flex-shrink-0">🥚</div>
                <div>
                    <p class="text-xs font-bold text-content-primary leading-tight">1,000+ Eggs</p>
                    <p class="text-xs text-content-muted">Collected Daily</p>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute top-[32%] right-[5%] hidden xl:block float-el-2">
        <div class="glass-card rounded-2xl p-3.5">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-orange-400/10 border border-orange-400/20 flex items-center justify-center text-lg flex-shrink-0">🐔</div>
                <div>
                    <p class="text-xs font-bold text-content-primary leading-tight">500+ Chickens</p>
                    <p class="text-xs text-content-muted">Farm Raised</p>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-[25%] left-[6%] hidden xl:block float-el">
        <div class="glass-card rounded-2xl p-3.5">
            <div class="flex items-center gap-2.5">
                <div class="relative flex-shrink-0">
                    <div class="w-2.5 h-2.5 bg-brand-green rounded-full"></div>
                    <div class="absolute inset-0 w-2.5 h-2.5 bg-brand-green rounded-full pulse-ring"></div>
                </div>
                <p class="text-xs font-bold text-brand-green">Ido Ekiti, Ekiti State</p>
            </div>
        </div>
    </div>
    <div class="absolute bottom-[30%] right-[6%] hidden xl:block float-el-2">
        <div class="glass-card rounded-2xl p-3.5">
            <div class="flex items-center gap-2.5">
                <span class="text-xl flex-shrink-0">⭐</span>
                <div>
                    <p class="text-xs font-bold text-content-primary leading-tight">200+ Customers</p>
                    <p class="text-xs text-content-muted">Across Nigeria</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-content-muted">
        <div class="w-6 h-10 border-2 border-content-muted/20 rounded-full flex items-start justify-center pt-1.5">
            <div class="w-1.5 h-2.5 bg-brand-green rounded-full scroll-dot"></div>
        </div>
        <span class="text-[10px] tracking-widest uppercase">Scroll</span>
    </div>
</section>

{{-- ════════════════════════════════════════════
     STATS
════════════════════════════════════════════ --}}
<div class="s-divider"></div>
<section class="py-10 sm:py-14" style="background: rgba(184,243,151,0.025);">
    <div class="container-custom px-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-0">
            @php
                $farmStats = [
                    ['icon' => '🐔', 'value' => number_format($stats['about_chickens'] ?? 500) . '+', 'label' => 'Chickens Raised',  'sub' => 'Free-range'],
                    ['icon' => '🥚', 'value' => number_format($stats['about_eggs_daily'] ?? 1000),    'label' => 'Eggs Per Day',      'sub' => 'Hormone-free'],
                    ['icon' => '🌿', 'value' => ($stats['about_acres'] ?? 50) . ' Ac',               'label' => 'Certified Farm',    'sub' => 'Ekiti State'],
                    ['icon' => '⭐', 'value' => number_format($stats['about_customers'] ?? 200) . '+','label' => 'Happy Customers',   'sub' => 'Nationwide'],
                ];
            @endphp
            @foreach($farmStats as $i => $stat)
            <div class="flex flex-col items-center text-center py-6 px-3 rounded-2xl sm:rounded-none"
                 style="{{ $i > 0 ? 'border-top: 1px solid rgba(255,255,255,0.04);' : '' }}
                        background: rgba(255,255,255,0.015);
                        {{ $i > 0 && $i < count($farmStats) ? 'sm:border-left: 1px solid rgba(255,255,255,0.04); sm:border-top: 0;' : '' }}">
                <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
                <div class="stat-num font-display font-black text-2xl sm:text-3xl mb-0.5">{{ $stat['value'] }}</div>
                <p class="text-content-primary text-xs sm:text-sm font-semibold">{{ $stat['label'] }}</p>
                <p class="text-content-muted text-[11px] mt-0.5">{{ $stat['sub'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
<div class="s-divider"></div>

{{-- ════════════════════════════════════════════
     CATEGORIES
════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 px-4">
    <div class="container-custom">
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
                  class="brand-pill">
                🌾 Our Categories
            </span>
            <h2 class="font-display font-black text-3xl sm:text-4xl md:text-5xl text-content-primary mb-3 leading-tight">
                What Are You<br class="sm:hidden"> Looking For?
            </h2>
            <p class="text-content-secondary text-base sm:text-lg max-w-md mx-auto">From farm-fresh poultry to organic inputs — everything at peak quality.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($categories as $cat)
            @php
                $icons = [
                    'poultry'        => ['emoji' => '🐔', 'bg' => 'rgba(251,146,60,0.1)',  'border' => 'rgba(251,146,60,0.25)'],
                    'eggs'           => ['emoji' => '🥚', 'bg' => 'rgba(250,204,21,0.1)',  'border' => 'rgba(250,204,21,0.25)'],
                    'livestock'      => ['emoji' => '🐐', 'bg' => 'rgba(167,139,250,0.1)', 'border' => 'rgba(167,139,250,0.25)'],
                    'crop-produce'   => ['emoji' => '🌽', 'bg' => 'rgba(52,211,153,0.1)',  'border' => 'rgba(52,211,153,0.25)'],
                    'dairy'          => ['emoji' => '🥛', 'bg' => 'rgba(147,197,253,0.1)', 'border' => 'rgba(147,197,253,0.25)'],
                    'organic-inputs' => ['emoji' => '🌱', 'bg' => 'rgba(184,243,151,0.1)', 'border' => 'rgba(184,243,151,0.25)'],
                    'farm-boxes'     => ['emoji' => '📦', 'bg' => 'rgba(251,146,60,0.1)',  'border' => 'rgba(251,146,60,0.25)'],
                ];
                $icon = $icons[$cat->slug] ?? ['emoji' => '🌿', 'bg' => 'rgba(184,243,151,0.1)', 'border' => 'rgba(184,243,151,0.25)'];
            @endphp
            <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card group rounded-2xl p-4 sm:p-6 text-center block active:scale-95 transition-transform">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl mx-auto mb-3 flex items-center justify-center text-2xl sm:text-3xl group-hover:scale-110 transition-transform duration-300"
                     style="background: {{ $icon['bg'] }}; border: 1px solid {{ $icon['border'] }};">
                    {{ $icon['emoji'] }}
                </div>
                <h3 class="font-display font-bold text-content-primary text-sm sm:text-base group-hover:text-brand-green transition-colors duration-300 leading-tight">{{ $cat->name }}</h3>
                <p class="text-content-muted text-[11px] sm:text-xs mt-1">{{ $cat->active_products_count }} {{ $cat->active_products_count === 1 ? 'product' : 'products' }}</p>
                <div class="h-0.5 w-0 group-hover:w-full mx-auto mt-3 transition-all duration-500 rounded-full"
                     style="background: linear-gradient(90deg, rgba(184,243,151,0.5), rgba(111,174,75,0.3));"></div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════════════════ --}}
<div class="s-divider"></div>
<section class="py-14 sm:py-20 px-4" style="background: rgba(255,255,255,0.015);">
    <div class="container-custom">
        <div class="flex items-end justify-between mb-8 sm:mb-12 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-3"
                      class="brand-pill">
                    🌾 Fresh Today
                </span>
                <h2 class="font-display font-black text-2xl sm:text-4xl md:text-5xl text-content-primary leading-tight">Fresh From<br class="sm:hidden"> The Farm</h2>
            </div>
            <a href="{{ route('shop.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-semibold text-xs sm:text-sm text-brand-green border border-brand-green/20 hover:border-brand-green/40 hover:bg-brand-green/5 transition-all">
                View All
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($featured as $product)
            @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
<div class="s-divider"></div>

{{-- ════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 px-4">
    <div class="container-custom">
        <div class="text-center mb-10 sm:mb-16">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
                  class="brand-pill">
                Simple Process
            </span>
            <h2 class="font-display font-black text-3xl sm:text-4xl md:text-5xl text-content-primary mb-3">Farm to Your Table</h2>
            <p class="text-content-secondary text-base sm:text-lg max-w-sm mx-auto">Freshest produce with zero hassle, every single time.</p>
        </div>

        @php
            $steps = [
                ['num' => '1', 'icon' => '🛒', 'title' => 'Browse & Order',    'desc' => 'Pick your farm-fresh products. Easy checkout, secure payment.',   'bg' => 'rgba(184,243,151,0.08)', 'bd' => 'rgba(184,243,151,0.2)'],
                ['num' => '2', 'icon' => '🌿', 'title' => 'We Harvest Fresh',  'desc' => 'Your order triggers same-day harvest. Nothing sits in storage.',   'bg' => 'rgba(52,211,153,0.08)',  'bd' => 'rgba(52,211,153,0.2)'],
                ['num' => '3', 'icon' => '📦', 'title' => 'Packed with Care',  'desc' => 'Inspected, weighed precisely, packed in clean insulated boxes.',   'bg' => 'rgba(251,146,60,0.08)',  'bd' => 'rgba(251,146,60,0.2)'],
                ['num' => '4', 'icon' => '🚚', 'title' => 'Delivered Fast',    'desc' => 'Same-day or next-day delivery. Track your order in real time.',    'bg' => 'rgba(147,197,253,0.08)', 'bd' => 'rgba(147,197,253,0.2)'],
            ];
        @endphp

        {{-- Mobile: vertical list with connectors --}}
        <div class="sm:hidden space-y-0">
            @foreach($steps as $i => $step)
            <div class="flex gap-4">
                {{-- Left: number + connector --}}
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0"
                         style="background: linear-gradient(135deg, #B8F397, #6FAE4B); color: #050505;">
                        {{ $step['num'] }}
                    </div>
                    @if($i < count($steps) - 1)
                    <div class="w-px flex-1 mt-1" style="background: rgba(184,243,151,0.2); min-height: 32px;"></div>
                    @endif
                </div>
                {{-- Right: content --}}
                <div class="pb-8 flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                             style="background: {{ $step['bg'] }}; border: 1px solid {{ $step['bd'] }};">
                            {{ $step['icon'] }}
                        </div>
                        <h3 class="font-display font-bold text-content-primary text-base">{{ $step['title'] }}</h3>
                    </div>
                    <p class="text-content-muted text-sm leading-relaxed pl-1">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Desktop: horizontal grid --}}
        <div class="hidden sm:grid sm:grid-cols-4 gap-5 relative">
            @foreach($steps as $i => $step)
            <div class="step-card rounded-2xl p-6 text-center relative">
                <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl relative"
                     style="background: {{ $step['bg'] }}; border: 1px solid {{ $step['bd'] }};">
                    {{ $step['icon'] }}
                    <div class="absolute -top-2.5 -right-2.5 w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                         style="background: linear-gradient(135deg, #B8F397, #6FAE4B); color: #050505;">
                        {{ $step['num'] }}
                    </div>
                </div>
                <h3 class="font-display font-bold text-content-primary mb-2">{{ $step['title'] }}</h3>
                <p class="text-content-muted text-sm leading-relaxed">{{ $step['desc'] }}</p>
                @if($i < count($steps) - 1)
                <div class="absolute top-10 -right-2.5 w-5 h-5 text-brand-green/30 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     WHY CHOOSE US — mobile-optimised grid
════════════════════════════════════════════ --}}
<div class="s-divider"></div>
<section class="py-14 sm:py-20 px-4" style="background: rgba(255,255,255,0.015);">
    <div class="container-custom">
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
                  class="brand-pill">
                Why T-Akomz
            </span>
            <h2 class="font-display font-black text-3xl sm:text-4xl md:text-5xl text-content-primary mb-3 leading-tight">
                We Don't Just<br class="sm:hidden"> Sell Farm Produce
            </h2>
            <p class="text-content-secondary text-base sm:text-lg max-w-md mx-auto">Freshness you can taste, trust built over years, zero middlemen, zero compromise.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $usps = [
                    ['icon' => '🌱', 'bg' => 'rgba(52,211,153,0.1)',  'bd' => 'rgba(52,211,153,0.2)',  'title' => '100% Organic',       'desc' => 'No antibiotics, no artificial hormones, no hidden chemicals.'],
                    ['icon' => '🚚', 'bg' => 'rgba(147,197,253,0.1)', 'bd' => 'rgba(147,197,253,0.2)', 'title' => 'Same-Day Delivery',  'desc' => 'Order before 12pm, get delivery the same day in Lagos.'],
                    ['icon' => '🐄', 'bg' => 'rgba(251,146,60,0.1)',  'bd' => 'rgba(251,146,60,0.2)',  'title' => 'Farm-to-Table',      'desc' => 'Harvested the same day it is delivered. No cold storage.'],
                    ['icon' => '📞', 'bg' => 'rgba(184,243,151,0.1)', 'bd' => 'rgba(184,243,151,0.2)', 'title' => '24/7 Support',       'desc' => 'WhatsApp, phone, or email — always available for you.'],
                ];
            @endphp
            @foreach($usps as $usp)
            <div class="usp-card rounded-2xl p-5 sm:p-6">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4"
                     style="background: {{ $usp['bg'] }}; border: 1px solid {{ $usp['bd'] }};">
                    {{ $usp['icon'] }}
                </div>
                <h3 class="font-display font-bold text-content-primary text-base mb-2">{{ $usp['title'] }}</h3>
                <p class="text-content-muted text-sm leading-relaxed">{{ $usp['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Farm Story — full row below USPs --}}
        <div class="mt-6 usp-card rounded-2xl p-5 sm:p-8">
            <div class="flex flex-col sm:flex-row gap-6 sm:gap-10 items-start sm:items-center">
                {{-- Text side --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl float-el flex-shrink-0"
                             style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.15);">🏡</div>
                        <div>
                            <p class="text-xs text-brand-green font-bold uppercase tracking-widest">Est. {{ $stats['about_founding_year'] ?? '2018' }}</p>
                            <h3 class="font-display font-bold text-content-primary text-lg leading-tight">Rooted in Ekiti,<br>Driven by Quality</h3>
                        </div>
                    </div>
                    <p class="text-content-muted text-sm leading-relaxed mb-5">
                        T-Akomz Agro Estates was founded to bridge the gap between Nigerian farms and Nigerian tables.
                        We operate {{ $stats['about_acres'] ?? 50 }} acres in Ido Ekiti, Ekiti State — poultry, livestock, and crop farming under organic principles.
                        Every product comes directly from our farm, not a middleman.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach([['🐔','Poultry'],['🥚','Eggs'],['🌾','Crops'],['🐐','Livestock']] as [$ic,$lb])
                        <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-content-secondary"
                              style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);">
                            {{ $ic }} {{ $lb }}
                        </span>
                        @endforeach
                    </div>
                    <a href="{{ route('about') }}" class="btn-primary inline-flex text-sm py-2.5 px-5">
                        Learn Our Story
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Stats side --}}
                <div class="grid grid-cols-2 gap-3 flex-shrink-0 w-full sm:w-56">
                    @foreach([['🌿', number_format($stats['about_acres'] ?? 50), 'Acres'],['👥', number_format($stats['about_customers'] ?? 200).'+', 'Customers'],['🐔', number_format($stats['about_chickens'] ?? 500).'+', 'Chickens'],['🥚', number_format($stats['about_eggs_daily'] ?? 1000), 'Eggs/day']] as [$ic,$vl,$lb])
                    <div class="rounded-xl p-3 text-center" style="background: rgba(184,243,151,0.05); border: 1px solid rgba(184,243,151,0.1);">
                        <div class="text-xl mb-1">{{ $ic }}</div>
                        <div class="stat-num font-display font-black text-lg leading-none">{{ $vl }}</div>
                        <p class="text-content-muted text-[11px] mt-0.5">{{ $lb }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<div class="s-divider"></div>

{{-- ════════════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 px-4">
    <div class="container-custom">
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-4"
                  class="brand-pill">
                Customer Stories
            </span>
            <h2 class="font-display font-black text-3xl sm:text-4xl md:text-5xl text-content-primary mb-3">What Our<br class="sm:hidden"> Customers Say</h2>
            <p class="text-content-secondary text-base sm:text-lg max-w-md mx-auto">{{ number_format($stats['about_customers'] ?? 200) }}+ satisfied customers across Nigeria.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            @php
                $testimonials = [
                    ['name' => 'Adaeze Okonkwo',   'loc' => 'Lagos',         'product' => 'Weekly Egg Box',      'init' => 'AO', 'bg' => 'rgba(251,146,60,0.12)',  'tc' => 'rgb(251,146,60)',  'quote' => 'The eggs are incredibly fresh — the yolks are so deep orange, nothing like what I used to buy at the market. The subscription is very convenient!'],
                    ['name' => 'Emeka Nwosu',       'loc' => 'Abuja',         'product' => 'Broiler Chicken',     'init' => 'EN', 'bg' => 'rgba(184,243,151,0.12)', 'tc' => 'rgb(184,243,151)','quote' => 'Ordered two chickens for a family celebration. Well-fed, healthy, and delivery was on time. Quality is unmatched anywhere in Nigeria.'],
                    ['name' => 'Fatima Abdullahi', 'loc' => 'Port Harcourt', 'product' => 'Organic Compost',     'init' => 'FA', 'bg' => 'rgba(52,211,153,0.12)',  'tc' => 'rgb(52,211,153)', 'quote' => 'I\'ve been using their poultry compost for my vegetable garden for 3 months. The difference in my crops is remarkable. Highly recommend!'],
                ];
            @endphp
            @foreach($testimonials as $t)
            <div class="testi-card rounded-2xl p-5 sm:p-7">
                {{-- Stars --}}
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-content-secondary text-sm sm:text-base leading-relaxed mb-5 italic">"{{ $t['quote'] }}"</p>
                <div class="flex items-center gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0"
                         style="background: {{ $t['bg'] }}; color: {{ $t['tc'] }};">{{ $t['init'] }}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-content-primary truncate">{{ $t['name'] }}</p>
                        <p class="text-xs text-content-muted truncate">{{ $t['loc'] }} · {{ $t['product'] }}</p>
                    </div>
                    <div class="ml-auto flex-shrink-0">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center" style="background: {{ $t['bg'] }};">
                            <svg class="w-3 h-3" fill="{{ $t['tc'] }}" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     BLOG PREVIEW
════════════════════════════════════════════ --}}
{{-- ════════════════════════════════════════════
     OUR TEAM
════════════════════════════════════════════ --}}
@if($team->count())
<div class="s-divider"></div>
<section class="py-14 sm:py-24 px-4 overflow-hidden" style="background: radial-gradient(ellipse 80% 60% at 50% 100%, rgba(184,243,151,0.04) 0%, transparent 70%);">
    <div class="container-custom">

        {{-- Section header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12 sm:mb-16">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px w-8 bg-brand-green"></div>
                    <span class="text-brand-green text-xs font-bold uppercase tracking-[0.2em]">Our People</span>
                </div>
                <h2 class="font-display font-black text-3xl sm:text-5xl md:text-6xl text-content-primary leading-[1.05]">
                    The Minds<br><span class="text-gradient">Behind the Farm</span>
                </h2>
            </div>
            <p class="text-content-muted text-sm sm:text-base max-w-xs leading-relaxed sm:text-right">
                Passionate experts who turn soil and seed into world-class produce every single day.
            </p>
        </div>

        {{-- Team grid — editorial portrait cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($team as $i => $member)
            @php
                $isEven = ($i % 2 === 0);
                $delay = $i * 100;
            @endphp
            <div class="group relative flex flex-col">

                {{-- Portrait card --}}
                <div class="relative overflow-hidden flex-1"
                     style="border-radius: 20px; background: #111; aspect-ratio: 3/4;">

                    {{-- Index number watermark --}}
                    <div class="absolute top-3 left-3 z-20 pointer-events-none select-none">
                        <span class="font-mono text-[10px] font-bold tracking-widest text-brand-green/40">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    {{-- Role badge top-right --}}
                    <div class="absolute top-3 right-3 z-20">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full brand-pill" style="backdrop-filter: blur(8px);">
                            {{ Str::limit($member->role, 14) }}
                        </span>
                    </div>

                    {{-- Photo --}}
                    <img src="{{ $member->image_url }}"
                         alt="{{ $member->name }}"
                         loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-110">

                    {{-- Gradient overlay — always present at bottom --}}
                    <div class="absolute inset-0 pointer-events-none"
                         style="background: linear-gradient(to top, rgba(5,5,5,0.95) 0%, rgba(5,5,5,0.5) 40%, transparent 70%);"></div>

                    {{-- Bio slides up on hover --}}
                    <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 z-10">
                        {{-- Name + role always visible --}}
                        <div class="transform transition-transform duration-500 ease-out group-hover:-translate-y-12 sm:group-hover:-translate-y-14">
                            <h3 class="font-display font-bold text-white text-sm sm:text-base leading-tight">
                                {{ $member->name }}
                            </h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="w-4 h-px" style="background: #B8F397;"></div>
                                <p class="text-[11px] sm:text-xs font-semibold uppercase tracking-wide" style="color: #B8F397;">
                                    {{ $member->role }}
                                </p>
                            </div>
                        </div>

                        {{-- Bio slides up --}}
                        @if($member->bio)
                        <div class="absolute inset-x-0 bottom-0 px-4 sm:px-5 pb-4 sm:pb-5 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out delay-75">
                            <p class="text-[11px] sm:text-xs leading-relaxed line-clamp-3" style="color: rgba(220,220,220,0.85);">
                                {{ $member->bio }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Hover glow border --}}
                    <div class="absolute inset-0 rounded-[20px] opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"
                         style="box-shadow: inset 0 0 0 1px rgba(184,243,151,0.3), 0 0 30px rgba(184,243,151,0.06);"></div>
                </div>

                {{-- Decorative accent line --}}
                <div class="mt-3 flex items-center gap-2 px-1">
                    <div class="h-px flex-1 bg-surface-border group-hover:bg-brand-green/40 transition-colors duration-500"></div>
                    <div class="w-1 h-1 rounded-full bg-surface-border group-hover:bg-brand-green transition-colors duration-500"></div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif

@if($latestPosts->count())
<div class="s-divider"></div>
<section class="py-14 sm:py-20 px-4" style="background: rgba(255,255,255,0.015);">
    <div class="container-custom">
        <div class="flex items-end justify-between mb-8 sm:mb-12 gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-3"
                      class="brand-pill">
                    Farm Blog
                </span>
                <h2 class="font-display font-black text-2xl sm:text-4xl md:text-5xl text-content-primary leading-tight">From the<br class="sm:hidden"> Farm Blog</h2>
            </div>
            <a href="{{ route('blog.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-semibold text-xs sm:text-sm text-brand-green border border-brand-green/20 hover:border-brand-green/40 hover:bg-brand-green/5 transition-all">
                All Articles
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card group rounded-2xl overflow-hidden block">
                <div class="aspect-video flex items-center justify-center relative overflow-hidden"
                     style="background: rgba(184,243,151,0.04);">
                    @if($post->cover_image)
                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="text-5xl group-hover:scale-110 transition-transform duration-300">📰</div>
                    @endif
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex items-center gap-2 mb-2.5">
                        @if($post->category)
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold"
                              class="brand-pill">{{ $post->category }}</span>
                        @endif
                        <span class="text-[11px] text-content-muted">{{ $post->reading_time }} min read</span>
                    </div>
                    <h3 class="font-display font-bold text-content-primary group-hover:text-brand-green transition-colors line-clamp-2 text-base sm:text-lg mb-1.5 leading-snug">{{ $post->title }}</h3>
                    <p class="text-content-muted text-xs sm:text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                    <div class="flex items-center justify-between mt-3.5">
                        <p class="text-[11px] text-content-muted">{{ $post->published_at?->format('M d, Y') }}</p>
                        <span class="text-xs text-brand-green font-semibold flex items-center gap-1">
                            Read
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
<div class="s-divider"></div>
@endif

{{-- ════════════════════════════════════════════
     NEWSLETTER
════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 px-4">
    <div class="container-custom">
        <div class="nl-box rounded-3xl p-7 sm:p-14 text-center max-w-2xl mx-auto relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-56 h-24 pointer-events-none"
                 style="background: radial-gradient(ellipse, rgba(184,243,151,0.15) 0%, transparent 70%); filter: blur(20px);"></div>
            <div class="relative">
                <div class="w-14 h-14 rounded-2xl mx-auto mb-5 flex items-center justify-center text-2xl"
                     style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.2);">🌿</div>
                <h2 class="font-display font-black text-2xl sm:text-4xl text-content-primary mb-3 leading-tight">
                    Get Farm Fresh<br class="sm:hidden"> Updates
                </h2>
                <p class="text-content-secondary text-sm sm:text-base mb-7 max-w-sm mx-auto">Harvest alerts, exclusive discounts & farming tips. No spam, ever.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST"
                      class="flex flex-col xs:flex-row gap-2.5 max-w-sm mx-auto">
                    @csrf
                    <input type="email" name="email" placeholder="your@email.com" required
                           class="input flex-1 text-sm py-3">
                    <button type="submit" class="btn-primary whitespace-nowrap text-sm py-3 flex-shrink-0">
                        Subscribe
                    </button>
                </form>
                @if(session('success') && str_contains(session('success'), 'subscribed'))
                <p class="text-brand-green text-sm mt-4 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </p>
                @endif
                <p class="text-content-muted text-xs mt-4">Unsubscribe anytime. Privacy protected.</p>
            </div>
        </div>
    </div>
</section>

@endsection
