@extends('layouts.app')

@section('title', 'My Account')

@push('head')
<style>
/* ── Account Dashboard Premium Styles ───────────────────────── */
.acct-hero {
    background: linear-gradient(135deg,
        rgb(var(--surface-card)) 0%,
        rgba(var(--brand-green) / 0.06) 50%,
        rgb(var(--surface-card)) 100%);
    position: relative;
    overflow: hidden;
}
.acct-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 600px 300px at 80% -20%, rgba(var(--brand-green) / 0.08) 0%, transparent 70%),
        radial-gradient(ellipse 400px 200px at 20% 120%, rgba(var(--brand-green) / 0.05) 0%, transparent 70%);
    pointer-events: none;
}
.acct-hero::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(var(--brand-green) / 0.4), transparent);
}

.stat-ring-card {
    background: linear-gradient(145deg, rgb(var(--surface-card)) 0%, rgb(var(--surface-elevated)) 100%);
    border: 1px solid rgba(var(--brand-green) / 0.12);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.stat-ring-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgb(var(--brand-green)), transparent);
    opacity: 0.6;
}
.stat-ring-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(var(--brand-green) / 0.12);
}
.stat-icon-ring {
    width: 48px; height: 48px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(var(--brand-green) / 0.1);
    border: 1.5px solid rgba(var(--brand-green) / 0.2);
    position: relative;
}
.stat-icon-ring::after {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 1px dashed rgba(var(--brand-green) / 0.15);
}

.order-row {
    background: rgb(var(--surface-elevated));
    border: 1px solid transparent;
    border-radius: 16px;
    transition: all 0.2s ease;
}
.order-row:hover {
    border-color: rgba(var(--brand-green) / 0.25);
    background: rgba(var(--brand-green) / 0.04);
    transform: translateX(3px);
}

.acct-sidebar-link {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 16px;
    border-radius: 14px;
    font-size: 0.875rem;
    font-weight: 500;
    color: rgb(var(--content-secondary));
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.acct-sidebar-link:hover {
    color: rgb(var(--content-primary));
    background: rgba(var(--brand-green) / 0.06);
}
.acct-sidebar-link.active {
    color: rgb(var(--brand-green));
    background: rgba(var(--brand-green) / 0.1);
}
.acct-sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0; top: 20%; bottom: 20%;
    width: 2.5px;
    border-radius: 0 2px 2px 0;
    background: rgb(var(--brand-green));
}

.quick-action-card {
    background: linear-gradient(145deg, rgb(var(--surface-card)) 0%, rgb(var(--surface-elevated)) 100%);
    border: 1px solid rgba(255,255,255,0.04);
    border-radius: 18px;
    padding: 20px;
    display: flex; align-items: center; gap: 16px;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}
.quick-action-card::after {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(var(--brand-green) / 0.04);
    transition: transform 0.3s ease;
}
.quick-action-card:hover {
    border-color: rgba(var(--brand-green) / 0.3);
    box-shadow: 0 8px 30px rgba(var(--brand-green) / 0.08);
    transform: translateY(-2px);
}
.quick-action-card:hover::after { transform: scale(1.5); }

