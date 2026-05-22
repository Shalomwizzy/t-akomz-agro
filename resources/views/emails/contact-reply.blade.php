<x-emails.layout subject="We Received Your Message — T-Akomz Agro Estates">

    <div style="text-align: center; padding: 10px 0 28px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #EBF9E0; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">✅</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 24px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px;">
            Message Received, {{ explode(' ', trim($senderName))[0] }}.
        </h1>
        <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.8; max-width: 420px; display: inline-block;">
            Thank you for reaching out to T-Akomz Agro Estates. We've received your message and one of our team members will respond personally within <strong>24 hours</strong>.
        </p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #D0EAC5, transparent); margin: 0 0 24px;"></div>

    {{-- Message summary --}}
    <div style="background: #F7FDF4; border: 1px solid #C8E6B8; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 14px;">Your Message Summary</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA; width: 35%;">Subject</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #E2F0DA;">{{ $topic }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Sent</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right;">{{ now()->format('d F Y, g:i A') }}</td>
            </tr>
        </table>
        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #E2F0DA;">
            <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin: 0 0 8px;">Your Message</p>
            <p style="font-size: 13px; color: #444; margin: 0; line-height: 1.8; font-style: italic;">"{{ $userMessage }}"</p>
        </div>
    </div>

    {{-- While you wait --}}
    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 16px;">While You Wait</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🛒</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Browse Our Farm Shop</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Fresh poultry, eggs, livestock, vegetables and more — all farm-direct from Ekiti State.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🌿</div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Read Our Farm Journal</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Farming insights, tips, and stories straight from the fields of T-Akomz Agro Estates.</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/shop"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 36px; border-radius: 10px; margin-right: 10px;">
            Shop Now &rarr;
        </a>
    </div>

    <div style="background: linear-gradient(135deg, #EBF9E0, #F5FCF0); border-left: 3px solid #2E7A10; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #2E7A10; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Need Urgent Help?</p>
        <p style="font-size: 13px; color: #444; margin: 0; line-height: 1.8;">
            For urgent matters, you can also reach us directly on WhatsApp or by phone. Our team operates Monday – Saturday, 8 AM – 6 PM (WAT).
        </p>
    </div>

    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
