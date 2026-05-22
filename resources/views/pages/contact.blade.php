@extends('layouts.app')

@section('title', 'Contact Us - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-12">
        <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary">Get In Touch</h1>
        <p class="text-content-muted mt-2">We'd love to hear from you. Our team responds within 2–4 hours.</p>
    </div>
</div>

<div class="container-custom py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        {{-- Contact Info --}}
        <div class="space-y-6">
            <div>
                <h2 class="font-display text-2xl font-bold text-content-primary mb-4">Contact Information</h2>
                <div class="space-y-4">
                    @foreach([
                        ['📞', 'Phone', \App\Models\SiteSetting::get('contact_phone') . (\App\Models\SiteSetting::get('contact_phone_2') ? ' / ' . \App\Models\SiteSetting::get('contact_phone_2') : ''), ''],
                        ['✉️', 'Email', \App\Models\SiteSetting::get('contact_email', 'info@takomzagro.com'), ''],
                        ['📍', 'Address', \App\Models\SiteSetting::get('contact_address', 'Benue State, Nigeria'), ''],
                        ['💬', 'WhatsApp', \App\Models\SiteSetting::get('whatsapp_number'), 'whatsapp'],
                    ] as [$icon, $label, $value, $type])
                    <div class="flex gap-4">
                        <span class="text-2xl flex-shrink-0">{{ $icon }}</span>
                        <div>
                            <p class="text-xs text-content-muted">{{ $label }}</p>
                            @if($type === 'whatsapp')
                            <a href="https://wa.me/{{ $value }}" target="_blank" rel="noopener noreferrer"  class="text-brand-green hover:underline text-sm">{{ $value }}</a>
                            @else
                            <p class="text-content-primary text-sm">{{ $value }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Business Hours --}}
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary mb-3">Business Hours</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-content-muted">Monday – Friday</span>
                        <span class="text-content-primary">8:00 AM – 6:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-content-muted">Saturday</span>
                        <span class="text-content-primary">8:00 AM – 4:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-content-muted">Sunday</span>
                        <span class="text-content-muted">Closed</span>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            @if(\App\Models\SiteSetting::get('google_maps_embed'))
            <div class="rounded-2xl overflow-hidden h-48 bg-surface-elevated">
                <iframe src="{{ \App\Models\SiteSetting::get('google_maps_embed') }}" width="100%" height="100%" frameborder="0" loading="lazy"></iframe>
            </div>
            @endif
        </div>

        {{-- Form --}}
        <div class="card p-6">
            <h2 class="font-semibold text-content-primary mb-5">Send a Message</h2>
            @if(session('success'))
            <div class="p-4 bg-brand-green/10 border border-brand-green/30 rounded-xl text-brand-green text-sm mb-5">
                {{ session('success') }}
            </div>
            @endif
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="input @error('name') border-red-500 @enderror" required>
                        @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="input">
                    </div>
                </div>
                <div>
                    <label class="label">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="input @error('email') border-red-500 @enderror" required>
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Subject <span class="text-red-400">*</span></label>
                    <select name="subject" class="input @error('subject') border-red-500 @enderror" required>
                        <option value="">Select a subject...</option>
                        @foreach(['Order Enquiry', 'Wholesale / Bulk Purchase', 'Farm Tour Request', 'Partnership / Export', 'Complaint / Feedback', 'General Enquiry'] as $sub)
                        <option value="{{ $sub }}" {{ old('subject') === $sub ? 'selected' : '' }}>{{ $sub }}</option>
                        @endforeach
                    </select>
                    @error('subject')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Message <span class="text-red-400">*</span></label>
                    <textarea name="message" rows="5" class="input resize-none @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full py-3.5">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
