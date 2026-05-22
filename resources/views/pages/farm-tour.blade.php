@extends('layouts.app')

@section('title', 'Book a Farm Tour - T-Akomz Agro Estates')

@section('content')

{{-- Hero --}}
<div class="relative bg-surface-card border-b border-surface-border overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-surface-bg/90 to-transparent z-10"></div>
    <div class="absolute inset-0 bg-surface-elevated"></div>
    <div class="container-custom py-20 relative z-20">
        <p class="text-brand-green text-sm font-semibold tracking-widest uppercase mb-3">Farm Experience</p>
        <h1 class="font-display text-4xl md:text-6xl font-bold text-content-primary mb-5">
            Visit Our <span class="text-gradient">Living Farm</span>
        </h1>
        <p class="text-content-secondary text-lg max-w-xl mb-8">
            See how your food is grown and raised. From hatchery to harvest — get a behind-the-scenes look at one of Nigeria's most modern integrated farms.
        </p>
        <a href="#book-tour" class="btn-primary px-8 py-4 text-base">Book Your Tour</a>
    </div>
</div>

<div class="container-custom py-16">

    {{-- Highlights --}}
    <div class="text-center mb-12">
        <h2 class="font-display text-3xl font-bold text-content-primary mb-2">What to Expect</h2>
        <p class="text-content-muted">A guided experience covering every aspect of our farm operations.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-20">
        @foreach([
            ['🐔', 'Poultry Unit', 'See 5,000+ birds in our climate-controlled broiler and layer houses. Learn about feed management and disease prevention.'],
            ['🥚', 'Egg Processing', 'Visit our grading, washing, and packaging facility. Learn how we maintain freshness from collection to delivery.'],
            ['🐄', 'Livestock Section', 'Meet our cattle, goats, and pigs. Learn about our animal welfare practices and grass-fed management.'],
            ['🌱', 'Organic Gardens', 'Walk through our vegetable gardens. See how we grow without synthetic chemicals using compost and natural pest control.'],
        ] as [$icon, $title, $desc])
        <div class="card p-5 text-center">
            <div class="text-4xl mb-3">{{ $icon }}</div>
            <h3 class="font-display font-semibold text-content-primary mb-2">{{ $title }}</h3>
            <p class="text-content-muted text-sm leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>

    {{-- Tour Packages --}}
    <div class="text-center mb-10">
        <h2 class="font-display text-3xl font-bold text-content-primary mb-2">Tour Packages</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
        @foreach([
            ['Individual', '₦5,000', 'Per person', ['2-hour guided tour', 'Welcome fresh juice', 'Farm produce gift pack', 'Photo opportunities'], false],
            ['Group (10+)', '₦3,500', 'Per person', ['3-hour guided tour', 'Fresh lunch included', 'Farm produce gift pack', 'Educational handouts', 'Q&A with our team'], true],
            ['Corporate/School', 'Custom', 'Contact us', ['Full-day programme', 'All meals included', 'CSR certificate', 'Custom educational content', 'Farm-to-table dinner option'], false],
        ] as [$name, $price, $per, $features, $popular])
        <div class="card p-6 {{ $popular ? 'border-brand-green/50 relative' : '' }}">
            @if($popular)
            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                <span class="badge-green text-xs px-3 py-1">Most Popular</span>
            </div>
            @endif
            <h3 class="font-display font-bold text-content-primary text-xl mb-1">{{ $name }}</h3>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="font-display font-bold text-brand-green text-3xl">{{ $price }}</span>
                <span class="text-content-muted text-sm">{{ $per }}</span>
            </div>
            <ul class="space-y-2 mb-6">
                @foreach($features as $feature)
                <li class="flex items-center gap-2 text-sm text-content-secondary">
                    <span class="text-brand-green">✓</span> {{ $feature }}
                </li>
                @endforeach
            </ul>
            <a href="#book-tour" class="{{ $popular ? 'btn-primary' : 'btn-outline' }} w-full py-3 text-sm text-center block">Book Now</a>
        </div>
        @endforeach
    </div>

    {{-- Booking Form --}}
    <div id="book-tour" class="max-w-xl mx-auto">
        <div class="card p-8">
            <h2 class="font-display font-bold text-content-primary text-2xl mb-1">Book Your Farm Tour</h2>
            <p class="text-content-muted text-sm mb-6">Pay securely via Paystack. You will be redirected to complete payment and receive a confirmation email.</p>

            @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm mb-5">{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm mb-5">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('farm-tour.book') }}" method="POST" class="space-y-4"
                  x-data="{
                      pkg: '{{ old('package', 'individual') }}',
                      persons: {{ (int) old('group_size', 1) }},
                      get price() { return this.pkg === 'group' ? 3500 : 5000; },
                      get total() { return this.price * (this.persons || 1); },
                      get totalFormatted() { return '₦' + this.total.toLocaleString(); }
                  }">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input text-sm" required>
                    </div>
                    <div>
                        <label class="label">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="input text-sm" required placeholder="080XXXXXXXX">
                    </div>
                </div>

                <div>
                    <label class="label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input text-sm" required>
                </div>

                <div>
                    <label class="label">Package</label>
                    <select name="package" x-model="pkg" class="input text-sm">
                        <option value="individual">Individual — ₦5,000 per person</option>
                        <option value="group">Group (10+) — ₦3,500 per person</option>
                        <option value="corporate">Corporate / School — Contact us</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Number of Persons</label>
                        <input type="number" name="group_size" x-model.number="persons" min="1" max="500"
                               class="input text-sm" value="{{ old('group_size', 1) }}" required>
                    </div>
                    <div>
                        <label class="label">Preferred Date</label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                               min="{{ date('Y-m-d', strtotime('+3 days')) }}" class="input text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="label">Additional Notes <span class="text-content-muted font-normal">(optional)</span></label>
                    <textarea name="notes" rows="2" class="input resize-none text-sm" placeholder="Special requirements, dietary needs, etc.">{{ old('notes') }}</textarea>
                </div>

                {{-- Live total — always visible for non-corporate, Alpine controls display --}}
                <div x-show="pkg !== 'corporate'"
                     style="display: block; background: rgba(184,243,151,0.06); border: 1px solid rgba(184,243,151,0.15);"
                     class="rounded-xl px-4 py-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm text-content-secondary">Per person rate:</span>
                        <span class="text-sm text-content-secondary font-medium">₦<span x-text="price.toLocaleString()"></span></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-content-primary">Total to pay:</span>
                        <span class="font-display font-black text-brand-green text-2xl" x-text="totalFormatted"></span>
                    </div>
                </div>

                <button type="submit" x-show="pkg !== 'corporate'"
                        style="display: flex;"
                        class="btn-primary w-full py-3.5 items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pay &amp; Confirm Booking
                </button>
                <a x-show="pkg === 'corporate'" x-cloak href="{{ route('contact') }}"
                   class="btn-outline w-full py-3.5 text-center block">Contact Us for Corporate Pricing</a>
            </form>
        </div>
    </div>
</div>
@endsection
