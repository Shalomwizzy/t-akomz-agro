<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\PaymentFailed;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\PaystackService;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    private array $nigerianStates = [
        'Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno',
        'Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','FCT (Abuja)','Gombe',
        'Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos',
        'Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers',
        'Sokoto','Taraba','Yobe','Zamfara',
    ];

    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        [$items, $subtotal] = $this->getCartData($cart);
        $discount    = session('discount', 0);
        $deliveryFee = (float) SiteSetting::get('delivery_fee_standard', 1500);
        $coupon      = session('coupon');

        $settings = SiteSetting::getMany([
            'delivery_fee_standard', 'delivery_fee_express', 'delivery_fee_pickup',
            'cod_allowed_states', 'active_payment_gateway',
        ]);

        $activeGateway  = $settings['active_payment_gateway'] ?? 'paystack';
        $defaultAddress = auth()->check() ? auth()->user()->defaultAddress : null;

        return view('checkout.index', compact(
            'items', 'subtotal', 'discount', 'deliveryFee', 'coupon',
            'settings', 'defaultAddress', 'activeGateway'
        ) + ['states' => $this->nigerianStates]);
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $isPickup = strtolower($request->input('delivery_type', '')) === 'pickup';

        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email'],
            'phone'         => ['required', 'string', 'max:20'],
            'street'        => [$isPickup ? 'nullable' : 'required', 'nullable', 'string', 'max:255'],
            'city'          => [$isPickup ? 'nullable' : 'required', 'nullable', 'string', 'max:100'],
            'state'         => [$isPickup ? 'nullable' : 'required', 'nullable', 'string', 'in:' . implode(',', $this->nigerianStates)],
            'notes'         => ['nullable', 'string', 'max:500'],
            'delivery_type' => ['required', 'in:pickup,standard,express'],
            'payment_method'=> ['required', 'in:paystack,flutterwave,bank_transfer,cod'],
        ]);

        // Map form fields to order columns
        $data['customer_name']    = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['customer_email']   = $data['email'];
        $data['customer_phone']   = $data['phone'];
        $data['delivery_address'] = $data['street'] ?? '';
        $data['delivery_city']    = $data['city'] ?? '';
        $data['delivery_state']   = $data['state'] ?? '';
        $data['delivery_notes']   = $data['notes'] ?? null;
        $data['delivery_type']    = strtoupper($data['delivery_type']);

        // Verify gateway key is present before creating the order (DB setting takes priority over .env)
        $paystackKey    = trim((string) (\App\Models\SiteSetting::get('paystack_secret_key') ?: config('services.paystack.secret_key')));
        $flutterwaveKey = trim((string) (\App\Models\SiteSetting::get('flutterwave_secret_key') ?: config('services.flutterwave.secret_key')));

        if ($data['payment_method'] === 'paystack' && strlen($paystackKey) < 10) {
            return back()->withInput()->with('error', 'Paystack is not configured. Add your secret key in Admin → Settings → Payments.');
        }
        if ($data['payment_method'] === 'flutterwave' && strlen($flutterwaveKey) < 10) {
            return back()->withInput()->with('error', 'Flutterwave is not configured. Add your secret key in Admin → Settings → Payments.');
        }

        [$items, $subtotal] = $this->getCartData($cart);
        $discount = session('discount', 0);
        $coupon   = session('coupon');

        $deliveryFee = match ($data['delivery_type']) {
            'PICKUP'  => 0,
            'EXPRESS' => (float) SiteSetting::get('delivery_fee_express', 3500),
            default   => (float) SiteSetting::get('delivery_fee_standard', 1500),
        };

        if ($coupon && $coupon['type'] === 'FREE_DELIVERY') {
            $deliveryFee = 0;
        }

        $total = max(0, $subtotal - $discount + $deliveryFee);

        $order = DB::transaction(function () use ($data, $items, $subtotal, $discount, $deliveryFee, $total, $coupon) {
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'user_id'          => auth()->id(),
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'],
                'customer_phone'   => $data['customer_phone'],
                'delivery_address' => $data['delivery_address'],
                'delivery_city'    => $data['delivery_city'],
                'delivery_state'   => $data['delivery_state'],
                'delivery_notes'   => $data['delivery_notes'],
                'delivery_type'    => $data['delivery_type'],
                'delivery_fee'     => $deliveryFee,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'total'            => $total,
                'coupon_code'      => $coupon['code'] ?? null,
                'status'           => 'PENDING',
                'payment_status'   => 'UNPAID',
                'payment_method'   => $data['payment_method'],
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id'   => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_unit' => $item['product']->unit,
                    'quantity'     => $item['qty'],
                    'unit_price'   => $item['product']->price,
                    'total_price'  => $item['lineTotal'],
                ]);
                $item['product']->decrement('stock', $item['qty']);
            }

            $order->addTimeline('PENDING', 'Order placed successfully.');

            if (!empty($coupon['code'])) {
                \App\Models\Coupon::where('code', $coupon['code'])->increment('used_count');
            }

            return $order;
        });

        session()->forget(['cart', 'coupon', 'discount']);

        // Redirect to payment gateway
        if ($data['payment_method'] === 'paystack') {
            return $this->redirectToPaystack($order);
        }

        if ($data['payment_method'] === 'flutterwave') {
            return $this->redirectToFlutterwave($order);
        }

        // Bank transfer or COD — go straight to success
        return redirect()->route('checkout.success', ['order' => $order->order_number]);
    }

    // ─── Paystack ─────────────────────────────────────────────────────────────

    private function redirectToPaystack(Order $order)
    {
        try {
            $data = app(PaystackService::class)->initialize($order);
            session(['pending_order' => $order->order_number]);
            return redirect()->away($data['authorization_url']);
        } catch (\Throwable $e) {
            Log::error('Paystack redirect failed', ['order' => $order->order_number, 'error' => $e->getMessage()]);
            $order->addTimeline('PENDING', 'Payment gateway error — awaiting manual payment.');
            return redirect()->route('checkout.success', ['order' => $order->order_number])
                             ->with('warning', 'Your order was placed but we could not reach Paystack. Send payment proof to us via WhatsApp or bank transfer and quote order #' . $order->order_number . '.');
        }
    }

    public function paystackCallback(Request $request)
    {
        $reference = $request->input('reference') ?? $request->input('trxref');

        if (!$reference) {
            return redirect()->route('shop.index')->with('error', 'Invalid payment callback.');
        }

        try {
            $paystack = app(PaystackService::class);
            $data     = $paystack->verify($reference);
            $order    = $paystack->processVerifiedPayment($data);

            if ($order && $order->payment_status === 'PAID') {
                session()->forget('pending_order');
                return redirect()->route('checkout.success', ['order' => $order->order_number])
                                 ->with('success', 'Payment confirmed! Your order is being processed.');
            }
        } catch (\Throwable $e) {
            Log::error('Paystack callback error', ['error' => $e->getMessage()]);
        }

        // Payment failed — send email if we have the order
        $orderNumber = session('pending_order');
        if ($orderNumber) {
            $failedOrder = Order::where('order_number', $orderNumber)->first();
            if ($failedOrder && $failedOrder->customer_email) {
                try { Mail::to($failedOrder->customer_email)->send(new PaymentFailed($failedOrder)); } catch (\Throwable) {}
            }
        }

        return redirect()->route('checkout.success', ['order' => $orderNumber])
                         ->with('info', 'Payment pending. We will confirm your order shortly.');
    }

    // ─── Flutterwave ──────────────────────────────────────────────────────────

    private function redirectToFlutterwave(Order $order)
    {
        try {
            $data = app(FlutterwaveService::class)->initialize($order);
            session(['pending_order' => $order->order_number]);
            return redirect()->away($data['payment_link']);
        } catch (\Throwable $e) {
            Log::error('Flutterwave redirect failed', ['order' => $order->order_number, 'error' => $e->getMessage()]);
            $order->addTimeline('PENDING', 'Payment gateway error — awaiting manual payment.');
            return redirect()->route('checkout.success', ['order' => $order->order_number])
                             ->with('warning', 'Your order was placed but we could not reach Flutterwave. Send payment proof to us via WhatsApp or bank transfer and quote order #' . $order->order_number . '.');
        }
    }

    public function flutterwaveCallback(Request $request)
    {
        $status        = $request->input('status');
        $transactionId = $request->input('transaction_id');
        $txRef         = $request->input('tx_ref');

        if ($status === 'cancelled') {
            $order = Order::where('order_number', $txRef)->first();
            return redirect()->route('cart.index')->with('info', 'Payment was cancelled. Your order is saved as pending.');
        }

        if (!$transactionId) {
            return redirect()->route('shop.index')->with('error', 'Invalid payment callback.');
        }

        try {
            $flw   = app(FlutterwaveService::class);
            $data  = $flw->verify($transactionId);
            $order = $flw->processVerifiedPayment($data);

            if ($order && $order->payment_status === 'PAID') {
                session()->forget('pending_order');
                return redirect()->route('checkout.success', ['order' => $order->order_number])
                                 ->with('success', 'Payment confirmed! Your order is being processed.');
            }
        } catch (\Throwable $e) {
            Log::error('Flutterwave callback error', ['error' => $e->getMessage()]);
        }

        // Payment failed — send email if we have the order
        $orderNumber = session('pending_order') ?? $txRef;
        if ($orderNumber) {
            $failedOrder = Order::where('order_number', $orderNumber)->first();
            if ($failedOrder && $failedOrder->customer_email) {
                try { Mail::to($failedOrder->customer_email)->send(new PaymentFailed($failedOrder)); } catch (\Throwable) {}
            }
        }

        return redirect()->route('checkout.success', ['order' => $orderNumber])
                         ->with('info', 'Payment pending. We will confirm your order shortly.');
    }

    // ─── Success ──────────────────────────────────────────────────────────────

    public function success(Request $request)
    {
        $orderNumber = $request->input('order') ?? session('pending_order');

        $order = null;
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->with('items')->first();

            if ($order) {
                if (auth()->check()) {
                    // Authenticated users may only see their own orders
                    if ($order->user_id && $order->user_id !== auth()->id()) {
                        $order = null;
                    }
                } else {
                    // Guests may only view via the session token set during checkout
                    if (session('pending_order') !== $orderNumber) {
                        $order = null;
                    }
                }
            }
        }

        return view('checkout.success', compact('order'));
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function getCartData(array $cart): array
    {
        $items    = [];
        $subtotal = 0;

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        foreach ($cart as $productId => $qty) {
            if ($products->has($productId)) {
                $product   = $products[$productId];
                $lineTotal = $product->price * $qty;
                $items[]   = compact('product', 'qty', 'lineTotal');
                $subtotal += $lineTotal;
            }
        }

        return [$items, $subtotal];
    }
}
