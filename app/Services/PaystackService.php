<?php

namespace App\Services;

use App\Mail\OrderAdminAlert;
use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaystackService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $dbKey = SiteSetting::get('paystack_secret_key', '');
        $this->secretKey = $dbKey ?: config('services.paystack.secret_key', '');
        $this->baseUrl   = config('services.paystack.payment_url', 'https://api.paystack.co');
    }

    /**
     * Initialize a Paystack transaction and return the hosted payment URL.
     */
    public function initialize(Order $order): array
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transaction/initialize", [
                'email'        => $order->customer_email,
                'amount'       => (int) ($order->total * 100), // kobo
                'reference'    => $order->order_number,
                'callback_url' => route('checkout.paystack.callback'),
                'metadata'     => [
                    'order_number' => $order->order_number,
                    'customer'     => $order->customer_name,
                ],
            ]);

        if (!$response->successful() || !$response->json('status')) {
            Log::error('Paystack init failed', ['response' => $response->json()]);
            throw new \RuntimeException('Could not initialize Paystack payment: ' . $response->json('message', 'Unknown error'));
        }

        return [
            'authorization_url' => $response->json('data.authorization_url'),
            'access_code'       => $response->json('data.access_code'),
            'reference'         => $response->json('data.reference'),
        ];
    }

    /**
     * Verify a transaction by reference.
     */
    public function verify(string $reference): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        if (!$response->successful() || !$response->json('status')) {
            throw new \RuntimeException('Paystack verification failed: ' . $response->json('message', 'Unknown error'));
        }

        return $response->json('data');
    }

    /**
     * Validate webhook signature from Paystack.
     */
    public function validateWebhook(string $payload, string $signature): bool
    {
        $computed = hash_hmac('sha512', $payload, $this->secretKey);
        return hash_equals($computed, $signature);
    }

    /**
     * Process a verified Paystack payment and update the order.
     */
    public function processVerifiedPayment(array $data): ?Order
    {
        if ($data['status'] !== 'success') {
            return null;
        }

        $order = Order::where('order_number', $data['reference'])->first();
        if (!$order || $order->payment_status === 'PAID') {
            return $order;
        }

        // Verify amount (Paystack amounts are in kobo) and currency
        $expectedKobo = (int) round($order->total * 100);
        $paidKobo     = (int) ($data['amount'] ?? 0);
        $currency     = strtoupper($data['currency'] ?? '');

        if ($currency !== 'NGN' || $paidKobo < $expectedKobo) {
            Log::error('Paystack amount/currency mismatch', [
                'order'    => $order->order_number,
                'expected' => $expectedKobo,
                'paid'     => $paidKobo,
                'currency' => $currency,
            ]);
            return null;
        }

        $order->update([
            'payment_status'     => 'PAID',
            'payment_reference'  => $data['reference'],
            'status'             => 'CONFIRMED',
            'paid_at'            => now(),
        ]);

        $order->addTimeline('CONFIRMED', 'Payment confirmed via Paystack.');

        $this->sendOrderEmails($order);

        return $order;
    }

    private function sendOrderEmails(Order $order): void
    {
        try {
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderConfirmed($order));
            }
            $adminEmail = SiteSetting::adminEmail();
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new OrderAdminAlert($order));
            }
        } catch (\Throwable $e) {
            Log::error('Order confirmation email failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        // Push notification to customer
        try {
            $orderUrl = route('account.orders.show', $order);
            app(\App\Services\OneSignalService::class)->sendOrderUpdate($order->order_number, 'CONFIRMED', $orderUrl);
        } catch (\Throwable $e) {
            Log::error('Order push notification failed (Paystack)', ['order' => $order->id, 'error' => $e->getMessage()]);
        }
    }
}
