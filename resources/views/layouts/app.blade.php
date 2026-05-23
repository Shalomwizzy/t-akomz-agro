<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth"
      x-data="themeManager()" x-init="init()" :class="light ? 'light' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // Views can set $seoTitle/$seoDescription via compact() in their controller.
        // Fallback: honour legacy @section('title') / @section('meta_description') stacks.
        $__seoTitle       = $seoTitle       ?? '';
        $__seoDescription = $seoDescription ?? 'Premium farm-fresh poultry, eggs, livestock and organic produce from T-Akomz Agro Estates. Delivered to your door.';
        $__seoImage       = $seoImage       ?? null;
        $__seoType        = $seoType        ?? 'website';
        $__seoNoindex     = $seoNoindex     ?? false;
    @endphp
    <x-seo
        :title="$__seoTitle"
        :description="$__seoDescription"
        :image="$__seoImage"
        :type="$__seoType"
        :noindex="$__seoNoindex"
    />

    <link rel="icon" href="{{ asset('images/icons/pwa-192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#B8F397">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Prevent flash of wrong theme --}}
    <script>if(localStorage.getItem('theme')==='light')document.documentElement.classList.add('light');</script>

    {{-- OneSignal Web Push --}}
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "{{ config('services.onesignal.app_id') }}",
                notifyButton: { enable: true },
                allowLocalhostAsSecureOrigin: true,
            });
        });
    </script>
    @stack('head')
