<?php

namespace App\Services;

use App\Mail\OrderAdminAlert;
use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlutterwaveService
{
    private string $secretKey;
    private string $secretHash;
    private string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $dbKey  = SiteSetting::get('flutterwave_secret_key', '');
        $dbHash = SiteSetting::get('flutterwave_secret_hash', '');
        $this->secretKey  = $dbKey  ?: config('services.flutterwave.secret_key', '');
        $this->secretHash = $dbHash ?: config('services.flutterwave.secret_hash', '');
    }

    /**
     * Create a Flutterwave hosted payment link.
     */
    public function initialize(Order $order): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", [
                'tx_ref'          => $order->order_number,
                'amount'          => $order->total,
                'currency'        => 'NGN',
                'redirect_url'    => route('checkout.flutterwave.callback'),
                'customer'        => [
                    'email'        => $order->customer_email,
                    'phonenumber'  => $order->customer_phone,
                    'name'         => $order->customer_name,
                ],
                'customizations'  => [
                    'title'       => 'T-Akomz Agro Estates',
                    'description' => "Payment for order {$order->order_number}",
                    'logo'        => asset('images/logo-mark.svg'),
                ],
                'meta'            => [
                    'order_number' => $order->order_number,
                ],
            ]);

        if (!$response->successful() || $response->json('status') !== 'success') {
            Log::error('Flutterwave init failed', ['response' => $response->json()]);
            throw new \RuntimeException('Could not initialize Flutterwave payment: ' . $response->json('message', 'Unknown error'));
        }

        return [
            'payment_link' => $response->json('data.link'),
            'tx_ref'       => $order->order_number,
        ];
    }

    /**
     * Verify a transaction by ID.
     */
    public function verify(string $transactionId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        if (!$response->successful() || $response->json('status') !== 'success') {
            throw new \RuntimeException('Flutterwave verification failed: ' . $response->json('message', 'Unknown error'));
        }

        return $response->json('data');
    }

    /**
     * Validate webhook signature from Flutterwave.
     */
    public function validateWebhook(string $signature): bool
    {
        return $signature === $this->secretHash;
    }

    /**
     * Process a verified Flutterwave payment and update the order.
     */
    public function processVerifiedPayment(array $data): ?Order
    {
        if ($data['status'] !== 'successful') {
            return null;
        }

        $order = Order::where('order_number', $data['tx_ref'])->first();
        if (!$order || $order->payment_status === 'PAID') {
            return $order;
        }

        // Verify amount and currency match the order
        $expectedAmount = round((float) $order->total, 2);
        $paidAmount     = round((float) ($data['amount'] ?? 0), 2);
        $currency       = strtoupper($data['currency'] ?? '');

        if ($currency !== 'NGN' || $paidAmount < $expectedAmount) {
            Log::error('Flutterwave amount/currency mismatch', [
                'order'    => $order->order_number,
                'expected' => $expectedAmount,
                'paid'     => $paidAmount,
                'currency' => $currency,
            ]);
            return null;
        }

        $order->update([
            'payment_status'    => 'PAID',
            'payment_reference' => (string) $data['id'],
            'status'            => 'CONFIRMED',
            'paid_at'           => now(),
        ]);

        $order->addTimeline('CONFIRMED', 'Payment confirmed via Flutterwave.');

        try {
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderConfirmed($order));
            }
            $adminEmail = SiteSetting::adminEmail();
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderAdminAlert($order));
            }
        } catch (\Throwable $e) {
            Log::error('Order email failed (Flutterwave)', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        // Push notification
        try {
            $orderUrl = route('account.orders.show', $order);
            app(\App\Services\OneSignalService::class)->sendOrderUpdate($order->order_number, 'CONFIRMED', $orderUrl);
        } catch (\Throwable $e) {
            Log::error('Order push notification failed (Flutterwave)', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        return $order;
    }
}
