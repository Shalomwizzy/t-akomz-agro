<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Invoice {{ $order->order_number }}</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1a1a1a; background: #fff; line-height: 1.5; }

  .page { padding: 40px 48px; }

  /* Header */
  .header { display: table; width: 100%; margin-bottom: 36px; }
  .header-left  { display: table-cell; width: 50%; vertical-align: top; }
  .header-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }
  .brand-name { font-size: 22px; font-weight: 700; color: #2d6a16; letter-spacing: 2px; text-transform: uppercase; }
  .brand-sub  { font-size: 10px; color: #888; letter-spacing: 3px; text-transform: uppercase; margin-top: 2px; }
  .brand-addr { font-size: 11px; color: #666; margin-top: 10px; line-height: 1.7; }

  .invoice-title { font-size: 28px; font-weight: 700; color: #1a1a1a; }
  .invoice-meta  { font-size: 12px; color: #555; margin-top: 6px; line-height: 1.8; }
  .invoice-meta strong { color: #1a1a1a; }

  /* Status badge */
  .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; letter-spacing: 0.5px; }
  .badge-paid      { background: #dcfce7; color: #15803d; }
  .badge-pending   { background: #fef9c3; color: #854d0e; }
  .badge-delivered { background: #dcfce7; color: #15803d; }

  /* Divider */
  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
  .divider-thick { border: none; border-top: 2px solid #2d6a16; margin: 0 0 24px; }

  /* Billing block */
  .billing-row { display: table; width: 100%; margin-bottom: 28px; }
  .billing-col { display: table-cell; width: 50%; vertical-align: top; }
  .section-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #999; font-weight: 600; margin-bottom: 6px; }
  .billing-name  { font-size: 14px; font-weight: 600; color: #1a1a1a; }
  .billing-info  { font-size: 12px; color: #555; line-height: 1.7; }

  /* Items table */
  .items-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  .items-table thead tr { background: #f4fef0; border-bottom: 2px solid #2d6a16; }
  .items-table th { padding: 10px 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #2d6a16; text-align: left; }
  .items-table th:last-child, .items-table td:last-child { text-align: right; }
  .items-table td { padding: 12px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #333; vertical-align: top; }
  .items-table tbody tr:last-child td { border-bottom: none; }
  .item-name { font-weight: 600; color: #1a1a1a; }
  .item-unit { font-size: 11px; color: #888; margin-top: 2px; }

  /* Totals */
  .totals-wrap { display: table; width: 100%; margin-top: 16px; }
  .totals-spacer { display: table-cell; width: 55%; }
  .totals-block  { display: table-cell; width: 45%; }
  .totals-row { display: table; width: 100%; padding: 5px 0; border-top: 1px solid #f0f0f0; }
  .totals-row:first-child { border-top: none; }
  .totals-label { display: table-cell; width: 55%; font-size: 13px; color: #666; }
  .totals-value { display: table-cell; width: 45%; text-align: right; font-size: 13px; color: #1a1a1a; font-weight: 500; }
  .totals-total-row { display: table; width: 100%; padding: 10px 0 0; border-top: 2px solid #2d6a16; margin-top: 4px; }
  .totals-total-label { display: table-cell; width: 55%; font-size: 15px; font-weight: 700; color: #1a1a1a; }
  .totals-total-value { display: table-cell; width: 45%; text-align: right; font-size: 15px; font-weight: 700; color: #2d6a16; }

  /* Footer */
  .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; }
  .footer-text { font-size: 11px; color: #999; line-height: 1.8; }
  .footer-thanks { font-size: 13px; font-weight: 600; color: #2d6a16; margin-bottom: 6px; }

  /* Payment info */
  .payment-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px 16px; margin-top: 24px; }
  .payment-grid { display: table; width: 100%; }
  .payment-item { display: table-cell; width: 33.33%; vertical-align: top; }
</style>
</head>
<body>
<div class="page">

  {{-- Header --}}
  <div class="header">
    <div class="header-left">
      <div class="brand-name">T-AKOMZ</div>
      <div class="brand-sub">Agro Estates Ltd</div>
      <div class="brand-addr">
        Oke-Ido Road, Ido Ekiti<br>
        Ekiti State, Nigeria<br>
        admin@takomzagro.com
      </div>
    </div>
    <div class="header-right">
      <div class="invoice-title">INVOICE</div>
      <div class="invoice-meta">
        <strong>{{ $order->order_number }}</strong><br>
        Date: {{ $order->created_at->format('d M Y') }}<br>
        @if($order->payment_status === 'PAID')
        Paid: {{ now()->format('d M Y') }}<br>
        @endif
        Status:
        <span class="badge {{ $order->payment_status === 'PAID' ? 'badge-paid' : 'badge-pending' }}">
          {{ $order->payment_status }}
        </span>
      </div>
    </div>
  </div>

  <hr class="divider-thick">

  {{-- Billing / Delivery --}}
  <div class="billing-row">
    <div class="billing-col">
      <div class="section-label">Billed To</div>
      <div class="billing-name">{{ $order->user->name }}</div>
      <div class="billing-info">
        {{ $order->user->email }}<br>
        @if($order->user->phone){{ $order->user->phone }}<br>@endif
      </div>
    </div>
    <div class="billing-col">
      <div class="section-label">Deliver To</div>
      @if($order->delivery_type === 'pickup')
      <div class="billing-info">Farm Pickup — Ido Ekiti</div>
      @elseif($order->delivery_address)
      <div class="billing-name">Delivery Address</div>
      <div class="billing-info">
        {{ $order->delivery_address }}<br>
        {{ $order->delivery_city }}, {{ $order->delivery_state }}
        @if($order->delivery_notes)<br><em>Note: {{ $order->delivery_notes }}</em>@endif
      </div>
      @else
      <div class="billing-info">Not specified</div>
      @endif
    </div>
  </div>

  {{-- Items --}}
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:45%">Product</th>
        <th style="width:15%; text-align:center">Qty</th>
        <th style="width:20%; text-align:right">Unit Price</th>
        <th style="width:20%">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
      <tr>
        <td>
          <div class="item-name">{{ $item->product_name }}</div>
          @if($item->unit)<div class="item-unit">{{ $item->unit }}</div>@endif
        </td>
        <td style="text-align:center; color:#555">{{ $item->quantity }}</td>
        <td style="text-align:right; color:#555">&#8358;{{ number_format($item->unit_price ?? ($item->price ?? 0), 2) }}</td>
        <td style="text-align:right; font-weight:600; color:#1a1a1a">&#8358;{{ number_format($item->subtotal ?? ($item->line_total ?? 0), 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Totals --}}
  <div class="totals-wrap">
    <div class="totals-spacer"></div>
    <div class="totals-block">
      <div class="totals-row">
        <div class="totals-label">Subtotal</div>
        <div class="totals-value">&#8358;{{ number_format($order->subtotal, 2) }}</div>
      </div>
      @if($order->delivery_fee > 0)
      <div class="totals-row">
        <div class="totals-label">Delivery Fee</div>
        <div class="totals-value">&#8358;{{ number_format($order->delivery_fee, 2) }}</div>
      </div>
      @endif
      @if(($order->discount ?? 0) > 0)
      <div class="totals-row">
        <div class="totals-label">Discount</div>
        <div class="totals-value" style="color:#15803d">-&#8358;{{ number_format($order->discount, 2) }}</div>
      </div>
      @endif
      <div class="totals-total-row">
        <div class="totals-total-label">Total</div>
        <div class="totals-total-value">&#8358;{{ number_format($order->total, 2) }}</div>
      </div>
    </div>
  </div>

  {{-- Payment info box --}}
  <div class="payment-box">
    <div class="payment-grid">
      <div class="payment-item">
        <div class="section-label">Payment Method</div>
        <div style="font-size:13px; color:#1a1a1a; margin-top:3px">{{ str_replace('_', ' ', $order->payment_method ?? 'Online') }}</div>
      </div>
      <div class="payment-item">
        <div class="section-label">Payment Status</div>
        <div style="font-size:13px; color:#1a1a1a; margin-top:3px">{{ $order->payment_status }}</div>
      </div>
      <div class="payment-item">
        <div class="section-label">Order Status</div>
        <div style="font-size:13px; color:#1a1a1a; margin-top:3px">{{ str_replace('_', ' ', $order->status) }}</div>
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <div class="footer-thanks">Thank you for choosing T-Akomz Agro Estates!</div>
    <div class="footer-text">
      Fresh from our farm to your table &bull; takomzagro.com<br>
      For enquiries: admin@takomzagro.com &bull; Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria<br>
      This is a computer-generated invoice and does not require a signature.
    </div>
  </div>

</div>
</body>
</html>
