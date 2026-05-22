<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $subject ?? 'T-Akomz Agro Estates' }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: #0a0a0a; font-family: 'Helvetica Neue', Arial, sans-serif; color: #f0f0f0; -webkit-font-smoothing: antialiased; }
  .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
  .header { background: #111111; border-radius: 16px 16px 0 0; padding: 32px 32px 24px; border-bottom: 1px solid #1e1e1e; text-align: center; }
  .logo-mark { display: inline-block; margin-bottom: 12px; }
  .brand-name { font-size: 20px; font-weight: 700; letter-spacing: 3px; color: #B8F397; text-transform: uppercase; }
  .brand-sub { font-size: 10px; letter-spacing: 4px; color: #666; text-transform: uppercase; margin-top: 2px; }
  .body { background: #111111; padding: 32px; border-top: 2px solid #B8F397; }
  .title { font-size: 24px; font-weight: 700; color: #f5f5f5; margin-bottom: 8px; line-height: 1.3; }
  .subtitle { font-size: 15px; color: #999; margin-bottom: 28px; line-height: 1.6; }
  .green-box { background: rgba(184, 243, 151, 0.06); border: 1px solid rgba(184, 243, 151, 0.2); border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
  .order-num { font-size: 13px; color: #999; margin-bottom: 4px; letter-spacing: 1px; text-transform: uppercase; }
  .order-val { font-size: 22px; font-weight: 700; color: #B8F397; font-family: 'Courier New', monospace; }
  .divider { height: 1px; background: #1e1e1e; margin: 24px 0; }
  .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  .items-table th { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #666; padding: 8px 0; border-bottom: 1px solid #222; text-align: left; }
  .items-table td { padding: 12px 0; border-bottom: 1px solid #1a1a1a; font-size: 14px; vertical-align: top; }
  .items-table td:last-child { text-align: right; color: #B8F397; font-weight: 600; }
  .item-name { color: #f0f0f0; font-weight: 500; }
  .item-qty { color: #888; font-size: 12px; margin-top: 2px; }
  .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
  .totals-row.total { font-size: 18px; font-weight: 700; color: #B8F397; padding-top: 12px; border-top: 1px solid #2a2a2a; margin-top: 4px; }
  .totals-row span:first-child { color: #999; }
  .totals-row.total span:first-child { color: #f5f5f5; }
  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
  .info-block { background: #0d0d0d; border-radius: 10px; padding: 14px 16px; }
  .info-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #555; margin-bottom: 5px; }
  .info-val { font-size: 13px; color: #d0d0d0; line-height: 1.5; }
  .cta-btn { display: inline-block; background: #B8F397; color: #0a0a0a; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 15px; text-decoration: none; margin: 20px 0; letter-spacing: 0.5px; }
  .badge { display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; }
  .badge-green { background: rgba(184, 243, 151, 0.12); color: #B8F397; }
  .badge-blue { background: rgba(100, 180, 255, 0.12); color: #64b4ff; }
  .badge-orange { background: rgba(255, 165, 0, 0.12); color: #ffa500; }
  .footer { background: #0d0d0d; padding: 24px 32px; border-radius: 0 0 16px 16px; border-top: 1px solid #1a1a1a; }
  .footer-links { margin-bottom: 12px; }
  .footer-links a { color: #555; text-decoration: none; font-size: 12px; margin-right: 16px; }
  .footer-links a:hover { color: #B8F397; }
  .footer-copy { font-size: 11px; color: #444; line-height: 1.6; }
  .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; color: #555; margin-bottom: 14px; font-weight: 600; }
  @media (max-width: 480px) {
    .body { padding: 24px 20px; }
    .info-grid { grid-template-columns: 1fr; }
    .header { padding: 24px 20px; }
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo-mark">
      <svg width="36" height="42" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M60 4 C60 4 12 62 12 94 C12 120 34 136 60 136 C86 136 108 120 108 94 C108 62 60 4 60 4 Z" stroke="#B8F397" stroke-width="5.5" fill="none" stroke-linejoin="round"/>
        <line x1="60" y1="118" x2="60" y2="60" stroke="#B8F397" stroke-width="4.5" stroke-linecap="round"/>
        <path d="M60 95 C60 95 36 88 34 68 C32 52 48 46 58 56" stroke="#B8F397" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M60 82 C60 82 84 76 86 56 C88 40 72 34 62 44" stroke="#B8F397" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M30 132 Q60 145 90 132" stroke="#B8F397" stroke-width="4" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="brand-name">T-AKOMZ</div>
    <div class="brand-sub">Agro Estates Ltd</div>
  </div>

  <div class="body">
    {{ $slot }}
  </div>

  <div class="footer">
    <div class="footer-links">
      <a href="{{ config('app.url') }}">Shop</a>
      <a href="{{ config('app.url') }}/account/orders">My Orders</a>
      <a href="{{ config('app.url') }}/contact">Contact Us</a>
      <a href="{{ config('app.url') }}/track">Track Order</a>
    </div>
    <div class="footer-copy">
      &copy; {{ date('Y') }} T-Akomz Agro Estates Ltd &bull; Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria<br>
      You received this email because you placed an order on takomzagro.com.
    </div>
  </div>
</div>
</body>
</html>
