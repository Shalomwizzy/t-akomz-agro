<x-emails.layout subject="Payment Not Completed | T-Akomz Agro Estates">

    <div style="text-align: center; padding: 10px 0 24px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #FEF2F2; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">💳</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 24px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px;">
            Your Payment Was Not Completed
        </h1>
        <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.8;">
            Hi {{ $order->user?->name ?? $order->customer_name ?? 'there' }}, we noticed that payment for your order was not completed. Your items are still reserved — here's how to finish your purchase.
        </p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #E0E0E0, transparent); margin: 0 0 24px;"></div>

    {{-- Order summary --}}
    <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 14px;">Order Details</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0F0F0; width: 40%;">Order Number</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0F0F0; font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0F0F0;">Order Date</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0F0F0;">{{ $order->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Amount Due</td>
                <td style="padding: 7px 0; font-size: 15px; font-weight: 700; color: #DC2626; text-align: right;">{{ $order->formatted_total ?? '₦' . number_format($order->total ?? 0, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Why it might have failed --}}
    <div style="background: #FEF2F2; border-left: 3px solid #DC2626; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #DC2626; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Common Reasons for Payment Failure</p>
        <ul style="font-size: 13px; color: #555; margin: 0; padding-left: 18px; line-height: 2;">
            <li>Insufficient funds in your account or on your card</li>
            <li>Card declined by your bank — contact your bank to allow the transaction</li>
            <li>Incorrect card details entered during checkout</li>
            <li>Session timed out before payment was completed</li>
            <li>Temporary issue with the payment gateway</li>
        </ul>
    </div>

    {{-- Retry CTA --}}
    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 14px;">Complete Your Purchase</p>
    <p style="font-size: 14px; color: #444; line-height: 1.8; margin: 0 0 20px;">
        Your cart items are still saved. Simply log in to your account and retry the payment — the whole process takes less than two minutes.
    </p>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/account/orders"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 15px 40px; border-radius: 10px;">
            Retry Payment &rarr;
        </a>
        <p style="font-size: 12px; color: #aaa; margin: 10px 0 0;">Secure payment powered by Paystack &amp; Flutterwave</p>
    </div>

    <div style="background: linear-gradient(135deg, #EBF9E0, #F5FCF0); border-left: 3px solid #2E7A10; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #2E7A10; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Need Help?</p>
        <p style="font-size: 13px; color: #444; margin: 0; line-height: 1.8;">
            If you continue to experience issues, reply to this email or contact us on WhatsApp and our team will assist you personally to complete your order.
        </p>
    </div>

    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
