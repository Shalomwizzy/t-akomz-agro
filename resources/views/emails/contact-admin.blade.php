<x-emails.layout subject="New Contact Form Submission">

    <div style="text-align: center; padding: 10px 0 24px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #FEF3C7; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">📩</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 22px; font-weight: 700; color: #1A1A1A; margin: 0 0 8px;">New Contact Form Message</h1>
        <p style="font-size: 13px; color: #666; margin: 0;">Received {{ now()->format('d F Y \a\t g:i A') }} · Reply within 24 hours</p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #E0E0E0, transparent); margin: 0 0 24px;"></div>

    {{-- Sender info --}}
    <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 14px;">Sender Details</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0F0F0; width: 30%;">Full Name</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0F0F0;">{{ $senderName }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0F0F0;">Email</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #2E7A10; text-align: right; border-bottom: 1px solid #F0F0F0;">
                    <a href="mailto:{{ $senderEmail }}" style="color: #2E7A10; text-decoration: none;">{{ $senderEmail }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #F0F0F0;">Phone</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #F0F0F0;">{{ $senderPhone ?: '—' }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Subject</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right;">{{ $topic }}</td>
            </tr>
        </table>
    </div>

    {{-- Message body --}}
    <div style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #888; margin: 0 0 12px;">Message</p>
        <p style="font-size: 14px; color: #333; margin: 0; line-height: 1.9; white-space: pre-line;">{{ $userMessage }}</p>
    </div>

    {{-- Action buttons --}}
    <div style="text-align: center; margin: 0 0 24px;">
        <a href="mailto:{{ $senderEmail }}"
           style="display: inline-block; background: #2E7A10; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 32px; border-radius: 10px; margin-right: 8px;">
            Reply to {{ explode(' ', trim($senderName))[0] }} &rarr;
        </a>
        <a href="{{ config('app.url') }}/admin"
           style="display: inline-block; background: #F3F4F6; color: #374151; font-size: 14px; font-weight: 600; text-decoration: none; padding: 13px 24px; border-radius: 10px;">
            Open Admin Panel
        </a>
    </div>

    <p style="font-size: 12px; color: #aaa; text-align: center; margin: 0; line-height: 1.7;">
        This message was submitted via the contact form at {{ config('app.url') }}/contact.<br>
        Reply directly to this email to respond to the sender.
    </p>

</x-emails.layout>
