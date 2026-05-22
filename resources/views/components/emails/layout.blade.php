<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<title>{{ $subject ?? 'T-Akomz Agro Estates' }}</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
<style>
/* ── RESET ── */
*, *::before, *::after { box-sizing: border-box; }
body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; }
img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; display: block; }
a { color: inherit; }

/* ── BASE ── */
body {
    margin: 0; padding: 0;
    background-color: #F2F0EB;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #1A1A1A;
    -webkit-font-smoothing: antialiased;
}

/* ── WRAPPER ── */
.email-outer { background-color: #F2F0EB; padding: 32px 16px 48px; }
.email-inner { max-width: 600px; margin: 0 auto; }

/* ── HEADER ── */
.email-header {
    background: #050505;
    border-radius: 20px 20px 0 0;
    padding: 32px 40px 28px;
    text-align: center;
}
.logo-wrap { display: inline-block; margin-bottom: 14px; }
.brand-name {
    font-size: 22px; font-weight: 800;
    letter-spacing: 5px; color: #B8F397;
    text-transform: uppercase; display: block;
}
.brand-tagline {
    font-size: 10px; letter-spacing: 4px;
    color: rgba(255,255,255,0.75); text-transform: uppercase;
    display: block; margin-top: 2px;
}
.header-accent {
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #B8F397 20%, #6FAE4B 50%, #B8F397 80%, transparent 100%);
    margin: 0;
}

/* ── BODY ── */
.email-body {
    background: #FFFFFF;
    padding: 44px 40px 36px;
}
.email-icon-wrap {
    width: 68px; height: 68px;
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px;
    font-size: 30px;
}
.email-title {
    font-size: 26px; font-weight: 800;
    color: #0A0A0A; line-height: 1.25;
    margin: 0 0 10px;
    letter-spacing: -0.5px;
}
.email-subtitle {
    font-size: 15px; color: #5C5C5C;
    margin: 0 0 32px; line-height: 1.65;
}

/* ── INFO BOX ── */
.info-box {
    background: #F8F8F6;
    border: 1px solid #E8E6E0;
    border-left: 4px solid #B8F397;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 24px;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 8px 0;
    border-bottom: 1px solid #EEECE6;
    font-size: 14px;
}
.info-row:last-child { border-bottom: none; }
.info-label { color: #888880; font-weight: 500; flex-shrink: 0; padding-right: 16px; }
.info-value { color: #1A1A1A; font-weight: 600; text-align: right; }
.info-value.green { color: #3B8A1E; font-size: 18px; font-weight: 800; }
.info-value.mono { font-family: 'Courier New', Courier, monospace; color: #3B8A1E; font-size: 15px; font-weight: 700; }

/* ── HIGHLIGHT BOX ── */
.highlight-box {
    background: linear-gradient(135deg, #F0FBE8 0%, #E8F7DC 100%);
    border: 1px solid #B8F397;
    border-radius: 14px;
    padding: 22px 26px;
    margin-bottom: 28px;
    text-align: center;
}
.highlight-box .amount {
    font-size: 36px; font-weight: 900;
    color: #2E7A10; letter-spacing: -1px;
    display: block; margin-bottom: 4px;
}
.highlight-box .amount-label {
    font-size: 12px; color: #5A8040;
    font-weight: 600; letter-spacing: 1px;
    text-transform: uppercase;
}

/* ── ALERT BOX ── */
.alert-box-green {
    background: #F0FBE8; border: 1px solid #B8E89A; border-radius: 12px;
    padding: 18px 22px; margin-bottom: 24px;
}
.alert-box-red {
    background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px;
    padding: 18px 22px; margin-bottom: 24px;
}
.alert-box-yellow {
    background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px;
    padding: 18px 22px; margin-bottom: 24px;
}
.alert-title { font-weight: 700; font-size: 14px; margin: 0 0 6px; }
.alert-text { font-size: 13px; color: #555; margin: 0; line-height: 1.6; }

/* ── STEPS ── */
.steps-list { list-style: none; margin: 0; padding: 0; }
.step-item { display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid #F0EDE6; }
.step-item:last-child { border-bottom: none; }
.step-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: #050505; color: #B8F397;
    font-size: 13px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 2px;
}
.step-content h4 { margin: 0 0 3px; font-size: 14px; font-weight: 700; color: #0A0A0A; }
.step-content p { margin: 0; font-size: 13px; color: #666; line-height: 1.5; }

/* ── CTA BUTTON ── */
.cta-wrap { text-align: center; margin: 32px 0 24px; }
.cta-btn {
    display: inline-block;
    background: #050505; color: #B8F397 !important;
    padding: 16px 40px; border-radius: 12px;
    font-weight: 800; font-size: 15px;
    text-decoration: none !important;
    letter-spacing: 0.3px;
    border: 2px solid #B8F397;
}
.cta-btn-green {
    display: inline-block;
    background: #2E7A10; color: #FFFFFF !important;
    padding: 16px 40px; border-radius: 12px;
    font-weight: 800; font-size: 15px;
    text-decoration: none !important;
    letter-spacing: 0.3px;
}
.cta-btn-outline {
    display: inline-block;
    background: transparent; color: #050505 !important;
    padding: 14px 36px; border-radius: 12px;
    font-weight: 700; font-size: 14px;
    text-decoration: none !important;
    border: 2px solid #1A1A1A;
}

/* ── DIVIDER ── */
.divider { height: 1px; background: #EEECEA; margin: 28px 0; border: none; }

/* ── ITEMS TABLE ── */
.items-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.items-table th {
    font-size: 11px; text-transform: uppercase; letter-spacing: 1px;
    color: #999; padding: 8px 0; border-bottom: 2px solid #EEE; text-align: left;
}
.items-table td { padding: 12px 0; border-bottom: 1px solid #F5F3EE; vertical-align: top; }
.items-table td:last-child { text-align: right; font-weight: 700; color: #2E7A10; }
.total-row td { padding-top: 16px; font-weight: 800; font-size: 16px; border-top: 2px solid #DDD; border-bottom: none; }
.total-row td:last-child { color: #2E7A10; font-size: 20px; }

/* ── FOOTER ── */
.email-footer {
    background: #050505;
    border-radius: 0 0 20px 20px;
    padding: 28px 40px 32px;
}
.footer-logo-row { margin-bottom: 16px; }
.footer-links { margin-bottom: 16px; }
.footer-links a {
    color: rgba(255,255,255,0.7); font-size: 12px;
    text-decoration: none; margin-right: 18px;
}
.footer-copy { font-size: 11px; color: rgba(255,255,255,0.6); line-height: 1.8; }
.footer-copy a { color: #B8F397; text-decoration: none; }
.social-links { margin-bottom: 14px; }
.social-links a {
    display: inline-block; width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.5);
    text-decoration: none; font-size: 14px; text-align: center;
    line-height: 32px; margin-right: 8px;
}

/* ── MOBILE ── */
@media only screen and (max-width: 480px) {
    .email-outer { padding: 16px 8px 32px; }
    .email-header { padding: 24px 24px 20px; border-radius: 16px 16px 0 0; }
    .email-body { padding: 28px 24px 24px; }
    .email-footer { padding: 20px 24px 24px; border-radius: 0 0 16px 16px; }
    .email-title { font-size: 22px; }
    .info-row { flex-direction: column; gap: 2px; }
    .info-value { text-align: left; }
    .highlight-box .amount { font-size: 28px; }
    .cta-btn, .cta-btn-green, .cta-btn-outline { padding: 14px 28px; font-size: 14px; }
}
</style>
</head>
<body>
<div class="email-outer">
<div class="email-inner">

    {{-- HEADER --}}
    <div class="email-header">
        <div class="logo-wrap">
            <svg width="40" height="46" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M60 4 C60 4 12 62 12 94 C12 120 34 136 60 136 C86 136 108 120 108 94 C108 62 60 4 60 4 Z" stroke="#B8F397" stroke-width="5.5" fill="none" stroke-linejoin="round"/>
                <line x1="60" y1="118" x2="60" y2="60" stroke="#B8F397" stroke-width="4.5" stroke-linecap="round"/>
                <path d="M60 95 C60 95 36 88 34 68 C32 52 48 46 58 56" stroke="#B8F397" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M60 82 C60 82 84 76 86 56 C88 40 72 34 62 44" stroke="#B8F397" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M30 132 Q60 145 90 132" stroke="#B8F397" stroke-width="4" fill="none" stroke-linecap="round"/>
            </svg>
        </div>
        <span class="brand-name">T-AKOMZ</span>
        <span class="brand-tagline">Agro Estates Ltd · Ido Ekiti, Nigeria</span>
    </div>
    <div class="header-accent"></div>

    {{-- BODY --}}
    <div class="email-body">
        {{ $slot }}
    </div>

    {{-- FOOTER --}}
    <div class="email-footer">
        <div class="footer-links">
            <a href="{{ config('app.url') }}">Home</a>
            <a href="{{ config('app.url') }}/shop">Shop</a>
            <a href="{{ config('app.url') }}/account/orders">My Orders</a>
            <a href="{{ config('app.url') }}/farm-tour">Farm Tour</a>
            <a href="{{ config('app.url') }}/contact">Contact</a>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} T-Akomz Agro Estates Ltd &bull; Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria<br>
            This email was sent because of your activity on <a href="{{ config('app.url') }}">takomzagro.com</a>.
            If you did not initiate this, please contact us immediately.
        </div>
    </div>

</div>
</div>
</body>
</html>
