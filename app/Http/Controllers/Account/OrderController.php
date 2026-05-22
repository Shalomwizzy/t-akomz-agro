<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product', 'timeline');

        return view('account.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->payment_status === 'PAID', 403);

        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (!$order->is_cancellable) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->load('items.product');
        $order->update(['status' => 'CANCELLED']);
        $order->addTimeline('CANCELLED', 'Order cancelled by customer.');

        // Restore stock
        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Order cancelled successfully.');
    }
}
