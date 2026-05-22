<x-emails.layout subject="Low Stock Alert | T-Akomz Admin">

    <div style="text-align: center; padding: 10px 0 24px;">
        <div style="width: 68px; height: 68px; border-radius: 50%; background: #FEF3C7; margin: 0 auto 18px; line-height: 68px; font-size: 30px; text-align: center;">⚠️</div>
        <h1 style="font-family: 'Georgia', serif; font-size: 22px; font-weight: 700; color: #1A1A1A; margin: 0 0 8px;">Low Stock Alert</h1>
        <p style="font-size: 13px; color: #666; margin: 0;">Action required · {{ now()->format('d F Y, g:i A') }}</p>
    </div>

    <div style="height: 1px; background: linear-gradient(to right, transparent, #FCD34D, transparent); margin: 0 0 24px;"></div>

    {{-- Alert box --}}
    <div style="background: #FFFBEB; border: 1px solid #FCD34D; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #92400E; margin: 0 0 14px;">Product Stock Warning</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #FDE68A; width: 40%;">Product</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 700; color: #1A1A1A; text-align: right; border-bottom: 1px solid #FDE68A;">{{ $product->name }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #FDE68A;">SKU / ID</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #FDE68A; font-family: monospace;">{{ $product->sku ?? '#' . $product->id }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #FDE68A;">Category</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #1A1A1A; text-align: right; border-bottom: 1px solid #FDE68A;">{{ $product->category?->name ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777; border-bottom: 1px solid #FDE68A;">Current Stock</td>
                <td style="padding: 7px 0; font-size: 15px; font-weight: 700; color: #DC2626; text-align: right; border-bottom: 1px solid #FDE68A;">{{ $product->stock }} units remaining</td>
            </tr>
            <tr>
                <td style="padding: 7px 0; font-size: 13px; color: #777;">Low Stock Threshold</td>
                <td style="padding: 7px 0; font-size: 13px; font-weight: 600; color: #B45309; text-align: right;">{{ $product->low_stock_threshold ?? 10 }} units</td>
            </tr>
        </table>
    </div>

    {{-- Urgency message --}}
    <div style="background: #FEF2F2; border-left: 3px solid #DC2626; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 0 0 24px;">
        <p style="font-size: 13px; font-weight: 700; color: #DC2626; margin: 0 0 6px; text-transform: uppercase; letter-spacing: 0.5px;">Immediate Action Required</p>
        <p style="font-size: 13px; color: #555; margin: 0; line-height: 1.8;">
            This product has reached its low stock threshold. At the current sales rate, it may run out soon. Please restock immediately to avoid missed orders and disappointed customers.
        </p>
    </div>

    <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 16px;">Recommended Actions</p>
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 0 28px;">
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">✏️</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Update Stock Level</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Log into the admin panel and update the stock quantity for this product.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 16px 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">🚫</div>
            </td>
            <td style="vertical-align: top; padding: 0 0 16px;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Pause the Listing (if out of stock)</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">If restocking will take time, temporarily deactivate this product to prevent new orders.</p>
            </td>
        </tr>
        <tr>
            <td style="width: 44px; vertical-align: top; padding: 0 14px 0 0;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #EBF9E0; text-align: center; line-height: 38px; font-size: 18px;">📦</div>
            </td>
            <td style="vertical-align: top; padding: 0;">
                <p style="font-size: 14px; font-weight: 700; color: #1A1A1A; margin: 4px 0 4px;">Review Pending Orders</p>
                <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.6;">Check if any pending orders include this product and ensure they can still be fulfilled.</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin: 0 0 24px;">
        <a href="{{ config('app.url') }}/admin/products/{{ $product->id }}/edit"
           style="display: inline-block; background: #D97706; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 32px; border-radius: 10px; margin-right: 8px;">
            Update Stock Now &rarr;
        </a>
        <a href="{{ config('app.url') }}/admin/products"
           style="display: inline-block; background: #F3F4F6; color: #374151; font-size: 14px; font-weight: 600; text-decoration: none; padding: 13px 24px; border-radius: 10px;">
            View All Products
        </a>
    </div>

    <p style="font-size: 12px; color: #aaa; text-align: center; margin: 0; line-height: 1.7;">
        This is an automated alert from the T-Akomz Agro Estates inventory system.<br>
        You will receive this alert each time a product drops to or below its low stock threshold.
    </p>

</x-emails.layout>