.avatar-ring {
    position: relative;
    display: inline-block;
}
.avatar-ring::before {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 50%;
    background: conic-gradient(
        rgb(var(--brand-green)) 0deg,
        rgba(var(--brand-green) / 0.3) 180deg,
        rgb(var(--brand-green)) 360deg
    );
    animation: spin 8s linear infinite;
    z-index: 0;
}
.avatar-ring::after {
    content: '';
    position: absolute;
    inset: -1px;
    border-radius: 50%;
    background: rgb(var(--surface-card));
    z-index: 1;
}
.avatar-ring img { position: relative; z-index: 2; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Mobile tab nav */
.acct-mobile-tabs {
    display: flex;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    gap: 6px;
    padding: 0 1px 4px;
}
.acct-mobile-tabs::-webkit-scrollbar { display: none; }
.acct-tab-pill {
    flex-shrink: 0;
    padding: 7px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
    color: rgb(var(--content-secondary));
    background: rgb(var(--surface-elevated));
    border: 1px solid rgb(var(--surface-border));
    white-space: nowrap;
    transition: all 0.2s ease;
}
.acct-tab-pill.active {
    background: rgba(var(--brand-green) / 0.12);
    color: rgb(var(--brand-green));
    border-color: rgba(var(--brand-green) / 0.3);
}

:root.light .stat-ring-card {
    background: white;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
:root.light .quick-action-card {
    background: white;
    border-color: rgba(0,0,0,0.06);
}
:root.light .order-row {
    background: rgb(var(--surface-elevated));
}
:root.light .acct-hero::before {
    background:
        radial-gradient(ellipse 600px 300px at 80% -20%, rgba(34,113,23,0.05) 0%, transparent 70%),
        radial-gradient(ellipse 400px 200px at 20% 120%, rgba(34,113,23,0.03) 0%, transparent 70%);
}
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $navLinks = [
        ['url' => route('account.dashboard'), 'label' => 'Dashboard',  'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'route' => 'account.dashboard'],
        ['url' => route('account.orders'), 'label' => 'My Orders',    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'route' => 'account.orders'],
        ['url' => route('account.wishlist'), 'label' => 'Wishlist',     'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'route' => 'account.wishlist'],
        ['url' => route('account.addresses'), 'label' => 'Addresses',    'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'route' => 'account.addresses'],
        ['url' => route('account.profile'), 'label' => 'Profile',      'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'route' => 'account.profile'],
    ];
@endphp

{{-- Hero Banner --}}
<div class="acct-hero border-b border-surface-border">
    <div class="container-custom py-8 sm:py-10 relative">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">

            {{-- Avatar --}}
            <div class="avatar-ring w-[72px] h-[72px] sm:w-20 sm:h-20 flex-shrink-0 rounded-full">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                     class="w-[72px] h-[72px] sm:w-20 sm:h-20 rounded-full object-cover">
            </div>

            {{-- Greeting --}}
            <div class="flex-1 min-w-0">
                <p class="text-content-muted text-sm mb-0.5">
                    Welcome back
                    <span class="ml-1 inline-flex items-center gap-1 text-brand-green font-medium">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Verified Member
                    </span>
                </p>
                <h1 class="font-display font-bold text-2xl sm:text-3xl text-content-primary leading-tight">
                    {{ $user->name }}
                </h1>
                <p class="text-content-muted text-sm mt-1 truncate">{{ $user->email }}</p>
            </div>

            {{-- Hero CTA --}}
            <div class="flex-shrink-0">
                <a href="{{ route('shop.index') }}"
                   class="btn-primary py-2.5 px-5 text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Shop Now
                </a>
            </div>
        </div>

        {{-- Mobile section tabs --}}
        <div class="mt-6 lg:hidden">
            <div class="acct-mobile-tabs">
                @foreach($navLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="acct-tab-pill {{ request()->routeIs($link['route']) ? 'active' : '' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Main Layout --}}
<div class="container-custom py-6 sm:py-8">
    <div class="flex gap-7">

        {{-- ── Desktop Sidebar ──────────────────────────────────── --}}
        <aside class="hidden lg:flex flex-col gap-4 w-[220px] flex-shrink-0">

            {{-- User card --}}
            <div class="card p-5 text-center">
                <div class="avatar-ring w-16 h-16 rounded-full mx-auto mb-3 inline-block">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-16 h-16 rounded-full object-cover">
                </div>
                <p class="font-semibold text-content-primary text-sm">{{ $user->name }}</p>
                <p class="text-[11px] text-content-muted mt-0.5 truncate">{{ $user->email }}</p>
                @if($user->phone)
                <p class="text-[11px] text-content-muted">{{ $user->phone }}</p>
                @endif
                <a href="{{ route('account.profile') }}"
                   class="mt-3 inline-block text-[11px] font-medium text-brand-green hover:underline">
                    Edit Profile
                </a>
            </div>

            {{-- Nav --}}
            <nav class="card p-3 space-y-1">
                @foreach($navLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="acct-sidebar-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                    {{ $link['label'] }}
                </a>
                @endforeach

                <div class="pt-2 mt-2 border-t border-surface-border">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="acct-sidebar-link w-full text-left text-red-400 hover:text-red-400 hover:bg-red-500/08">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Help card --}}
            <div class="card p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <p class="text-xs font-medium text-content-primary mb-1">Need help?</p>
                <p class="text-[11px] text-content-muted mb-2">We're here for you</p>
                <a href="{{ route('contact') }}" class="btn-outline py-1.5 px-3 text-xs">Contact Us</a>
            </div>
        </aside>

        {{-- ── Main Content ──────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-6">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                @foreach([
                    ['total_orders',     'All Orders',      'text-brand-green',  'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    ['delivered_orders', 'Delivered',       'text-emerald-400',  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['wishlist_count',   'Wishlist',        'text-brand-green',  'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['address_count',   'Saved Addresses', 'text-blue-400',     'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                ] as [$key, $label, $color, $path])
                <div class="stat-ring-card p-4 sm:p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon-ring">
                            <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $path }}"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-mono text-content-muted bg-surface-elevated px-1.5 py-0.5 rounded-md">
                            #{{ str_pad($stats[$key], 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="font-display font-bold text-2xl sm:text-3xl text-content-primary leading-none">
                        {{ $stats[$key] }}
                    </div>
                    <p class="text-xs text-content-muted mt-1 font-medium">{{ $label }}</p>
                </div>
                @endforeach
            </div>

            {{-- Recent Orders + Quick Actions (two column on md+) --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

                {{-- Recent Orders (wider col) --}}
                <div class="md:col-span-3">
                    <div class="card p-5 sm:p-6 h-full">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="font-display font-semibold text-content-primary text-base sm:text-lg">Recent Orders</h2>
                            <a href="{{ route('account.orders') }}"
                               class="text-xs font-medium text-brand-green hover:underline flex items-center gap-1">
                                View all
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        @if($recentOrders->count())
                        <div class="space-y-2.5">
                            @foreach($recentOrders as $order)
                            @php
                                $statusColors = [
                                    'PENDING'    => 'text-yellow-400 bg-yellow-400/10',
                                    'CONFIRMED'  => 'text-blue-400 bg-blue-400/10',
                                    'PROCESSING' => 'text-purple-400 bg-purple-400/10',
                                    'DISPATCHED' => 'text-orange-400 bg-orange-400/10',
                                    'DELIVERED'  => 'text-brand-green bg-brand-green/10',
                                    'CANCELLED'  => 'text-red-400 bg-red-400/10',
                                ];
                                $sc = $statusColors[$order->status] ?? 'text-content-muted bg-surface-elevated';
                            @endphp
                            <a href="{{ route('account.orders.show', $order) }}" class="order-row flex items-center gap-4 p-3.5 group">
                                {{-- Order icon --}}
                                <div class="w-10 h-10 rounded-xl bg-brand-green/08 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4.5 h-4.5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-mono font-bold text-content-primary group-hover:text-brand-green transition-colors truncate">{{ $order->order_number }}</p>
                                    <p class="text-xs text-content-muted mt-0.5">
                                        {{ $order->created_at->format('d M Y') }}
                                        <span class="text-surface-border mx-1">·</span>
                                        {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}
                                    </p>
                                </div>
                                {{-- Right --}}
                                <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                    <span class="font-bold text-sm text-brand-green">{{ $order->formatted_total }}</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $sc }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-brand-green/06 border border-brand-green/15 flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-brand-green/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <p class="font-semibold text-content-primary text-sm mb-1">No orders yet</p>
                            <p class="text-xs text-content-muted mb-5">Your orders will appear here after your first purchase.</p>
                            <a href="{{ route('shop.index') }}" class="btn-primary text-sm py-2.5 px-6">
                                Browse Products
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions (narrower col) --}}
                <div class="md:col-span-2 space-y-3">
                    <h2 class="font-display font-semibold text-content-primary text-base sm:text-lg px-1">Quick Actions</h2>

                    @foreach([
                        [route('account.wishlist'),  'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'My Wishlist',       'Saved products',               'text-brand-green',  'bg-brand-green/08'],
                        [route('account.addresses'), 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',                                          'Delivery Addresses', 'Manage your locations',         'text-blue-400',     'bg-blue-400/08'],
                        [route('account.profile'),   'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',                                                         'Profile Settings',   'Update your info',              'text-purple-400',   'bg-purple-400/08'],
                        [route('orders.track'),      'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',                                                                                 'Track an Order',     'Enter order number to track',   'text-orange-400',   'bg-orange-400/08'],
                    ] as [$url, $iconPath, $label, $sub, $color, $bg])
                    <a href="{{ $url }}" class="quick-action-card group">
                        <div class="w-10 h-10 rounded-xl {{ $bg }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-4.5 h-4.5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-content-primary group-hover:text-brand-green transition-colors">{{ $label }}</p>
                            <p class="text-xs text-content-muted mt-0.5">{{ $sub }}</p>
                        </div>
                        <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endforeach

                    {{-- Farm freshness promise card --}}
                    <div class="card p-4 mt-2" style="background: linear-gradient(135deg, rgba(var(--brand-green)/0.06) 0%, transparent 100%); border-color: rgba(var(--brand-green)/0.15);">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-brand-green/10 flex items-center justify-center flex-shrink-0">
                                <x-logo class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-content-primary">T-Akomz Farm Promise</p>
                                <p class="text-[11px] text-content-muted mt-0.5 leading-relaxed">Every product is harvested fresh from our 50-acre Ido Ekiti farm and delivered within 24 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recently Viewed / Shop promo --}}
            <div class="card p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-green/10 border border-brand-green/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-content-primary">Fresh arrivals this week</p>
                            <p class="text-sm text-content-muted mt-0.5">New seasonal produce just listed from the farm.</p>
                        </div>
                    </div>
                    <a href="{{ route('shop.index') }}" class="btn-primary py-2.5 px-5 text-sm flex-shrink-0 w-full sm:w-auto text-center">
                        Shop Now
                    </a>
                </div>
            </div>

        </div>{{-- end main --}}
    </div>{{-- end flex --}}
</div>
@endsection
