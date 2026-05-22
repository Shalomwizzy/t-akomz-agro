<x-emails.layout subject="[Admin] New Order Received — {{ $order->order_number }}">

    {{-- Icon --}}
    <div class="email-icon-wrap" style="background: #EBF9E0;">
        <span style="font-size: 30px;">🛒</span>
    </div>

    {{-- Heading --}}
    <h1 class="email-title" style="text-align: center;">New Order Received</h1>
    <p class="email-subtitle" style="text-align: center;">
        A customer has placed an order and payment has been confirmed.
        Review the details below and begin fulfilment.
    </p>

    {{-- Amount Highlight --}}
    <div class="highlight-box">
        <span class="amount">{{ $order->formatted_total ?? '₦' . number_format($order->total, 2) }}</span>
        <span class="amount-label">Order Value</span>
    </div>

    {{-- Order & Customer Info --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Order Reference</span>
            <span class="info-value mono">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Customer Name</span>
            <span class="info-value">{{ $order->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $order->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $order->user->phone ?? $order->phone ?? 'Not provided' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Method</span>
            <span class="info-value">{{ ucwords(str_replace('_', ' ', $order->payment_method ?? 'Online')) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Status</span>
            <span class="info-value green">{{ ucfirst($order->payment_status ?? 'Paid') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Order Date</span>
            <span class="info-value">{{ $order->created_at->format('d M Y, g:i A') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Delivery Address</span>
            <span class="info-value" style="text-align: right; max-width: 280px;">
                {{ $order->delivery_address ?? 'Not specified' }}
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
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="font-weight: 600; color: #1A1A1A;">
                    {{ $item->product->name ?? $item->product_name ?? 'Product' }}
                </td>
                <td style="text-align: center; color: #666;">{{ $item->quantity }}</td>
                <td style="text-align: right; font-weight: 500; color: #555;">₦{{ number_format($item->price, 2) }}</td>
                <td>₦{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Order Total</td>
                <td>{{ $order->formatted_total ?? '₦' . number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- CTA --}}
    <div class="cta-wrap">
        <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" class="cta-btn">View Order in Admin</a>
    </div>

    <hr class="divider">

    <p style="font-size: 12px; color: #AAA; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated admin notification. Fulfil this order promptly to maintain
        customer satisfaction. Target dispatch within 24 hours of confirmation.
    </p>

</x-emails.layout>
