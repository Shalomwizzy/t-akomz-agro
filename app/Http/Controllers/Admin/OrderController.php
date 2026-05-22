<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderCancelled;
use App\Mail\OrderConfirmed;
use App\Mail\OrderDelivered;
use App\Mail\OrderDispatched;
use App\Mail\OrderRefunded;
use App\Models\Order;
use App\Services\OneSignalService;
use App\Services\TermiiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    private array $statuses = [
        'PENDING','CONFIRMED','PROCESSING','PACKED',
        'DISPATCHED','OUT_FOR_DELIVERY','DELIVERED','CANCELLED','REFUNDED',
    ];

    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders   = $query->paginate(25)->withQueryString();
        $statuses = $this->statuses;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'timeline', 'user');
        $statuses = $this->statuses;
        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'  => ['required', 'in:' . implode(',', $this->statuses)],
            'note'    => ['nullable', 'string', 'max:500'],
            'payment_status' => ['nullable', 'in:UNPAID,PAID,PARTIALLY_PAID,REFUNDED'],
        ]);

        $order->update([
            'status'         => $data['status'],
            'payment_status' => $data['payment_status'] ?? $order->payment_status,
        ]);

        $order->addTimeline($data['status'], $data['note'] ?? null);

        $order->load('user', 'items');

        $this->dispatchNotifications($order, $data['status']);

        return back()->with('success', "Order status updated to {$data['status']}.");
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    private function dispatchNotifications(Order $order, string $status): void
    {
        $user = $order->user;

        // Email
        try {
            $mailable = match ($status) {
                'CONFIRMED'                      => new OrderConfirmed($order),
                'DISPATCHED', 'OUT_FOR_DELIVERY' => new OrderDispatched($order),
                'DELIVERED'                      => new OrderDelivered($order),
                'CANCELLED'                      => new OrderCancelled($order),
                'REFUNDED'                       => new OrderRefunded($order),
                default                          => null,
            };

            if ($mailable && $user?->email) {
                Mail::to($user->email)->send($mailable);
            }
        } catch (\Throwable $e) {
            Log::error('Order email failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        // SMS
        if ($user?->phone) {
            try {
                $sms  = app(TermiiService::class);
                $num  = $order->order_number;
                $tot  = $order->formatted_total;
                match ($status) {
                    'CONFIRMED'                      => $sms->sendOrderConfirmed($user->phone, $num, $tot),
                    'DISPATCHED', 'OUT_FOR_DELIVERY' => $sms->sendOrderDispatched($user->phone, $num),
                    'DELIVERED'                      => $sms->sendOrderDelivered($user->phone, $num),
                    'CANCELLED'                      => $sms->sendOrderCancelled($user->phone, $num),
                    default                          => null,
                };
            } catch (\Throwable $e) {
                Log::error('Order SMS failed', ['order' => $order->id, 'error' => $e->getMessage()]);
            }
        }

        // Push Notification
        try {
            $pushStatuses = ['CONFIRMED', 'DISPATCHED', 'OUT_FOR_DELIVERY', 'DELIVERED', 'CANCELLED', 'REFUNDED'];
            if (in_array($status, $pushStatuses)) {
                $orderUrl = route('account.orders.show', $order);
                app(OneSignalService::class)->sendOrderUpdate($order->order_number, $status, $orderUrl);
            }
        } catch (\Throwable $e) {
            Log::error('Order push notification failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $filename = 'orders-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new OrdersExport(
                status:   $request->status ?: null,
                dateFrom: $request->date_from ?: null,
                dateTo:   $request->date_to ?: null,
            ),
            $filename
        );
    }
}
