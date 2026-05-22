<x-emails.layout subject="Order Cancelled | T-Akomz Agro Estates">

    {{-- Header --}}
    <div style="text-align: center; padding: 10px 0 24px;">
        <div style="display: inline-block; width: 68px; height: 68px; border-radius: 50%; background: #FEF0F0; margin: 0 auto 18px; line-height: 68px; font-size: 32px; text-align: center;">
            ❌
        </div>
        <h1 style="font-family: 'Georgia', serif; font-size: 24px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px;">
            Your Order Has Been Cancelled
        </h1>
        <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.7;">
            Hi {{ $order->user?->name ?? $order->customer_name ?? 'Valued Customer' }}, we're writing to confirm that order <strong>{{ $order->order_number }}</strong> has been cancelled.
        </p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #E0E0E0, transparent); margin: 0 0 24px;"></div>

    {{-- Order summary --}}
    <div style="background: #FEF8F8; border: 1px solid #F5C6C6; border-radius: 12px; padding: 18px 22px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 12px;">Cancelled Order Summary</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 6px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0DADA;">Order Number</td>
                <td style="padding: 6px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0DADA; font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0DADA;">Order Date</td>
                <td style="padding: 6px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0DADA;">{{ $order->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-size: 13px; color: #777;">Order Total</td>
                <td style="padding: 6px 0; font-size: 14px; font-weight: 700; color: #C0392B; text-align: right;">{{ $order->formatted_total ?? '₦' . number_format($order->total ?? 0, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Refund info --}}
    <div style="background: #FFFBEB; border-left: 3px solid #F59E0B; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #B45309; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Refund Information</p>
        <p style="font-size: 13px; color: #555; margin: 0; line-height: 1.8;">
            If payment was made for this order, a full refund will be processed within <strong>3–5 business days</strong> back to your original payment method. If you do not receive your refund within this timeframe, please contact us immediately.
        </p>
    </div>

    {{-- What's next --}}
    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 14px;">What Would You Like to Do?</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🔄</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Place a New Order</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Visit our shop to browse fresh produce and place a new order at any time.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">💬</div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Speak to Our Team</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">If you believe this cancellation was a mistake, or you have concerns about your refund, reply to this email and we will resolve it immediately.</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/shop"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 36px; border-radius: 10px;">
            Browse the Shop Again &rarr;
        </a>
    </div>

    <p style="font-size: 13px; color: #888; text-align: center; margin: 0; line-height: 1.7;">
        We're sorry this order didn't work out. We hope to serve you again soon.
    </p>
    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
