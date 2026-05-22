<x-emails.layout subject="You're Subscribed — T-Akomz Farm Updates">

    <div style="text-align: center; padding: 10px 0 28px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #EBF9E0; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">🌱</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 24px; font-weight: 700; color: #1A1A1A; margin: 0 0 10px;">
            You're In. Welcome to the Farm.
        </h1>
        <p style="font-size: 14px; color: #666; margin: 0; line-height: 1.8; max-width: 420px; display: inline-block;">
            You have successfully subscribed to the T-Akomz Agro Estates newsletter. Here's exactly what you can expect in your inbox.
        </p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #D0EAC5, transparent); margin: 0 0 24px;"></div>

    {{-- What they'll receive --}}
    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 16px;">What You'll Receive</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🆕</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">New Product Alerts</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Be the first to know when new farm produce and products are added to the shop.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🏷️</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Exclusive Promotions</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Subscriber-only discount codes, seasonal offers, and limited-time deals — before they go public.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">📖</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Farm Journal Stories</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Behind-the-scenes updates, farming tips, seasonal harvest news, and stories from our fields in Ekiti State.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🗓️</div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Farm Tour Announcements</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Upcoming tour dates, special events, and early-bird booking invitations for our subscribers.</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/shop"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 15px 40px; border-radius: 10px;">
            Visit the Farm Shop &rarr;
        </a>
    </div>

    <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 16px 20px; margin: 0 0 24px; text-align: center;">
        <p style="font-size: 12px; color: #999; margin: 0; line-height: 1.7;">
            You subscribed with <strong style="color: #555;">{{ $email }}</strong>. We send at most one email per week — no spam, ever.<br>
            You can unsubscribe at any time by clicking the link at the bottom of any future email.
        </p>
    </div>

    <p style="font-size: 14px; color: #1A1A1A; font-weight: 700; margin: 16px 0 2px;">The T-Akomz Team</p>
    <p style="font-size: 12px; color: #999; margin: 0;">T-Akomz Agro Estates Ltd · Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria</p>

</x-emails.layout>
