<x-emails.orders.layout>

  <p class="title">New Arrival Just Dropped 🌾</p>
  <p class="subtitle">
    Fresh from our farm — <strong style="color: #f5f5f5;">{{ $product->name }}</strong> is now
    available in limited quantities. Don't miss out.
  </p>

  <div class="green-box">
    <p style="font-size: 22px; font-weight: 700; color: #B8F397; margin-bottom: 8px; line-height: 1.2;">
      {{ $product->name }}
    </p>
    <div style="margin-bottom: 14px;">
      @if($product->category)
        <span class="badge badge-green">{{ $product->category->name }}</span>
      @endif
    </div>
    <div style="display: flex; align-items: baseline; gap: 8px;">
      <span class="info-val" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #555;">Price</span>
      <span style="font-size: 28px; font-weight: 700; color: #B8F397; font-family: 'Courier New', monospace;">
        &#8358;{{ number_format($product->price, 2) }}
      </span>
    </div>
  </div>

  <div class="divider"></div>

  <p class="section-title">About This Product</p>
  <p class="info-val" style="font-size: 14px; line-height: 1.8; color: #ccc; margin-bottom: 24px;">
    {{ mb_strimwidth(strip_tags($product->description ?? ''), 0, 200, '...') }}
  </p>

  <div style="text-align: center; margin: 28px 0;">
    <a href="{{ config('app.url') }}/shop/{{ $product->slug }}" class="cta-btn">Order Now</a>
  </div>

  <div class="divider"></div>

  <div class="info-block" style="background: rgba(184,243,151,0.04); border: 1px solid rgba(184,243,151,0.12); border-radius: 10px; padding: 14px 20px; text-align: center;">
    <span style="color: #B8F397; font-size: 15px; margin-right: 6px;">&#9888;</span>
    <span class="info-val" style="font-size: 13px; color: #888;">
      Stock is limited. Order early for guaranteed delivery.
    </span>
  </div>

</x-emails.orders.layout>
