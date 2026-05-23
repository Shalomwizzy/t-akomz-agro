@php
    $lowStock       = \App\Models\Product::whereRaw('stock <= low_stock_threshold')->where('is_active', true)->count();
    $pendingOrders  = \App\Models\Order::where('status', 'PENDING')->count();
    $newCustomers   = \App\Models\User::role('CUSTOMER')->where('created_at', '>=', now()->subHours(24))->count();
    $pendingReviews = \App\Models\Review::where('is_approved', false)->count();
    $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
    $notifTotal     = $lowStock + $pendingOrders + $newCustomers + $pendingReviews + $unreadMessages;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth"
      x-data="themeManager()" x-init="init()" :class="light ? 'light' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') | T-Akomz Admin</title>
    <link rel="icon" href="{{ asset('images/icons/pwa-192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#B8F397">
    <script>if(localStorage.getItem('theme')==='light')document.documentElement.classList.add('light');</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- OneSignal Web Push (admin subscribes too) --}}
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "{{ config('services.onesignal.app_id') }}",
                allowLocalhostAsSecureOrigin: true,
            });
            @auth
            // Tag the admin with user ID for targeted pushes
            await OneSignal.login('{{ auth()->id() }}');
            @endauth
        });
    </script>
    @stack('head')
    <style>
        /* Premium sidebar pattern */
        .admin-sidebar {
            background: linear-gradient(180deg, #0D0D0D 0%, #080808 100%);
            box-shadow: 4px 0 40px rgba(0,0,0,0.6);
        }
        .admin-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                radial-gradient(ellipse at 0% 0%, rgba(184,243,151,0.04) 0%, transparent 60%),
                radial-gradient(ellipse at 100% 100%, rgba(111,174,75,0.03) 0%, transparent 50%);
            pointer-events: none;
        }
        .admin-header {
            background: linear-gradient(180deg, rgba(16,16,16,0.98) 0%, rgba(12,12,12,0.98) 100%);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 4px 20px rgba(0,0,0,0.4), 0 1px 0 rgba(255,255,255,0.04) inset;
            backdrop-filter: blur(12px);
        }
        :root.light .admin-sidebar {
            background: linear-gradient(180deg, #f8faf8 0%, #f0f5f0 100%);
            box-shadow: 4px 0 40px rgba(0,0,0,0.08);
        }
        :root.light .admin-sidebar::before {
            background-image: radial-gradient(ellipse at 0% 0%, rgba(34,113,23,0.05) 0%, transparent 60%);
        }
        :root.light .admin-header {
            background: rgba(255,255,255,0.98);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .admin-main {
            background: linear-gradient(135deg, #060606 0%, #0a0a0a 100%);
        }
        :root.light .admin-main {
            background: linear-gradient(135deg, #f5f8f5 0%, #eef3ee 100%);
        }
        /* Subtle dot grid on main area */
        .admin-main::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image: radial-gradient(rgba(184,243,151,0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            mask-image: radial-gradient(ellipse at 50% 0%, black 0%, transparent 70%);
            z-index: 0;
        }
        :root.light .admin-main::before { display: none; }
        .admin-main > * { position: relative; z-index: 1; }

        /* Notification & profile dropdown polish */
        .admin-dropdown {
            background: linear-gradient(160deg, #181818 0%, #111111 100%);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 20px 60px rgba(0,0,0,0.7), 0 4px 12px rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,0.06) inset;
            border-radius: 16px;
        }
        :root.light .admin-dropdown {
            background: #ffffff;
            border-color: rgba(0,0,0,0.1);
            box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.06);
        }

        /* Active sidebar glow pulse */
        @keyframes sidebarGlow {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        .sidebar-active-dot {
            animation: sidebarGlow 2s ease-in-out infinite;
        }

        /* Table row hover */
        table tbody tr:hover { background: rgba(184,243,151,0.02); }
        :root.light table tbody tr:hover { background: rgba(34,113,23,0.03); }
    </style>
</head>
<body class="text-content-primary min-h-screen" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar Overlay (mobile) --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 lg:hidden"
             style="background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);">
        </div>

        {{-- ═══════════ SIDEBAR ═══════════ --}}
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-40 w-[260px] flex flex-col transform transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               style="position: fixed;">

            {{-- Logo Area --}}
            <div class="flex items-center justify-between h-[70px] px-5 flex-shrink-0"
                 style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-green/20 rounded-xl blur-md group-hover:bg-brand-green/30 transition-all duration-300"></div>
                        <div class="relative w-9 h-9 bg-brand-green/10 border border-brand-green/20 rounded-xl flex items-center justify-center">
                            <x-logo class="h-5 w-auto" />
                        </div>
                    </div>
                    <div class="leading-tight">
                        <div class="font-display font-black text-sm text-brand-green tracking-wider">T-AKOMZ</div>
                        <div class="text-content-muted tracking-[0.2em] font-medium" style="font-size:8px">ADMIN PANEL</div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-content-muted hover:text-content-primary p-1.5 rounded-lg hover:bg-white/5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5" style="scrollbar-width: none;">

                @if(auth()->user()->hasRole('MANAGER'))
                {{-- ══════════════════════════════════════════
                     MANAGER NAVIGATION
                     Finance-focused view. Additional modules
                     unlocked by admin via permissions.
                ══════════════════════════════════════════ --}}

                <p class="admin-section-header">Financial Control</p>

                <a href="{{ route('admin.finance.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    Finance Overview
                    @if(request()->routeIs('admin.finance.dashboard'))
                    <span class="sidebar-active-dot ml-auto w-1.5 h-1.5 rounded-full bg-brand-green flex-shrink-0"></span>
                    @endif
                </a>

                <a href="{{ route('admin.finance.expenses.direct') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.expenses.direct') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Log Expense
                </a>

                <a href="{{ route('admin.finance.expenses.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.expenses.index') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    My Expenses
                </a>

                {{-- Extra modules granted by admin — every section shown conditionally --}}

                {{-- Dashboard --}}
                @can('dashboard.view')
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Admin Dashboard
                </a>
                @endcan

                {{-- Commerce --}}
                @if(auth()->user()->can('orders.manage') || auth()->user()->can('products.manage') || auth()->user()->can('categories.manage') || auth()->user()->can('inventory.view') || auth()->user()->can('promotions.manage') || auth()->user()->can('reviews.manage'))
                <p class="admin-section-header">Commerce</p>
                @can('orders.manage')
                <a href="{{ route('admin.orders.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Orders
                    @if($pendingOrders > 0)
                    <span class="ml-auto text-xs bg-blue-500/20 border border-blue-500/30 text-blue-400 px-1.5 py-0.5 rounded-full font-bold leading-none">{{ min($pendingOrders, 99) }}</span>
                    @endif
                </a>
                @endcan
                @can('products.manage')
                <a href="{{ route('admin.products.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.products*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Products
                </a>
                @endcan
                @can('categories.manage')
                <a href="{{ route('admin.categories.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Categories
                </a>
                @endcan
                @can('inventory.view')
                <a href="{{ route('admin.inventory.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.inventory*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Inventory
                </a>
                @endcan
                @can('promotions.manage')
                <a href="{{ route('admin.promotions.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.promotions*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Promotions
                </a>
                @endcan
                @can('reviews.manage')
                <a href="{{ route('admin.reviews.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Reviews
                </a>
                @endcan
                @endif

                {{-- People --}}
                @if(auth()->user()->can('customers.view') || auth()->user()->can('staff.manage'))
                <p class="admin-section-header">People</p>
                @can('customers.view')
                <a href="{{ route('admin.customers.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Customers
                    @if($newCustomers > 0)
                    <span class="ml-auto text-xs bg-brand-green/20 border border-brand-green/30 text-brand-green px-1.5 py-0.5 rounded-full font-bold leading-none">+{{ min($newCustomers, 99) }}</span>
                    @endif
                </a>
                @endcan
                @can('staff.manage')
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Staff
                </a>
                @endcan
                @endif

                {{-- Content --}}
                @if(auth()->user()->can('blog.manage') || auth()->user()->can('gallery.manage') || auth()->user()->can('team.manage') || auth()->user()->can('tour-bookings.view'))
                <p class="admin-section-header">Content</p>
                @can('blog.manage')
                <a href="{{ route('admin.blog.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.blog*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Blog
                </a>
                @endcan
                @can('gallery.manage')
                <a href="{{ route('admin.gallery.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Gallery
                </a>
                @endcan
                @can('team.manage')
                <a href="{{ route('admin.team.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.team*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Our Team
                </a>
                @endcan
                @can('tour-bookings.view')
                <a href="{{ route('admin.tour-bookings.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.tour-bookings*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Tour Bookings
                </a>
                @endcan
                @endif

                {{-- Intelligence --}}
                @if(auth()->user()->can('analytics.view') || auth()->user()->can('delivery.manage'))
                <p class="admin-section-header">Intelligence</p>
                @can('analytics.view')
                <a href="{{ route('admin.analytics.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.analytics*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Analytics
                </a>
                @endcan
                @can('delivery.manage')
                <a href="{{ route('admin.deliveries.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.deliveries*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    Deliveries
                </a>
                @endcan
                @endif

                {{-- Farm ERP --}}
                @if(auth()->user()->can('operations.view') || auth()->user()->can('livestock.manage') || auth()->user()->can('farm-supplies.manage'))
                <p class="admin-section-header">Farm ERP</p>
                @can('operations.view')
                <a href="{{ route('admin.operations.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.operations*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Operations Overview
                </a>
                @endcan
                @can('livestock.manage')
                <a href="{{ route('admin.livestock.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.livestock*') ? 'sidebar-link-active' : '' }}">
                    <span class="text-base flex-shrink-0 w-4 h-4 flex items-center justify-center leading-none">🐾</span>
                    Livestock Batches
                </a>
                @endcan
                @can('farm-supplies.manage')
                <a href="{{ route('admin.farm-supplies.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.farm-supplies*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Farm Supplies
                </a>
                @endcan

                {{-- HR & Payroll --}}
                <a href="{{ route('admin.workers.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.workers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Workers (HR)
                </a>
                <a href="{{ route('admin.attendance.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.attendance*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7l2 2 4-4"/>
                    </svg>
                    Attendance
                </a>
                <a href="{{ route('admin.payroll.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payroll*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Payroll
                    @php $unpaidPayrolls = \App\Models\Payroll::whereIn('status', ['draft','approved'])->count(); @endphp
                    @if($unpaidPayrolls > 0)
                    <span class="ml-auto text-xs bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 px-1.5 py-0.5 rounded-full font-bold">{{ $unpaidPayrolls }}</span>
                    @endif
                </a>
                @endif

                {{-- AI Assistant --}}
                @can('ai.access')
                <div class="pt-2 pb-1 px-1 mt-1">
                    <a href="{{ route('admin.ai.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.ai*') ? 'sidebar-link-active' : '' }} relative overflow-hidden"
                       style="background: linear-gradient(90deg, rgba(184,243,151,0.08) 0%, rgba(111,174,75,0.04) 100%); border: 1px solid rgba(184,243,151,0.12);">
                        <div class="relative w-5 h-5 flex items-center justify-center">
                            <x-logo class="w-4 h-4 opacity-90" />
                        </div>
                        <span class="relative text-brand-green font-semibold">T-AKOMZ AI</span>
                        <span class="relative ml-auto text-xs bg-brand-green text-surface-bg px-1.5 py-0.5 rounded-full font-bold leading-none">AI</span>
                    </a>
                </div>
                @endcan

                @else
                {{-- ══════════════════════════════════════════
                     ADMIN / SUPER_ADMIN NAVIGATION
                     Full access. Sections in logical order:
                     Main → Commerce → People → Farm ERP →
                     HR & Payroll → Content → Analytics →
                     Financial Control → Communications → System
                ══════════════════════════════════════════ --}}

                {{-- ─── MAIN ──────────────────────────────── --}}
                <p class="admin-section-header">Main</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                    @if(request()->routeIs('admin.dashboard'))
                    <span class="sidebar-active-dot ml-auto w-1.5 h-1.5 rounded-full bg-brand-green flex-shrink-0"></span>
                    @endif
                </a>

                {{-- ─── COMMERCE ──────────────────────────── --}}
                <p class="admin-section-header">Commerce</p>

                <div x-data="{ open: {{ request()->routeIs('admin.orders*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="sidebar-link w-full justify-between {{ request()->routeIs('admin.orders*') ? 'sidebar-link-active' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Orders
                        </span>
                        <span class="flex items-center gap-1.5 flex-shrink-0">
                            @if($pendingOrders > 0)
                            <span class="text-xs bg-blue-500/20 border border-blue-500/30 text-blue-400 px-1.5 py-0.5 rounded-full font-bold leading-none">{{ min($pendingOrders, 99) }}</span>
                            @endif
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 text-content-muted" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </button>
                    <div x-show="open" x-cloak class="pl-9 mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.orders.index') }}" class="sidebar-link text-xs py-2">All Orders</a>
                        <a href="{{ route('admin.orders.index') }}?status=PENDING" class="sidebar-link text-xs py-2">Pending</a>
                        <a href="{{ route('admin.orders.index') }}?status=PROCESSING" class="sidebar-link text-xs py-2">Processing</a>
                        <a href="{{ route('admin.orders.index') }}?status=DELIVERED" class="sidebar-link text-xs py-2">Delivered</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('admin.products*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="sidebar-link w-full justify-between {{ request()->routeIs('admin.products*') ? 'sidebar-link-active' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                        </span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-content-muted flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-9 mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.products.index') }}" class="sidebar-link text-xs py-2">All Products</a>
                        <a href="{{ route('admin.products.create') }}" class="sidebar-link text-xs py-2">Add Product</a>
                    </div>
                </div>

                <a href="{{ route('admin.categories.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Categories
                </a>

                <a href="{{ route('admin.inventory.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.inventory*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Inventory
                </a>

                <a href="{{ route('admin.promotions.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.promotions*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Promotions
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Reviews
                    @php $pendingReviewCount = \App\Models\Review::where('is_approved', false)->count(); @endphp
                    @if($pendingReviewCount > 0)
                    <span class="ml-auto text-xs bg-yellow-500/20 text-yellow-400 px-1.5 py-0.5 rounded-full font-bold">{{ $pendingReviewCount }}</span>
                    @endif
                </a>

                {{-- ─── PEOPLE ────────────────────────────── --}}
                <p class="admin-section-header">People</p>

                <a href="{{ route('admin.customers.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Customers
                    @if($newCustomers > 0)
                    <span class="ml-auto text-xs bg-brand-green/20 border border-brand-green/30 text-brand-green px-1.5 py-0.5 rounded-full font-bold leading-none">+{{ min($newCustomers, 99) }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Staff
                </a>

                {{-- ─── FARM ERP ───────────────────────────── --}}
                <p class="admin-section-header">Farm ERP</p>

                <a href="{{ route('admin.operations.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.operations*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Operations
                </a>

                <a href="{{ route('admin.livestock.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.livestock*') ? 'sidebar-link-active' : '' }}">
                    <span class="text-base flex-shrink-0 w-4 h-4 flex items-center justify-center leading-none">🐾</span>
                    Livestock
                </a>

                <a href="{{ route('admin.farm-supplies.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.farm-supplies*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Farm Supplies
                </a>

                {{-- ─── HR & PAYROLL ────────────────────────── --}}
                <p class="admin-section-header">HR & Payroll</p>

                <a href="{{ route('admin.workers.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.workers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Workers
                </a>

                <a href="{{ route('admin.attendance.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.attendance*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7l2 2 4-4"/>
                    </svg>
                    Attendance
                </a>

                <a href="{{ route('admin.payroll.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payroll*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Payroll
                    @php $unpaidPayrolls = \App\Models\Payroll::whereIn('status', ['draft','approved'])->count(); @endphp
                    @if($unpaidPayrolls > 0)
                    <span class="ml-auto text-xs bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 px-1.5 py-0.5 rounded-full font-bold">{{ $unpaidPayrolls }}</span>
                    @endif
                </a>

                {{-- ─── CONTENT ────────────────────────────── --}}
                <p class="admin-section-header">Content</p>

                <div x-data="{ open: {{ request()->routeIs('admin.blog*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="sidebar-link w-full justify-between {{ request()->routeIs('admin.blog*') ? 'sidebar-link-active' : '' }}">
                        <span class="flex items-center gap-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Blog
                        </span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-content-muted flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak class="pl-9 mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.blog.index') }}" class="sidebar-link text-xs py-2">All Posts</a>
                        <a href="{{ route('admin.blog.create') }}" class="sidebar-link text-xs py-2">New Post</a>
                    </div>
                </div>

                <a href="{{ route('admin.gallery.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.gallery*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Gallery
                </a>

                <a href="{{ route('admin.team.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.team*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Our Team
                </a>

                <a href="{{ route('admin.tour-bookings.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.tour-bookings*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Tour Bookings
                </a>

                {{-- ─── ANALYTICS ──────────────────────────── --}}
                <p class="admin-section-header">Analytics</p>

                <a href="{{ route('admin.analytics.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.analytics*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Analytics
                </a>

                <a href="{{ route('admin.deliveries.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.deliveries*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    Deliveries
                </a>

                {{-- ─── FINANCIAL CONTROL ──────────────────── --}}
                @can('wallet.view')
                <p class="admin-section-header">Financial Control</p>

                <a href="{{ route('admin.finance.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    Finance Dashboard
                    @php $finPending = \App\Models\Wallet::main()->pendingCount(); @endphp
                    @if($finPending > 0)
                    <span class="ml-auto text-[10px] font-bold bg-yellow-400/20 text-yellow-400 px-1.5 py-0.5 rounded-full border border-yellow-400/30">{{ $finPending }}</span>
                    @elseif(request()->routeIs('admin.finance.dashboard'))
                    <span class="sidebar-active-dot ml-auto w-1.5 h-1.5 rounded-full bg-brand-green flex-shrink-0"></span>
                    @endif
                </a>

                @can('wallet.fund')
                <a href="{{ route('admin.finance.fund') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.fund') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Fund Wallet
                </a>
                @endcan

                <a href="{{ route('admin.finance.expenses.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.finance.expenses*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    All Expenses
                </a>
                @endcan

                {{-- ─── COMMUNICATIONS ─────────────────────── --}}
                <p class="admin-section-header">Communications</p>

                <a href="{{ route('admin.contact-messages.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.contact-messages*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Messages
                    @php $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                    @if($unreadMessages > 0)
                    <span class="ml-auto text-xs bg-brand-green/20 border border-brand-green/30 text-brand-green px-1.5 py-0.5 rounded-full font-bold">{{ $unreadMessages }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.mail.workers') }}"
                   class="sidebar-link {{ request()->routeIs('admin.mail.workers*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Mail Workers / Staff
                </a>

                <a href="{{ route('admin.mail.users') }}"
                   class="sidebar-link {{ request()->routeIs('admin.mail.users*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Mail Customers
                </a>

                <a href="{{ route('download.guide') }}"
                   class="sidebar-link"
                   download>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Website Guide
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.notifications.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Push Notifications
                </a>
                @endif

                {{-- ─── SYSTEM (Super Admin only) ───────────── --}}
                @if(auth()->user()->isSuperAdmin())
                <p class="admin-section-header">System</p>

                <a href="{{ route('admin.settings.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>
                @endif

                {{-- ─── AI ASSISTANT (always last, branded) ── --}}
                <div class="pt-3 pb-1 px-1 mt-2" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <a href="{{ route('admin.ai.index') }}"
                       class="sidebar-link {{ request()->routeIs('admin.ai*') ? 'sidebar-link-active' : '' }} relative overflow-hidden"
                       style="background: linear-gradient(90deg, rgba(184,243,151,0.08) 0%, rgba(111,174,75,0.04) 100%); border: 1px solid rgba(184,243,151,0.12);">
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-300"
                             style="background: linear-gradient(90deg, rgba(184,243,151,0.12) 0%, transparent 100%);"></div>
                        <div class="relative w-5 h-5 flex items-center justify-center">
                            <x-logo class="w-4 h-4 opacity-90" />
                        </div>
                        <span class="relative text-brand-green font-semibold">T-AKOMZ AI</span>
                        <span class="relative ml-auto text-xs bg-brand-green text-surface-bg px-1.5 py-0.5 rounded-full font-bold leading-none">AI</span>
                    </a>
                </div>

                @endif {{-- end @else (non-MANAGER users) --}}

            </nav>

            {{-- Sidebar Footer (User Card) --}}
            <div class="p-3 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 transition-all group cursor-default"
                     style="background: rgba(255,255,255,0.03);">
                    <div class="relative flex-shrink-0">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-brand-green/20" alt="">
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-brand-green rounded-full border-2 border-[#0D0D0D]"></div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-content-primary truncate leading-tight">{{ Str::limit(auth()->user()->name, 18) }}</p>
                        <p class="text-xs text-content-muted truncate leading-tight">{{ auth()->user()->roles->first()?->name ?? 'Admin' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" title="Logout"
                                class="p-1.5 rounded-lg text-content-muted hover:text-red-400 hover:bg-red-500/10 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ═══════════ MAIN AREA ═══════════ --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-[260px]">

            {{-- ─── TOP HEADER ─── --}}
            <header class="admin-header h-[70px] flex items-center justify-between px-6 flex-shrink-0 sticky top-0 z-20">

                {{-- Left: Mobile menu + Page title --}}
                <div class="flex items-center gap-4 min-w-0">
                    <button @click="sidebarOpen = true" class="lg:hidden text-content-muted hover:text-content-primary p-2 rounded-xl hover:bg-white/5 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="font-display text-lg font-bold text-content-primary truncate">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                        <p class="text-xs text-content-muted">@yield('breadcrumb')</p>
                        @endif
                    </div>
                </div>

                {{-- Centre: Global search --}}
                <form action="{{ route('admin.search') }}" method="GET"
                      class="hidden lg:flex items-center gap-2 flex-1 max-w-sm mx-6">
                    <div class="relative w-full">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Search products, orders, customers…"
                               class="w-full pl-9 pr-3 py-2 text-sm rounded-xl text-content-primary placeholder-content-muted focus:outline-none focus:border-brand-green/50 transition-colors"
                               style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                    </div>
                </form>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                    {{-- Alert badges --}}
                    @if($lowStock > 0)
                    <a href="{{ route('admin.inventory.index') }}"
                       class="hidden sm:flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-yellow-400 transition-all"
                       style="background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.2);">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $lowStock }} Low Stock
                    </a>
                    @endif

                    {{-- Website Guide Download --}}
                    <a href="{{ route('download.guide') }}" download="T-Akomz-Website-Guide.html"
                       title="Download Website Guide"
                       class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                       style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.25); color: #B8F397;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Client Guide
                    </a>

                    {{-- Theme toggle --}}
                    <button @click="toggle()" title="Toggle theme"
                            class="p-2.5 rounded-xl text-content-muted hover:text-content-primary transition-all"
                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                        <svg x-show="!light" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="light" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Notifications --}}
                    <div class="relative" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
                        <button @click="notifOpen = !notifOpen"
                                class="relative p-2.5 rounded-xl text-content-muted hover:text-content-primary transition-all"
                                :class="notifOpen ? 'text-brand-green' : ''"
                                style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($notifTotal > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-lg">{{ min($notifTotal, 9) }}</span>
                            @endif
                        </button>

                        <div x-show="notifOpen" x-cloak x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="admin-dropdown absolute right-0 mt-2 w-80 py-2 z-50">

                            <div class="px-4 py-2 flex items-center justify-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <p class="text-xs font-bold uppercase tracking-widest text-content-muted">Notifications</p>
                                @if($notifTotal > 0)
                                <span class="text-xs font-bold text-brand-green">{{ $notifTotal }} alert{{ $notifTotal > 1 ? 's' : '' }}</span>
                                @endif
                            </div>

                            @if($lowStock > 0)
                            <a href="{{ route('admin.inventory.index') }}" @click="notifOpen = false"
                               class="flex items-center gap-3.5 px-4 py-3.5 hover:bg-white/5 transition-colors group">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(234,179,8,0.12); border: 1px solid rgba(234,179,8,0.2);">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-content-primary">{{ $lowStock }} Low Stock Alert{{ $lowStock > 1 ? 's' : '' }}</p>
                                    <p class="text-xs text-content-muted">Products need restocking soon</p>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif

                            @if($pendingOrders > 0)
                            <a href="{{ route('admin.orders.index') }}?status=PENDING" @click="notifOpen = false"
                               class="flex items-center gap-3.5 px-4 py-3.5 hover:bg-white/5 transition-colors group">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2);">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-content-primary">{{ $pendingOrders }} Pending Order{{ $pendingOrders > 1 ? 's' : '' }}</p>
                                    <p class="text-xs text-content-muted">Awaiting your confirmation</p>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif

                            @if($newCustomers > 0)
                            <a href="{{ route('admin.customers.index') }}" @click="notifOpen = false"
                               class="flex items-center gap-3.5 px-4 py-3.5 hover:bg-white/5 transition-colors group">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(184,243,151,0.1); border: 1px solid rgba(184,243,151,0.2);">
                                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-content-primary">{{ $newCustomers }} New Customer{{ $newCustomers > 1 ? 's' : '' }}</p>
                                    <p class="text-xs text-content-muted">Registered in the last 24 hours</p>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif

                            @if($pendingReviews > 0)
                            <a href="{{ route('admin.reviews.index') }}" @click="notifOpen = false"
                               class="flex items-center gap-3.5 px-4 py-3.5 hover:bg-white/5 transition-colors group">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(245,166,35,0.1); border: 1px solid rgba(245,166,35,0.2);">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-content-primary">{{ $pendingReviews }} Review{{ $pendingReviews > 1 ? 's' : '' }} to Approve</p>
                                    <p class="text-xs text-content-muted">Customer reviews awaiting moderation</p>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif

                            @if($unreadMessages > 0)
                            <a href="{{ route('admin.contact-messages.index') }}" @click="notifOpen = false"
                               class="flex items-center gap-3.5 px-4 py-3.5 hover:bg-white/5 transition-colors group">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-content-primary">{{ $unreadMessages }} Unread Message{{ $unreadMessages > 1 ? 's' : '' }}</p>
                                    <p class="text-xs text-content-muted">New contact form submissions</p>
                                </div>
                                <svg class="w-4 h-4 text-content-muted group-hover:text-brand-green transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @endif

                            @if($notifTotal === 0)
                            <div class="px-4 py-8 text-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3"
                                     style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.12);">
                                    <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-content-primary">All caught up!</p>
                                <p class="text-xs text-content-muted mt-1">No alerts at this time.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- View site --}}
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" 
                       class="hidden md:flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-content-muted hover:text-content-primary text-xs font-medium transition-all"
                       style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Site
                    </a>

                    {{-- Profile --}}
                    <div class="relative" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2.5 pl-3 transition-all"
                                style="border-left: 1px solid rgba(255,255,255,0.08);">
                            <div class="hidden sm:block text-right">
                                <p class="text-xs font-bold text-content-primary leading-tight">{{ Str::limit(auth()->user()->name, 14) }}</p>
                                <p class="text-[10px] text-content-muted leading-tight tracking-wider">{{ auth()->user()->roles->first()?->name ?? 'Admin' }}</p>
                            </div>
                            <div class="relative">
                                <img src="{{ auth()->user()->avatar_url }}"
                                     class="w-9 h-9 rounded-xl object-cover"
                                     style="ring: 2px solid rgba(184,243,151,0.3);" alt="">
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-brand-green rounded-full border-2"
                                     style="border-color: #0D0D0D;"></div>
                            </div>
                        </button>

                        <div x-show="profileOpen" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="admin-dropdown absolute right-0 mt-2 w-56 py-1.5 z-50">
                            <div class="px-4 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <p class="text-sm font-bold text-content-primary">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-content-muted truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.profile') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-white/5 hover:text-content-primary transition-colors">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                My Profile
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.settings.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-content-secondary hover:bg-white/5 hover:text-content-primary transition-colors">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Settings
                            </a>
                            @endif
                            <div style="border-top: 1px solid rgba(255,255,255,0.06);" class="mt-1 pt-1">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium"
                 style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.2); color: rgb(184,243,151);">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-400"
                 style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            {{-- ─── MAIN CONTENT ─── --}}
            <main class="admin-main flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
    // Persist sidebar scroll position across page navigations
    (function () {
        var KEY = 'sidebar_nav_scroll';
        var nav = document.querySelector('aside nav');
        if (!nav) return;

        // Restore position immediately (before paint)
        var saved = sessionStorage.getItem(KEY);
        if (saved) nav.scrollTop = parseInt(saved, 10);

        // Save position on every sidebar link click
        nav.addEventListener('click', function (e) {
            var link = e.target.closest('a');
            if (link) sessionStorage.setItem(KEY, nav.scrollTop);
        });

        // Also save on page unload (handles button-triggered navigations)
        window.addEventListener('pagehide', function () {
            sessionStorage.setItem(KEY, nav.scrollTop);
        });
    })();

    function themeManager() {
        return {
            light: false,
            init() {
                this.light = localStorage.getItem('theme') === 'light';
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
</body>
</html>
