@extends('layouts.app')

@section('title', 'Checkout - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-3">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-brand-green transition-colors">Cart</a>
            <span>/</span>
            <span class="text-content-primary">Checkout</span>
        </nav>
        <h1 class="font-display text-3xl font-bold text-content-primary">Checkout</h1>
    </div>
</div>

<div class="container-custom py-8">
    @php
        $cartItems = session('cart', []);
        $products  = [];
        $subtotal  = 0;

        if (!empty($cartItems)) {
            $pids = array_keys($cartItems);
            $dbProducts = \App\Models\Product::whereIn('id', $pids)->with('images')->get()->keyBy('id');
            foreach ($cartItems as $pid => $qty) {
                if (isset($dbProducts[$pid])) {
                    $p = $dbProducts[$pid];
                    $lineTotal = $p->price * $qty;
                    $subtotal += $lineTotal;
                    $products[] = ['product' => $p, 'qty' => $qty, 'line_total' => $lineTotal];
                }
            }
        }

        $couponDiscount = session('discount', 0);
        $deliveryFeeStd = \App\Models\SiteSetting::get('delivery_fee_standard', 2500);
        $deliveryFeeExp = \App\Models\SiteSetting::get('delivery_fee_express', 5000);

        $states = ['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','FCT (Abuja)','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara'];
    @endphp

    <div class="flex flex-col lg:flex-row gap-10"
         x-data="{
             delivery: 'standard',
             deliveryFeeStd: {{ $deliveryFeeStd }},
             deliveryFeeExp: {{ $deliveryFeeExp }},
             subtotal: {{ $subtotal }},
             discount: {{ $couponDiscount }},
             get deliveryFee() {
                 if (this.delivery === 'express') return this.deliveryFeeExp;
                 if (this.delivery === 'pickup') return 0;
                 return this.deliveryFeeStd;
             },
             get total() { return Math.max(0, this.subtotal - this.discount) + this.deliveryFee; }
         }">

        {{-- ─── FORM ──────────────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf

                {{-- Contact Info --}}
                <div class="card p-6 mb-6">
                    <h2 class="font-display font-semibold text-content-primary text-lg mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-brand-green/20 text-brand-green text-xs flex items-center justify-center font-bold">1</span>
                        Contact Information
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', auth()->user()?->name ? explode(' ', auth()->user()->name)[0] : '') }}"
                                   class="input @error('first_name') border-red-500 @enderror" required>
                            @error('first_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', auth()->user()?->name ? (explode(' ', auth()->user()->name)[1] ?? '') : '') }}"
                                   class="input @error('last_name') border-red-500 @enderror" required>
                            @error('last_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                                   class="input @error('email') border-red-500 @enderror" required>
                            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone) }}"
                                   placeholder="+234 800 000 0000"
                                   class="input @error('phone') border-red-500 @enderror" required>
                            @error('phone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @guest
                    <p class="text-xs text-content-muted mt-4">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-brand-green hover:underline">Sign in</a>
                        to auto-fill your details.
                    </p>
                    @endguest
                </div>

                {{-- Delivery --}}
                <div class="card p-6 mb-6">
                    <h2 class="font-display font-semibold text-content-primary text-lg mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-brand-green/20 text-brand-green text-xs flex items-center justify-center font-bold">2</span>
                        Delivery Details
                    </h2>

                    {{-- Delivery Type --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                        @foreach([
                            ['standard', '🚚', 'Standard', '2–5 days', '$deliveryFeeStd'],
                            ['express', '⚡', 'Express', '1–2 days', '$deliveryFeeExp'],
                            ['pickup', '🏪', 'Farm Pickup', 'Self-collect', 'Free'],
                        ] as [$val, $icon, $label, $time, $fee])
                        <label :class="delivery === '{{ $val }}' ? 'border-brand-green bg-brand-green/5' : 'border-surface-border'"
                               class="cursor-pointer border rounded-xl p-4 transition-all">
                            <input type="radio" name="delivery_type" value="{{ $val }}" x-model="delivery" class="sr-only">
                            <div class="text-xl mb-1">{{ $icon }}</div>
                            <div class="font-medium text-sm text-content-primary">{{ $label }}</div>
                            <div class="text-xs text-content-muted">{{ $time }}</div>
                            @if($val === 'pickup')
                            <div class="text-xs text-brand-green font-semibold mt-1">Free</div>
                            @else
                            <div class="text-xs text-brand-green font-semibold mt-1">₦<span x-text="delivery === '{{ $val }}' ? new Intl.NumberFormat().format({{ $val === 'express' ? '$deliveryFeeExp' : '$deliveryFeeStd' }}) : ''"></span></div>
                            @endif
                        </label>
                        @endforeach
                    </div>

                    {{-- Address (hidden for pickup) --}}
                    <div x-show="delivery !== 'pickup'" x-transition>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="label">Street Address</label>
                                <input type="text" name="street" value="{{ old('street') }}"
                                       class="input @error('street') border-red-500 @enderror"
                                       :required="delivery !== 'pickup'">
                                @error('street')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label">City / LGA</label>
                                    <input type="text" name="city" value="{{ old('city') }}"
                                           class="input @error('city') border-red-500 @enderror"
                                           :required="delivery !== 'pickup'">
                                    @error('city')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="label">State</label>
                                    <select name="state" class="input @error('state') border-red-500 @enderror"
                                            :required="delivery !== 'pickup'">
                                        <option value="">Select state...</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="label">Delivery Notes <span class="text-content-muted font-normal">(optional)</span></label>
                                <textarea name="notes" rows="2" placeholder="Gate code, landmarks, special instructions..."
                                          class="input resize-none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="delivery === 'pickup'" x-transition class="mt-4 p-4 bg-surface-elevated rounded-xl">
                        <p class="text-sm text-content-secondary">
                            <strong class="text-content-primary">Pickup Address:</strong> T-Akomz Agro Estates, Benue State, Nigeria.<br>
                            We'll contact you via phone to confirm your pickup slot.
                        </p>
                    </div>
                </div>

                {{-- Payment --}}
                @php
                    $activeGateway     = \App\Models\SiteSetting::get('active_payment_gateway', 'paystack');
                    $bankTransferOn    = \App\Models\SiteSetting::get('bank_transfer_enabled', '0') === '1';
                    $codOn             = \App\Models\SiteSetting::get('cod_enabled', '0') === '1';
                    $bankName          = \App\Models\SiteSetting::get('bank_name', 'Zenith Bank');
                    $bankAccNum        = \App\Models\SiteSetting::get('bank_account_number', '');
                    $bankAccName       = \App\Models\SiteSetting::get('bank_account_name', 'T-Akomz Agro Estates Ltd');

                    $paymentMethods = [];
                    if (in_array($activeGateway, ['paystack', 'both'])) {
                        $paymentMethods[] = ['paystack', '💳', 'Paystack', 'Cards, bank transfer, USSD, mobile money'];
                    }
                    if (in_array($activeGateway, ['flutterwave', 'both'])) {
                        $paymentMethods[] = ['flutterwave', '🌊', 'Flutterwave', 'Cards, mobile money, bank transfer'];
                    }
                    if ($bankTransferOn) {
                        $paymentMethods[] = ['bank_transfer', '🏦', 'Bank Transfer', 'Manual transfer — we confirm within 2 hours'];
                    }
                    if ($codOn) {
                        $paymentMethods[] = ['cod', '💵', 'Cash on Delivery', 'Pay cash when your order arrives'];
                    }
                    $defaultPayment = $paymentMethods[0][0] ?? 'paystack';
                @endphp
                <div class="card p-6 mb-6" x-data="{ payment: '{{ $defaultPayment }}' }">
                    <h2 class="font-display font-semibold text-content-primary text-lg mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-brand-green/20 text-brand-green text-xs flex items-center justify-center font-bold">3</span>
                        Payment Method
                    </h2>

                    <div class="space-y-3">
                        @foreach($paymentMethods as [$val, $icon, $label, $desc])
                        <label :class="payment === '{{ $val }}' ? 'border-brand-green bg-brand-green/5' : 'border-surface-border'"
                               class="cursor-pointer border rounded-xl p-4 transition-all flex items-center gap-4">
                            <input type="radio" name="payment_method" value="{{ $val }}" x-model="payment" class="accent-green-500">
                            <span class="text-xl">{{ $icon }}</span>
                            <div>
                                <div class="font-medium text-sm text-content-primary">{{ $label }}</div>
                                <div class="text-xs text-content-muted">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach

                        @if(empty($paymentMethods))
                        <p class="text-yellow-400 text-sm">No payment methods are currently enabled. Please contact us to complete your order.</p>
                        @endif
                    </div>

                    {{-- Bank details (shown when bank_transfer selected) --}}
                    @if($bankTransferOn)
                    <div x-show="payment === 'bank_transfer'" x-transition class="mt-4 p-4 bg-surface-elevated rounded-xl">
                        <p class="text-xs text-content-muted mb-2">Transfer to:</p>
                        <div class="space-y-1 text-sm">
                            <div class="flex gap-3"><span class="text-content-muted w-28">Bank:</span><span class="text-content-primary font-medium">{{ $bankName }}</span></div>
                            <div class="flex gap-3"><span class="text-content-muted w-28">Account:</span><span class="text-content-primary font-medium">{{ $bankAccNum ?: 'Contact us for details' }}</span></div>
                            <div class="flex gap-3"><span class="text-content-muted w-28">Name:</span><span class="text-content-primary font-medium">{{ $bankAccName }}</span></div>
                        </div>
                        <p class="text-xs text-yellow-400 mt-3">Send proof of payment to our WhatsApp after checkout. Orders confirmed within 2 hours.</p>
                    </div>
                    @endif
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-base">
                    Place Order — ₦<span x-text="new Intl.NumberFormat().format(total)"></span>
                </button>
                <p class="text-xs text-content-muted text-center mt-3">
                    By placing your order you agree to our
                    <a href="{{ route('terms') }}" class="text-brand-green hover:underline">Terms of Service</a>
                    and
                    <a href="{{ route('privacy') }}" class="text-brand-green hover:underline">Privacy Policy</a>.
                </p>
            </form>
        </div>

        {{-- ─── ORDER SUMMARY SIDEBAR ──────────────────────────────────────── --}}
        <div class="lg:w-80 flex-shrink-0 space-y-4">

            {{-- Coupon / Promo Code --}}
            @php
                $checkoutCoupon   = session('coupon');
                $checkoutDiscount = session('discount', 0);
                $checkoutCode     = $checkoutCoupon['code'] ?? '';
            @endphp
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-3">Promo Code</h3>
                @if($checkoutCode)
                <div class="flex items-center justify-between bg-brand-green/10 border border-brand-green/30 rounded-xl px-3 py-2.5 mb-2">
                    <span class="text-brand-green text-sm font-medium">{{ $checkoutCode }}</span>
                    <span class="text-brand-green text-sm">-₦{{ number_format($checkoutDiscount, 2) }}</span>
                </div>
                <form action="{{ route('coupon.remove') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-red-400 hover:underline">Remove coupon</button>
                </form>
                @else
                <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="Enter promo code"
                           value="{{ old('code') }}"
                           class="input text-sm flex-1 py-2.5 px-3">
                    <button type="submit" class="btn-outline py-2.5 px-4 text-sm">Apply</button>
                </form>
                @if(session('error') && request()->is('checkout*'))
                <p class="text-red-400 text-xs mt-1">{{ session('error') }}</p>
                @endif
                @endif
            </div>

            <div class="card p-5 sticky top-24">
                <h3 class="font-semibold text-content-primary mb-4">Order Summary</h3>

                {{-- Items --}}
                <div class="space-y-3 mb-4">
                    @foreach($products as $item)
                    @php $p = $item['product']; @endphp
                    <div class="flex items-center gap-3">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}"
                                 class="w-12 h-12 object-cover rounded-lg">
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-surface-elevated border border-surface-border rounded-full text-xs flex items-center justify-center text-content-muted">{{ $item['qty'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-content-primary line-clamp-1">{{ $p->name }}</p>
                            <p class="text-xs text-content-muted">{{ $p->unit }}</p>
                        </div>
                        <span class="text-xs font-medium text-content-primary">₦{{ number_format($item['line_total'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-surface-border pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-content-muted">Subtotal</span>
                        <span class="text-content-primary">₦{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($couponDiscount > 0)
                    <div class="flex justify-between text-brand-green">
                        <span>Discount</span>
                        <span>-₦{{ number_format($couponDiscount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-content-muted">Delivery</span>
                        <span class="text-content-primary">₦<span x-text="new Intl.NumberFormat().format(deliveryFee)"></span></span>
                    </div>
                    <div class="border-t border-surface-border pt-3 flex justify-between font-bold">
                        <span class="text-content-primary">Total</span>
                        <span class="text-brand-green text-lg">₦<span x-text="new Intl.NumberFormat().format(total)"></span></span>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 text-xs text-content-muted">
                    <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    SSL-encrypted. Your data is safe.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
