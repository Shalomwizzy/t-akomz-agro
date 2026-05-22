@extends('layouts.admin')

@section('page-title', 'Site Settings')

@section('content')
<div class="max-w-6xl">

    @php
    function settingsForm($section) {
        return 'action="' . route('admin.settings.update') . '" method="POST"';
    }
    @endphp

    <div class="flex gap-6 items-start">

        {{-- ── Sticky sidebar nav ─────────────────────────────────────── --}}
        <aside class="hidden lg:block w-52 flex-shrink-0 sticky top-24">
            <nav class="card p-3 space-y-0.5 text-sm">
                @foreach([
                    ['#business',      '🏢', 'Business'],
                    ['#delivery',      '🚚', 'Delivery'],
                    ['#payments',      '💳', 'Payments'],
                    ['#bank-transfer', '🏦', 'Bank Transfer'],
                    ['#announcements', '📢', 'Announcements'],
                    ['#social',        '📱', 'Social Media'],
                    ['#images',        '🖼',  'Images'],
                    ['#about-images',  '📸',  'About Photos'],
                    ['#gallery',       '🎨',  'Gallery'],
                    ['#location',      '🗺',  'Location'],
                ] as [$href, $icon, $label])
                <a href="{{ $href }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-content-muted hover:text-content-primary hover:bg-surface-elevated transition-colors">
                    <span>{{ $icon }}</span>
                    <span>{{ $label }}</span>
                </a>
                @endforeach
            </nav>
        </aside>

        {{-- ── Sections ────────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-6">

            {{-- ─── Business / Contact ─────────────────────────── --}}
            <section id="business" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="business">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🏢</span> Business Information
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Primary Phone</label>
                            <input type="tel" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Secondary Phone</label>
                            <input type="tel" name="contact_phone_2" value="{{ $settings['contact_phone_2'] ?? '' }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Email Address</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">WhatsApp Number</label>
                            <input type="tel" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="input text-sm" placeholder="+2348012345678">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label">Business Address</label>
                            <textarea name="contact_address" rows="2" class="input resize-none text-sm">{{ $settings['contact_address'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Delivery ────────────────────────────────────── --}}
            <section id="delivery" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="delivery">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🚚</span> Delivery Fees
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="label">Standard (₦)</label>
                            <input type="number" name="delivery_fee_standard" value="{{ $settings['delivery_fee_standard'] ?? '' }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">2–5 days</p>
                        </div>
                        <div>
                            <label class="label">Express (₦)</label>
                            <input type="number" name="delivery_fee_express" value="{{ $settings['delivery_fee_express'] ?? '' }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">1–2 days</p>
                        </div>
                        <div>
                            <label class="label">Pickup Fee (₦)</label>
                            <input type="number" name="delivery_fee_pickup" value="{{ $settings['delivery_fee_pickup'] ?? '' }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">0 = free</p>
                        </div>
                        <div>
                            <label class="label">Min Order (₦)</label>
                            <input type="number" name="min_order_amount" value="{{ $settings['min_order_amount'] ?? '' }}" class="input text-sm">
                            <p class="text-xs text-content-muted mt-1">Block below this</p>
                        </div>
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Payments ────────────────────────────────────── --}}
            <section id="payments" class="scroll-mt-24"
                     x-data="{ gateway: '{{ $settings['active_payment_gateway'] ?? 'paystack' }}', showPaystackKeys: false, showFlwKeys: false }">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="payments">
                <input type="hidden" name="active_payment_gateway" :value="gateway">

                <div class="card p-6">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">💳</span> Online Payment Gateways
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <p class="text-xs text-content-muted mb-5">Choose which gateway is active at checkout. API keys entered here override .env values.</p>

                    {{-- Gateway selector --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                        @foreach([
                            ['paystack',    'Paystack',    'Nigeria\'s leading processor. Cards, USSD, bank.'],
                            ['flutterwave', 'Flutterwave', 'Pan-African gateway. Cards, mobile money.'],
                            ['both',        'Both',        'Let customer choose between gateways.'],
                        ] as [$val, $name, $desc])
                        <button type="button" @click="gateway = '{{ $val }}'"
                                :class="gateway === '{{ $val }}' ? 'border-brand-green bg-brand-green/10 ring-1 ring-brand-green' : 'border-surface-border hover:border-brand-green/30'"
                                class="relative text-left rounded-xl border p-4 transition-all">
                            <p class="text-sm font-semibold text-content-primary mb-1">{{ $name }}</p>
                            <p class="text-xs text-content-muted leading-snug">{{ $desc }}</p>
                            <span x-show="gateway === '{{ $val }}'"
                                  class="absolute top-2.5 right-2.5 w-4 h-4 bg-brand-green rounded-full flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 text-surface-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                        </button>
                        @endforeach
                    </div>

                    {{-- Paystack Keys --}}
                    <div class="rounded-xl border border-surface-border overflow-hidden mb-3">
                        <button type="button" @click="showPaystackKeys = !showPaystackKeys"
                                class="w-full flex items-center justify-between px-4 py-3 bg-surface-elevated hover:bg-surface-elevated/80 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-[#00C3F7]/15 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#00C3F7] text-xs font-bold">P</span>
                                </div>
                                <span class="text-sm font-medium text-content-primary">Paystack API Keys</span>
                                @if(!empty($settings['paystack_secret_key']))
                                <span class="text-xs bg-brand-green/15 text-brand-green px-2 py-0.5 rounded-full">Configured</span>
                                @else
                                <span class="text-xs bg-yellow-500/15 text-yellow-400 px-2 py-0.5 rounded-full">Not set</span>
                                @endif
                            </div>
                            <svg class="w-4 h-4 text-content-muted transition-transform duration-200" :class="showPaystackKeys ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="showPaystackKeys" x-cloak class="p-4 space-y-4 border-t border-surface-border">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Public Key</label>
                                    <input type="text" name="paystack_public_key"
                                           value="{{ $settings['paystack_public_key'] ?? '' }}"
                                           placeholder="pk_live_..." class="input text-sm font-mono">
                                    <p class="text-xs text-content-muted mt-1">Safe to expose — used on frontend</p>
                                </div>
                                <div>
                                    <label class="label">Secret Key</label>
                                    <input type="text" name="paystack_secret_key"
                                           value="{{ $settings['paystack_secret_key'] ?? '' }}"
                                           placeholder="sk_live_..." class="input text-sm font-mono">
                                    <p class="text-xs text-content-muted mt-1">Keep private — server-side only</p>
                                </div>
                            </div>
                            <p class="text-xs text-content-muted bg-surface-card rounded-lg px-3 py-2 border border-surface-border">
                                Get keys at <strong class="text-content-secondary">dashboard.paystack.com</strong> → Settings → API Keys &amp; Webhooks.
                            </p>
                        </div>
                    </div>

                    {{-- Flutterwave Keys --}}
                    <div class="rounded-xl border border-surface-border overflow-hidden">
                        <button type="button" @click="showFlwKeys = !showFlwKeys"
                                class="w-full flex items-center justify-between px-4 py-3 bg-surface-elevated hover:bg-surface-elevated/80 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-[#F5A623]/15 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[#F5A623] text-xs font-bold">F</span>
                                </div>
                                <span class="text-sm font-medium text-content-primary">Flutterwave API Keys</span>
                                @if(!empty($settings['flutterwave_secret_key']))
                                <span class="text-xs bg-brand-green/15 text-brand-green px-2 py-0.5 rounded-full">Configured</span>
                                @else
                                <span class="text-xs bg-yellow-500/15 text-yellow-400 px-2 py-0.5 rounded-full">Not set</span>
                                @endif
                            </div>
                            <svg class="w-4 h-4 text-content-muted transition-transform duration-200" :class="showFlwKeys ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="showFlwKeys" x-cloak class="p-4 space-y-4 border-t border-surface-border">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">Public Key</label>
                                    <input type="text" name="flutterwave_public_key"
                                           value="{{ $settings['flutterwave_public_key'] ?? '' }}"
                                           placeholder="FLWPUBK_TEST-..." class="input text-sm font-mono">
                                </div>
                                <div>
                                    <label class="label">Secret Key</label>
                                    <input type="text" name="flutterwave_secret_key"
                                           value="{{ $settings['flutterwave_secret_key'] ?? '' }}"
                                           placeholder="FLWSECK_TEST-..." class="input text-sm font-mono">
                                </div>
                                <div>
                                    <label class="label">Encryption Key</label>
                                    <input type="text" name="flutterwave_secret_hash"
                                           value="{{ $settings['flutterwave_secret_hash'] ?? '' }}"
                                           placeholder="Encryption key" class="input text-sm font-mono">
                                </div>
                            </div>
                            <p class="text-xs text-content-muted bg-surface-card rounded-lg px-3 py-2 border border-surface-border">
                                Get keys at <strong class="text-content-secondary">dashboard.flutterwave.com</strong> → Settings → API.
                            </p>
                        </div>
                    </div>

                </div>
            </form>
            </section>

            {{-- ─── Bank Transfer & COD ─────────────────────────── --}}
            <section id="bank-transfer" class="scroll-mt-24"
                     x-data="{
                         btOpen: {{ ($settings['bank_transfer_enabled'] ?? '0') === '1' ? 'true' : 'false' }},
                         codOpen: {{ ($settings['cod_enabled'] ?? '0') === '1' ? 'true' : 'false' }}
                     }">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="bank-transfer">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🏦</span> Offline Payment Methods
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>

                    {{-- Bank Transfer --}}
                    <div class="rounded-xl border border-surface-border overflow-hidden mb-3">
                        <label class="flex items-center justify-between px-4 py-3 bg-surface-elevated cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🏦</span>
                                <div>
                                    <p class="text-sm font-medium text-content-primary">Bank Transfer</p>
                                    <p class="text-xs text-content-muted">Customer pays via transfer and sends proof — you confirm manually</p>
                                </div>
                            </div>
                            <input type="checkbox" name="bank_transfer_enabled" value="1"
                                   x-model="btOpen"
                                   class="w-4 h-4 accent-green-500 flex-shrink-0"
                                   {{ ($settings['bank_transfer_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        </label>
                        <div x-show="btOpen" x-cloak class="p-4 border-t border-surface-border">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="label">Bank Name</label>
                                    <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}"
                                           placeholder="e.g. GTBank" class="input text-sm">
                                </div>
                                <div>
                                    <label class="label">Account Number</label>
                                    <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] ?? '' }}"
                                           placeholder="0123456789" class="input text-sm font-mono">
                                </div>
                                <div>
                                    <label class="label">Account Name</label>
                                    <input type="text" name="bank_account_name" value="{{ $settings['bank_account_name'] ?? '' }}"
                                           placeholder="T-Akomz Agro Estates Ltd" class="input text-sm">
                                </div>
                            </div>
                            <p class="text-xs text-content-muted mt-3">These details are shown to the customer at checkout when they select Bank Transfer.</p>
                        </div>
                    </div>

                    {{-- Cash on Delivery --}}
                    <div class="rounded-xl border border-surface-border overflow-hidden">
                        <label class="flex items-center justify-between px-4 py-3 bg-surface-elevated cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">💵</span>
                                <div>
                                    <p class="text-sm font-medium text-content-primary">Cash on Delivery</p>
                                    <p class="text-xs text-content-muted">Customer pays cash when order arrives at their door</p>
                                </div>
                            </div>
                            <input type="checkbox" name="cod_enabled" value="1"
                                   x-model="codOpen"
                                   class="w-4 h-4 accent-green-500 flex-shrink-0"
                                   {{ ($settings['cod_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        </label>
                        <div x-show="codOpen" x-cloak class="p-4 border-t border-surface-border">
                            <label class="label">Allowed States <span class="font-normal text-content-muted">(comma-separated, blank = all states)</span></label>
                            <textarea name="cod_allowed_states" rows="2" class="input resize-none text-sm"
                                      placeholder="Lagos, FCT (Abuja), Ekiti">{{ $settings['cod_allowed_states'] ?? '' }}</textarea>
                        </div>
                    </div>

                </div>
            </form>
            </section>

            {{-- ─── Announcements ───────────────────────────────── --}}
            <section id="announcements" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="announcements">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">📢</span> Announcements &amp; Site Mode
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="label">Banner Text</label>
                            <input type="text" name="banner_text" value="{{ $settings['banner_text'] ?? '' }}"
                                   placeholder="Free delivery on orders above ₦15,000 🚚" class="input text-sm">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-surface-border hover:border-brand-green/30 cursor-pointer transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-content-primary">Show Announcement Banner</p>
                                    <p class="text-xs text-content-muted mt-0.5">Display the banner at the top of every page</p>
                                </div>
                                <input type="checkbox" name="banner_active" value="1" class="w-4 h-4 accent-green-500 flex-shrink-0"
                                       {{ ($settings['banner_active'] ?? '0') === '1' ? 'checked' : '' }}>
                            </label>
                            <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-red-500/20 hover:border-red-500/40 cursor-pointer transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-red-400">Maintenance Mode</p>
                                    <p class="text-xs text-content-muted mt-0.5">Show a maintenance page to all visitors</p>
                                </div>
                                <input type="checkbox" name="maintenance_mode" value="1" class="w-4 h-4 accent-red-500 flex-shrink-0"
                                       {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Social Media ────────────────────────────────── --}}
            <section id="social" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="social">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">📱</span> Social Media
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            ['social_instagram', 'Instagram', 'https://instagram.com/...'],
                            ['social_facebook',  'Facebook',  'https://facebook.com/...'],
                            ['social_twitter',   'Twitter/X', 'https://x.com/...'],
                            ['social_youtube',   'YouTube',   'https://youtube.com/...'],
                        ] as [$key, $label, $placeholder])
                        <div>
                            <label class="label">{{ $label }}</label>
                            <input type="url" name="{{ $key }}" value="{{ $settings[$key] ?? '' }}"
                                   placeholder="{{ $placeholder }}" class="input text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Image Processing ────────────────────────────── --}}
            <section id="images" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="images">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🖼</span> Image Processing
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <p class="text-xs text-content-muted mb-5">Product images are auto-resized to 1200×1200px and converted to WebP. Optionally add a brand watermark.</p>
                    <div class="space-y-4">
                        <label class="flex items-center justify-between gap-4 p-4 rounded-xl border border-surface-border hover:border-brand-green/30 cursor-pointer transition-colors">
                            <div>
                                <p class="text-sm font-medium text-content-primary">Enable Watermark</p>
                                <p class="text-xs text-content-muted mt-0.5">Overlay your brand text on every uploaded product photo</p>
                            </div>
                            <input type="checkbox" name="image_watermark" value="1" class="w-4 h-4 accent-green-500 flex-shrink-0"
                                   {{ ($settings['image_watermark'] ?? '0') === '1' ? 'checked' : '' }}>
                        </label>
                        <div>
                            <label class="label">Watermark Text</label>
                            <input type="text" name="watermark_text" value="{{ $settings['watermark_text'] ?? 'T-AKOMZ AGRO' }}"
                                   placeholder="T-AKOMZ AGRO" class="input text-sm">
                        </div>
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── About Page Images ───────────────────────────── --}}
            <section id="about-images" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_section" value="about-images">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">📸</span> About Page Photo
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <p class="text-xs text-content-muted mb-5">This photo appears on the About Us page next to the "Who We Are" text.</p>
                    <div>
                        <label class="label">Farm Photo (JPG/PNG — max 8MB)</label>
                        @if(!empty($settings['about_image_1']))
                        <div class="mb-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['about_image_1']) }}"
                                 alt="About farm photo" class="w-40 h-40 object-cover rounded-xl border border-surface-border">
                            <p class="text-xs text-content-muted mt-1">Current photo — upload a new one to replace it.</p>
                        </div>
                        @endif
                        <input type="file" name="about_image_1_file" accept="image/*"
                               class="input text-sm file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-brand-green/20 file:text-brand-green file:font-medium cursor-pointer">
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Gallery ─────────────────────────────────────── --}}
            <section id="gallery" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="gallery">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🎨</span> Gallery Page
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <p class="text-xs text-content-muted mb-5">Configure the public gallery page text and call-to-action.</p>
                    <div class="space-y-4">
                        <div>
                            <label class="label">Gallery Page Title</label>
                            <input type="text" name="gallery_title" value="{{ $settings['gallery_title'] ?? 'Our Farm in Pictures' }}" class="input text-sm" placeholder="Our Farm in Pictures">
                        </div>
                        <div>
                            <label class="label">Gallery Subtitle</label>
                            <input type="text" name="gallery_subtitle" value="{{ $settings['gallery_subtitle'] ?? 'A visual journey through our fields, livestock, and people.' }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Instagram CTA Text</label>
                            <input type="text" name="gallery_instagram_cta" value="{{ $settings['gallery_instagram_cta'] ?? 'Follow us on Instagram for daily farm updates' }}" class="input text-sm">
                        </div>
                        <div class="pt-2 border-t border-surface-border">
                            <a href="{{ route('admin.gallery.index') }}"
                               class="inline-flex items-center gap-2 btn-outline px-4 py-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Manage Gallery Images →
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            </section>

            {{-- ─── Location ────────────────────────────────────── --}}
            <section id="location" class="scroll-mt-24">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="_section" value="location">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-content-primary text-base flex items-center gap-2">
                            <span class="text-lg">🗺</span> Google Maps Embed
                        </h2>
                        <button type="submit" class="btn-primary py-1.5 px-4 text-xs">Save</button>
                    </div>
                    <div>
                        <label class="label">Embed URL</label>
                        <input type="url" name="google_maps_embed" value="{{ $settings['google_maps_embed'] ?? '' }}"
                               placeholder="https://maps.google.com/maps?..." class="input text-sm">
                        <p class="text-xs text-content-muted mt-1">Google Maps → Share → Embed a map → copy the src URL from the iframe.</p>
                    </div>
                </div>
            </form>
            </section>

        </div>{{-- end main --}}
    </div>{{-- end flex --}}
</div>
@endsection