</head>
@php $initialCartCount = session('cart') ? array_sum(session('cart')) : 0; @endphp
<body class="bg-surface-bg text-content-primary min-h-screen flex flex-col pb-16 lg:pb-0"
      x-data="{
          cartOpen: false,
          mobileMenuOpen: false,
          searchOpen: false,
          cartCount: {{ $initialCartCount }},
          toasts: [],
          toastId: 0,
          showToast(msg, type = 'success') {
              const id = ++this.toastId;
              this.toasts.push({ id, msg, type });
              setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 3500);
          }
      }"
      @cart-updated.window="cartCount = $event.detail.count"
      @toast.window="showToast($event.detail.message, $event.detail.type || 'success')">

    {{-- Announcement Banner --}}
    @php
        $banner = \App\Models\SiteSetting::get('banner_text');
        $bannerActive = \App\Models\SiteSetting::get('banner_active', '0');
    @endphp
    @if($bannerActive === '1' && $banner)
    <div class="bg-brand-green text-surface-bg text-center text-sm font-medium py-2 px-4">
        {{ $banner }}
    </div>
    @endif

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 bg-surface-bg/95 backdrop-blur-md border-b border-surface-border">
        <nav class="container-custom">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <x-logo class="h-16 w-auto" />
                    <div class="leading-tight">
                        <div class="font-display font-bold text-base text-brand-green tracking-wider">T-AKOMZ</div>
                        <div class="text-content-muted text-xs tracking-widest uppercase" style="font-size:9px">Agro Estates</div>
                    </div>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-surface-card {{ request()->routeIs('home') ? 'text-brand-green' : '' }}">Home</a>
                    <a href="{{ route('shop.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-surface-card {{ request()->routeIs('shop.*') ? 'text-brand-green' : '' }}">Shop</a>
                    <a href="{{ route('about') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-surface-card {{ request()->routeIs('about') ? 'text-brand-green' : '' }}">About</a>
                    <a href="{{ route('blog.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-surface-card {{ request()->routeIs('blog.*') ? 'text-brand-green' : '' }}">Blog</a>
                    <a href="{{ route('contact') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-surface-card {{ request()->routeIs('contact') ? 'text-brand-green' : '' }}">Contact</a>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-2">

                    {{-- Search --}}
                    <button @click="searchOpen = true" title="Search products"
                            class="p-2 rounded-lg hover:bg-surface-card text-content-muted hover:text-content-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    {{-- Theme Toggle --}}
                    <button @click="toggle()" title="Toggle light/dark mode"
                            class="p-2 rounded-lg hover:bg-surface-card text-content-muted hover:text-content-primary transition-colors">
                        {{-- Sun (show when dark) --}}
                        <svg x-show="!light" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{-- Moon (show when light) --}}
                        <svg x-show="light" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Wishlist (only show when logged in and has items) --}}
                    @auth
                    @php $wishlistCount = auth()->user()->wishlistItems()->count(); @endphp
                    @if($wishlistCount > 0)
                    <a href="{{ route('account.wishlist') }}" class="relative nav-link p-2 rounded-lg hover:bg-surface-card" title="My Wishlist">
                        <svg class="w-5 h-5 text-brand-green" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-brand-green text-surface-bg text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($wishlistCount, 9) }}</span>
                    </a>
                    @endif
                    @endauth

                    {{-- Cart --}}
                    <button @click="cartOpen = true" class="relative nav-link p-2 rounded-lg hover:bg-surface-card">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-show="cartCount > 0" x-text="cartCount > 99 ? '99+' : cartCount"
                              class="absolute -top-1 -right-1 w-5 h-5 bg-brand-green text-surface-bg text-xs font-bold rounded-full flex items-center justify-center"></span>
                    </button>

                    {{-- Account --}}
                    @auth
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 nav-link px-3 py-2 rounded-lg hover:bg-surface-card">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-6 h-6 rounded-full object-cover" alt="">
                            <span class="text-sm font-medium">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </button>
                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-52 bg-surface-elevated border border-surface-border rounded-xl shadow-card py-1 z-50">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-brand-green hover:bg-surface-card">Admin Panel</a>
                            <div class="border-t border-surface-border"></div>
                            @endif
                            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-card hover:text-content-primary">My Account</a>
                            <a href="{{ route('account.orders') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-card hover:text-content-primary">My Orders</a>
                            <div class="border-t border-surface-border"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-surface-card">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="hidden md:flex btn-outline py-2 px-4 text-xs">Login</a>
                    @endauth

                    {{-- Mobile menu toggle --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-surface-card text-content-secondary">
                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-surface-border py-4 space-y-1">
                <a href="{{ route('home') }}" class="block nav-link px-3 py-2.5 rounded-lg hover:bg-surface-card">Home</a>
                <a href="{{ route('shop.index') }}" class="block nav-link px-3 py-2.5 rounded-lg hover:bg-surface-card">Shop</a>
                <a href="{{ route('about') }}" class="block nav-link px-3 py-2.5 rounded-lg hover:bg-surface-card">About</a>
                <a href="{{ route('blog.index') }}" class="block nav-link px-3 py-2.5 rounded-lg hover:bg-surface-card">Blog</a>
                <a href="{{ route('contact') }}" class="block nav-link px-3 py-2.5 rounded-lg hover:bg-surface-card">Contact</a>
                <div class="pt-3 border-t border-surface-border flex gap-3">
                    @auth
                    <a href="{{ route('account.dashboard') }}" class="btn-outline flex-1 py-2 text-xs text-center">My Account</a>
                    @else
                    <a href="{{ route('login') }}" class="btn-outline flex-1 py-2 text-xs text-center">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary flex-1 py-2 text-xs text-center">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    {{-- Search Overlay --}}
    <div x-show="searchOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex flex-col items-center justify-start pt-20 px-4"
         style="background: rgba(5,5,5,0.92); backdrop-filter: blur(12px);"
         @keydown.escape.window="searchOpen = false">
        <div class="w-full max-w-2xl"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-content-muted pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" autofocus
                       placeholder="Search for products, categories…"
                       class="w-full pl-14 pr-16 py-4 text-lg rounded-2xl text-content-primary placeholder-content-muted focus:outline-none"
                       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);"
                       x-init="$watch('searchOpen', val => val && $nextTick(() => $el.focus()))">
                <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-brand-green text-surface-bg text-sm font-semibold px-4 py-2 rounded-xl hover:bg-brand-dark transition-colors">
                    Search
                </button>
            </form>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->limit(6)->get() as $cat)
                <a href="{{ route('shop.index') }}?category={{ $cat->slug }}" @click="searchOpen = false"
                   class="text-xs px-3 py-1.5 rounded-full border border-surface-border text-content-muted hover:border-brand-green hover:text-brand-green transition-colors">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        <button @click="searchOpen = false" class="absolute top-6 right-6 text-content-muted hover:text-content-primary p-2 rounded-xl hover:bg-white/5 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Cart Drawer --}}
    <div x-show="cartOpen" x-cloak class="fixed inset-0 z-50 flex" style="display:none">
        <div class="flex-1 bg-black/60 backdrop-blur-sm" @click="cartOpen = false"></div>
        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="w-full max-w-md bg-surface-card border-l border-surface-border flex flex-col overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-surface-border">
                <h2 class="font-display text-xl font-bold">Your Cart</h2>
                <button @click="cartOpen = false" class="p-1 text-content-muted hover:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 p-6">
                @include('components.cart-drawer-content')
            </div>
        </div>
    </div>

    {{-- Toast Notifications (AJAX + server flash) --}}
    <div class="fixed top-20 right-4 z-50 flex flex-col gap-2 pointer-events-none" style="max-width:22rem;">
        {{-- Server-side flash (auto-inject as toast on page load) --}}
        @if(session('success'))
        <div x-data x-init="
            $dispatch('toast', { message: @js(session('success')), type: 'success' });
        " class="hidden"></div>
        @endif
        @if(session('error'))
        <div x-data x-init="
            $dispatch('toast', { message: @js(session('error')), type: 'error' });
        " class="hidden"></div>
        @endif
        @if(session('info'))
        <div x-data x-init="
            $dispatch('toast', { message: @js(session('info')), type: 'info' });
        " class="hidden"></div>
        @endif
        @if(session('warning'))
        <div x-data x-init="
            $dispatch('toast', { message: @js(session('warning')), type: 'warning' });
        " class="hidden"></div>
        @endif

        {{-- Dynamic toast stack --}}
        <template x-for="t in toasts" :key="t.id">
            <div x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="{
                     'bg-brand-green/10 border-brand-green/30 text-brand-green': t.type === 'success',
                     'bg-red-500/10 border-red-500/30 text-red-400': t.type === 'error',
                     'bg-blue-500/10 border-blue-500/30 text-blue-400': t.type === 'info',
                     'bg-yellow-500/10 border-yellow-500/30 text-yellow-400': t.type === 'warning',
                 }"
                 class="pointer-events-auto border rounded-xl px-5 py-3 shadow-card text-sm font-medium flex items-start gap-3">
                <svg x-show="t.type === 'success'" class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="t.type === 'error'" class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <svg x-show="t.type === 'info' || t.type === 'warning'" class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="t.msg" class="flex-1"></span>
            </div>
        </template>
    </div>

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-surface-card border-t border-surface-border mt-auto">
        <div class="container-custom py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-4">
                        <x-logo class="h-14 w-auto" />
                        <div class="leading-tight">
                            <div class="font-display font-bold text-xl text-brand-green tracking-wider">T-AKOMZ</div>
                            <div class="text-content-muted text-xs tracking-widest uppercase">Agro Estates Ltd</div>
                        </div>
                    </a>
                    <p class="text-content-muted text-sm leading-relaxed mb-5">
                        Farm-fresh poultry, eggs, livestock & organic produce from our 50-acre estate. Delivered to your door.
                    </p>
                </div>
                {{-- Quick Links --}}
                <div>
                    <h4 class="font-semibold text-content-primary mb-4">Quick Links</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('about') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">About Us</a></li>
                        <li><a href="{{ route('farm-tour') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">Farm Tour</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">Contact</a></li>
                        <li><a href="{{ route('orders.track') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">Track Order</a></li>
                        <li><a href="{{ route('faq') }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">FAQ</a></li>
                    </ul>
                </div>
                {{-- Shop --}}
                <div>
                    <h4 class="font-semibold text-content-primary mb-4">Shop</h4>
                    <ul class="space-y-2.5">
                        @foreach(\App\Models\Category::active()->get() as $cat)
                        <li><a href="{{ route('shop.category', $cat->slug) }}" class="text-content-muted hover:text-brand-green text-sm transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold text-content-primary mb-4">Get in Touch</h4>
                    @php
                        $phone    = \App\Models\SiteSetting::get('contact_phone');
                        $email    = \App\Models\SiteSetting::get('contact_email');
                        $address  = \App\Models\SiteSetting::get('contact_address');
                        $whatsapp = \App\Models\SiteSetting::get('whatsapp_number');
                    @endphp
                    <ul class="space-y-3 mb-5">
                        @if($phone)
                        <li class="flex items-start gap-2 text-sm text-content-muted">
                            <svg class="w-4 h-4 mt-0.5 text-brand-green shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $phone }}" class="hover:text-brand-green transition-colors">{{ $phone }}</a>
                        </li>
                        @endif
                        @if($email)
                        <li class="flex items-start gap-2 text-sm text-content-muted">
                            <svg class="w-4 h-4 mt-0.5 text-brand-green shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $email }}" class="hover:text-brand-green transition-colors">{{ $email }}</a>
                        </li>
                        @endif
                        @if($address)
                        <li class="flex items-start gap-2 text-sm text-content-muted">
                            <svg class="w-4 h-4 mt-0.5 text-brand-green shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $address }}</span>
                        </li>
                        @endif
                    </ul>
                    @if($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp Us
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-surface-border">
            <div class="container-custom py-5 flex flex-col md:flex-row items-center justify-between gap-3">
                <p class="text-content-muted text-xs">&copy; {{ date('Y') }} T-Akomz Agro Estates Ltd. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy') }}" class="text-content-muted hover:text-brand-green text-xs transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="text-content-muted hover:text-brand-green text-xs transition-colors">Terms</a>
                    <a href="{{ route('refund-policy') }}" class="text-content-muted hover:text-brand-green text-xs transition-colors">Refund Policy</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- T-Akomz AI Assistant Widget --}}
    @include('components.ai-chat-widget')

    {{-- Mobile Bottom Nav --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-surface-card border-t border-surface-border z-40 px-2 py-2">
        <div class="flex items-center justify-around">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 min-w-0 {{ request()->routeIs('home') ? 'text-brand-green' : 'text-content-muted' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="{{ route('shop.index') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 min-w-0 {{ request()->routeIs('shop.*') ? 'text-brand-green' : 'text-content-muted' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="text-xs font-medium">Shop</span>
            </a>
            {{-- Wishlist — only visible when logged in and has items --}}
            @if(isset($wishlistCount) && $wishlistCount > 0)
            <a href="{{ route('account.wishlist') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 min-w-0 relative text-brand-green">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="absolute top-0 right-1 w-4 h-4 bg-brand-green text-surface-bg text-[9px] font-bold rounded-full flex items-center justify-center leading-none">{{ min($wishlistCount, 9) }}</span>
                <span class="text-xs font-medium">Saved</span>
            </a>
            @endif
            <button @click="cartOpen = true" class="flex flex-col items-center gap-0.5 px-2 py-1 min-w-0 relative"
                    :class="cartCount > 0 ? 'text-brand-green' : 'text-content-muted'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span x-show="cartCount > 0" x-text="Math.min(cartCount, 9)"
                      class="absolute top-0 right-1 w-4 h-4 bg-brand-green text-surface-bg text-[9px] font-bold rounded-full flex items-center justify-center leading-none"></span>
                <span class="text-xs font-medium">Cart</span>
            </button>
            <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="flex flex-col items-center gap-0.5 px-2 py-1 min-w-0 {{ request()->routeIs('account.*') ? 'text-brand-green' : 'text-content-muted' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-xs font-medium">Account</span>
            </a>
        </div>
    </nav>

    @stack('scripts')

    <script>
    function themeManager() {
        return {
            light: false,
            init() {
                this.light = localStorage.getItem('theme') === 'light';
                // Apply immediately (before Alpine paint) to avoid flash
                if (this.light) document.documentElement.classList.add('light');
            },
            toggle() {
                this.light = !this.light;
                localStorage.setItem('theme', this.light ? 'light' : 'dark');
                if (this.light) {
                    document.documentElement.classList.add('light');
                } else {
                    document.documentElement.classList.remove('light');
                }
            },
        };
    }
    </script>

    {{-- PWA Service Worker --}}
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(reg => {
                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                newWorker.postMessage({ type: 'SKIP_WAITING' });
                            }
                        });
                    });
                })
                .catch(() => {});
        });
    }
    </script>

    <x-pwa-install-prompt />
</body>
</html>
