<table>
    <thead>
        <tr>
            <th>Order #</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Subtotal (₦)</th>
            <th>Delivery Fee (₦)</th>
            <th>Discount (₦)</th>
            <th>Total (₦)</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Delivery Type</th>
            <th>Items</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->customer_name ?? $order->user?->name }}</td>
            <td>{{ $order->customer_email ?? $order->user?->email }}</td>
            <td>{{ $order->customer_phone ?? $order->user?->phone }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ number_format($order->subtotal, 2) }}</td>
            <td>{{ number_format($order->delivery_fee, 2) }}</td>
            <td>{{ number_format($order->discount ?? 0, 2) }}</td>
            <td>{{ number_format($order->total, 2) }}</td>
            <td>{{ str_replace('_', ' ', $order->payment_method ?? '') }}</td>
            <td>{{ $order->payment_status }}</td>
            <td>{{ str_replace('_', ' ', $order->delivery_type ?? '') }}</td>
            <td>{{ $order->items->sum('quantity') }} items</td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
