<x-emails.layout subject="Your Wishlist is Waiting — Don't Miss Out | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #FFF0F0;">
        <span style="font-size: 30px;">❤️</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Your Wishlist is Waiting for You</h1>
    <p class="email-subtitle" style="text-align: center;">
        Hi {{ $user->name }}, you have saved items on your T-Akomz wishlist.
        Farm produce is seasonal and stock levels move fast — grab yours before they sell out!
    </p>

    {{-- Low stock warning --}}
    <div class="alert-box-yellow">
        <p class="alert-title" style="color: #92400E;">⚠️ Stock Levels Are Limited</p>
        <p class="alert-text">
            Farm produce sells fast, especially during harvest periods. Items on your wishlist
            are not reserved and may run out of stock at any time. We recommend ordering sooner
            rather than later to avoid disappointment.
        </p>
    </div>

    {{-- Wishlist items --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 14px;">
        Your Saved Items
    </p>

    <div class="info-box">
        @foreach($items->take(5) as $item)
        <div class="info-row">
            <span class="info-label" style="font-size: 14px; color: #333; font-weight: 600;">
                {{ $item->product->name }}
            </span>
            <span class="info-value green">
                ₦{{ number_format($item->product->price, 2) }}
            </span>
        </div>
        @endforeach
        @if($items->count() > 5)
        <div style="padding: 10px 0 2px; text-align: center;">
            <span style="font-size: 13px; color: #999;">
                + {{ $items->count() - 5 }} more item{{ $items->count() - 5 > 1 ? 's' : '' }} on your wishlist
            </span>
        </div>
        @endif
    </div>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/account/wishlist" class="cta-btn">View My Wishlist</a>
    </div>

    <div style="text-align: center; margin-top: 12px;">
        <a href="{{ config('app.url') }}/shop" class="cta-btn-outline">Browse All Products</a>
    </div>

    <hr class="divider">

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        You're receiving this email because you saved items to your T-Akomz wishlist.
        To stop these reminders, simply
        <a href="{{ config('app.url') }}/account/wishlist" style="color: #3B8A1E; text-decoration: none;">clear your wishlist</a>
        or update your email preferences.
    </p>

</x-emails.layout>
