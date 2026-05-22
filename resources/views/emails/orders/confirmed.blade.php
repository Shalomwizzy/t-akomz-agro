<x-emails.layout subject="Order Confirmed — {{ $order->order_number }} | T-Akomz Agro">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">✅</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">Order Confirmed!</h1>
    <p class="email-subtitle" style="text-align: center;">
        Thank you, {{ $order->user->name }}! Your payment has been received and your
        order is now being prepared. You'll get another notification once it's dispatched.
    </p>

    {{-- Amount Paid Highlight --}}
    <div class="highlight-box">
        <span class="amount">{{ $order->formatted_total ?? '₦' . number_format($order->total, 2) }}</span>
        <span class="amount-label">Total Amount Paid</span>
    </div>

    {{-- Order Details --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Order Reference</span>
            <span class="info-value mono">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Method</span>
            <span class="info-value">{{ ucwords(str_replace('_', ' ', $order->payment_method ?? 'Online')) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Status</span>
            <span class="info-value green">Paid</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estimated Delivery</span>
            <span class="info-value">2 – 5 Business Days</span>
        </div>
        <div class="info-row">
            <span class="info-label">Delivery Address</span>
            <span class="info-value" style="text-align: right; max-width: 280px;">
                {{ $order->delivery_address ?? 'See account' }}
                @if(!empty($order->delivery_city)), {{ $order->delivery_city }}@endif
                @if(!empty($order->delivery_state)), {{ $order->delivery_state }}@endif
            </span>
        </div>
    </div>

    {{-- Items Table --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 12px;">
        Items Ordered
    </p>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <span style="font-weight: 600; color: #1A1A1A;">{{ $item->product->name ?? $item->product_name ?? 'Product' }}</span>
                    <br>
                    <span style="font-size: 12px; color: #999;">₦{{ number_format($item->price, 2) }} each</span>
                </td>
                <td style="text-align: center; color: #666;">{{ $item->quantity }}</td>
                <td>₦{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if(($order->subtotal ?? 0) > 0 && $order->subtotal != $order->total)
            <tr>
                <td colspan="2" style="font-size: 13px; color: #888; font-weight: 500; border-bottom: none; padding-top: 12px;">Subtotal</td>
                <td style="font-size: 13px; font-weight: 600; color: #555; border-bottom: none; padding-top: 12px; text-align: right;">
                    ₦{{ number_format($order->subtotal ?? 0, 2) }}
                </td>
            </tr>
            @if(($order->delivery_fee ?? 0) > 0)
            <tr>
                <td colspan="2" style="font-size: 13px; color: #888; font-weight: 500; border-bottom: none;">Delivery Fee</td>
                <td style="font-size: 13px; font-weight: 600; color: #555; border-bottom: none; text-align: right;">
                    ₦{{ number_format($order->delivery_fee ?? 0, 2) }}
                </td>
            </tr>
            @endif
            @endif
            <tr class="total-row">
                <td colspan="2">Total Paid</td>
                <td>{{ $order->formatted_total ?? '₦' . number_format($order->total ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <hr class="divider">

    {{-- Fulfilment Steps --}}
    <p style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 14px;">
        What Happens Next
    </p>
    <ul class="steps-list">
        <li class="step-item">
            <div class="step-num">✓</div>
            <div class="step-content">
                <h4>Order Confirmed</h4>
                <p>Your payment has been received and your order is in the system.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">2</div>
            <div class="step-content">
                <h4>Being Prepared</h4>
                <p>Our team is picking, packaging and quality-checking your items at the farm.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">3</div>
            <div class="step-content">
                <h4>Dispatched</h4>
                <p>Your order leaves the farm and is handed to our delivery team. You'll be notified by email and SMS.</p>
            </div>
        </li>
        <li class="step-item">
            <div class="step-num">4</div>
            <div class="step-content">
                <h4>Delivered to Your Door</h4>
                <p>Fresh produce arrives at your delivery address within the estimated window. Refrigerate promptly.</p>
            </div>
        </li>
    </ul>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/account/orders/{{ $order->id }}" class="cta-btn">Track My Order</a>
    </div>

    <hr class="divider">

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        Need help with your order? Reply to this email or reach us on
        <a href="{{ config('app.url') }}/contact" style="color: #3B8A1E; text-decoration: none; font-weight: 600;">WhatsApp</a>.
        We're always happy to assist.
    </p>

</x-emails.layout>
