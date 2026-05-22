<x-emails.layout subject="Welcome to T-Akomz Agro Estates — Your Account Is Ready">

    {{-- Hero greeting --}}
    <div style="text-align: center; padding: 10px 0 28px;">
        <div style="display: inline-block; width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #EBF9E0, #D4F5BC); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <span style="font-size: 34px; line-height: 1;">🌾</span>
        </div>
        <h1 style="font-family: 'Georgia', serif; font-size: 26px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px; line-height: 1.3;">
            Welcome to T-Akomz Agro Estates,<br>
            <span style="color: #2E7A10;">{{ explode(' ', trim($user->name))[0] }}</span>.
        </h1>
        <p style="font-size: 15px; color: #555; margin: 0; line-height: 1.8; max-width: 420px; display: inline-block;">
            Your account is live. You now have direct access to the finest farm-fresh produce from the heart of Ekiti State, Nigeria — grown with care, delivered with pride.
        </p>
    </div>

    {{-- Divider --}}
    <div style="height: 1px; background: linear-gradient(to right, transparent, #D0EAC5, transparent); margin: 0 0 28px;"></div>

    {{-- Account summary card --}}
    <div style="background: #F7FDF4; border: 1px solid #C8E6B8; border-radius: 12px; padding: 20px 24px; margin: 0 0 28px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 14px;">Your Account Details</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA;">Full Name</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #E2F0DA;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #E2F0DA;">Email Address</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #E2F0DA;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Member Since</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right;">{{ $user->created_at->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- What you can do --}}
    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 16px;">Everything That's Now Yours</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 18px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; display: table; text-align: center;">
                    <span style="display: table-cell; vertical-align: middle; font-size: 18px;">🛒</span>
                </div>
            </td>
            <td style="vertical-align: top; padding: 0 0 18px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Shop Farm-Fresh Produce</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Browse poultry, eggs, livestock, vegetables, dairy, and organic inputs — all sourced directly from our 50-acre estate in Ido Ekiti.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 18px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; display: table; text-align: center;">
                    <span style="display: table-cell; vertical-align: middle; font-size: 18px;">📦</span>
                </div>
            </td>
            <td style="vertical-align: top; padding: 0 0 18px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Track Every Order in Real Time</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Log into your dashboard to follow your order from confirmation through packing, dispatch, and final delivery — every step, notified.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 18px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; display: table; text-align: center;">
                    <span style="display: table-cell; vertical-align: middle; font-size: 18px;">❤️</span>
                </div>
            </td>
            <td style="vertical-align: top; padding: 0 0 18px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Save Favourites to Your Wishlist</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Shortlist products you love and get reminded when they're back in stock or on promotion.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; display: table; text-align: center;">
                    <span style="display: table-cell; vertical-align: middle; font-size: 18px;">🌿</span>
                </div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Book a Farm Tour</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Visit us in Ido Ekiti and experience the farm firsthand — meet the animals, walk the fields, and see how your food is grown.</p>
            </td>
        </tr>
    </table>

    {{-- CTA --}}
    <div style="text-align: center; margin: 0 0 28px;">
        <a href="{{ config('app.url') }}/shop"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 15px 40px; border-radius: 10px; letter-spacing: 0.3px;">
            Explore the Farm Shop &rarr;
        </a>
        <p style="font-size: 12px; color: #aaa; margin: 12px 0 0;">No minimum order. Free account. Cancel anytime.</p>
    </div>

    {{-- Freshness promise --}}
    <div style="background: linear-gradient(135deg, #EBF9E0, #F5FCF0); border-left: 3px solid #2E7A10; border-radius: 0 10px 10px 0; padding: 18px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #2E7A10; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Our Promise to You</p>
        <p style="font-size: 13px; color: #444; margin: 0; line-height: 1.8;">
            Every product you order is sourced <strong>directly from T-Akomz Agro Estates</strong> — no warehouses, no middlemen. Produce is harvested or prepared as close to your delivery date as possible. If you're ever unsatisfied with anything, we will make it right, no questions asked.
        </p>
    </div>

    {{-- Sign-off --}}
    <p style="font-size: 14px; color: #555; line-height: 1.9; margin: 0 0 6px;">
        We're glad you're here. If you have any questions — about a product, an order, or anything else — simply reply to this email. A real person on our team will get back to you.
    </p>
    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
