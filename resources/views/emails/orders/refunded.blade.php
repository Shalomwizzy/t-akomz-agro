<x-emails.layout subject="Refund Processed | T-Akomz Agro Estates">

    <div style="text-align: center; padding: 10px 0 24px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #EBF9E0; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">💚</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 24px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px;">
            Your Refund Has Been Processed
        </h1>
        <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.8;">
            Hi {{ $order->user?->name ?? $order->customer_name ?? 'Valued Customer' }}, we've successfully processed a refund for order <strong>{{ $order->order_number }}</strong>.
        </p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #D0EAC5, transparent); margin: 0 0 24px;"></div>

    {{-- Refund summary --}}
    <div style="background: #F7FDF4; border: 1px solid #C8E6B8; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 14px;">Refund Summary</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA; width: 40%;">Order Reference</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right; border-bottom: 1px solid #E2F0DA; font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA;">Refund Amount</td>
                <td style="padding: 7px 0; font-size: 15px; font-weight: 700; color: #2E7A10; text-align: right; border-bottom: 1px solid #E2F0DA;">{{ $order->formatted_total ?? '₦' . number_format($order->total ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA;">Original Order Date</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #E2F0DA;">{{ $order->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Refund Initiated</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right;">{{ now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Timeline --}}
    <div style="background: #FFFBEB; border-left: 3px solid #F59E0B; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #B45309; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">When Will It Arrive?</p>
        <p style="font-size: 13px; color: #555; margin: 0; line-height: 1.8;">
            Refunds typically reflect in your account within <strong>3–7 business days</strong>, depending on your bank or card provider. If you paid via Paystack or Flutterwave, the funds will be returned to the same card or bank account used at checkout.
        </p>
    </div>

    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 16px;">What Would You Like to Do?</p>
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🔄</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Place a New Order</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Our shop is always stocked with fresh produce. Browse and order again anytime.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">💬</div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Refund Not Arrived After 7 Days?</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Reply to this email and our team will investigate and resolve it immediately.</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/shop"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 36px; border-radius: 10px;">
            Return to the Shop &rarr;
        </a>
    </div>

    <p style="font-size: 13px; color: #666; line-height: 1.8; margin: 0 0 16px;">
        We're sorry this order didn't work out as expected. Your satisfaction is our priority and we hope to serve you again soon.
    </p>
    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
