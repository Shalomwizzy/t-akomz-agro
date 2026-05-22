<aside class="lg:w-56 flex-shrink-0">
    <div class="card p-5 mb-4 text-center">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
             class="w-16 h-16 rounded-full mx-auto mb-3 object-cover">
        <p class="font-semibold text-content-primary text-sm">{{ auth()->user()->name }}</p>
        <p class="text-xs text-content-muted">{{ auth()->user()->email }}</p>
    </div>
    <nav class="space-y-1">
        @foreach([
            [route('account.dashboard'), 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'Dashboard'],
            [route('account.orders'), 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'My Orders'],
            [route('account.wishlist'), 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'Wishlist'],
            [route('account.addresses'), 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'Addresses'],
            [route('account.profile'), 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'Profile'],
        ] as [$url, $icon, $label])
        <a href="{{ $url }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors
               {{ request()->url() === $url ? 'bg-brand-green/10 text-brand-green font-medium' : 'text-content-secondary hover:bg-surface-elevated hover:text-content-primary' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
            {{ $label }}
        </a>
        @endforeach

        <div class="pt-2 border-t border-surface-border mt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-400 hover:bg-red-500/10 transition-colors w-full text-left">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </nav>
</aside>
