<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'T-Akomz Agro Estates') }}</title>
    <link rel="icon" href="{{ asset('images/logo-mark.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-mark.svg') }}">
    <meta name="theme-color" content="#B8F397">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-bg text-content-primary font-sans antialiased min-h-screen" x-data>

<div class="min-h-screen flex">

    {{-- Left: Brand Panel (hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-5/12 relative flex-col justify-between p-12 overflow-hidden bg-surface-card border-r border-surface-border">

        {{-- Background glow --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-brand-green/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-brand-green/8 rounded-full translate-x-1/4 translate-y-1/4"></div>
        </div>

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="relative flex items-center gap-3 w-fit">
            <x-logo class="h-14 w-auto" />
            <div>
                <div class="font-display font-bold text-xl text-brand-green tracking-wider">T-AKOMZ</div>
                <div class="text-content-muted text-xs tracking-widest uppercase">Agro Estates Ltd</div>
            </div>
        </a>

        {{-- Center content --}}
        <div class="relative">
            <h2 class="font-display font-bold text-4xl text-content-primary leading-tight mb-4">
                Farm Fresh.<br>
                <span class="text-brand-green">Straight</span> to<br>
                Your Table.
            </h2>
            <p class="text-content-secondary text-base leading-relaxed mb-8">
                Premium poultry, eggs, livestock & organic produce from our 50-acre certified farm — delivered across Nigeria.
            </p>

            <div class="space-y-3">
                @foreach([
                    ['🌿', '100% Organic & Natural', 'No antibiotics, no artificial chemicals'],
                    ['🚚', 'Same-Day Delivery', 'Order before 12pm, arrive today'],
                    ['⭐', '200+ Happy Customers', 'Trusted farm-to-table brand since 2018'],
                ] as [$icon, $title, $sub])
                <div class="flex items-start gap-3 p-3 bg-surface-elevated/50 rounded-xl border border-surface-border">
                    <span class="text-2xl flex-shrink-0">{{ $icon }}</span>
                    <div>
                        <p class="text-sm font-semibold text-content-primary">{{ $title }}</p>
                        <p class="text-xs text-content-muted">{{ $sub }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom --}}
        <p class="relative text-xs text-content-muted">&copy; {{ date('Y') }} T-Akomz Agro Estates Ltd</p>
    </div>

    {{-- Right: Form Panel --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 lg:px-16 overflow-y-auto">

        {{-- Mobile logo --}}
        <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2.5 mb-8">
            <x-logo class="h-10 w-auto" />
            <div>
                <div class="font-display font-bold text-lg text-brand-green tracking-wider">T-AKOMZ</div>
                <div class="text-content-muted text-xs tracking-widest uppercase" style="font-size:9px">Agro Estates Ltd</div>
            </div>
        </a>

        {{-- Form card --}}
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>

        <a href="{{ route('home') }}" class="mt-8 text-xs text-content-muted hover:text-brand-green transition-colors">
            &larr; Back to website
        </a>
    </div>

</div>

</body>
</html>
