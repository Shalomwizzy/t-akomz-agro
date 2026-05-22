<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    private string $appId;
    private string $apiKey;
    private string $baseUrl = 'https://onesignal.com/api/v1';

    public function __construct()
    {
        $this->appId  = config('services.onesignal.app_id', '');
        $this->apiKey = config('services.onesignal.api_key', '');
    }

    /**
     * Send push notification to all subscribed users.
     */
    public function sendToAll(string $title, string $message, ?string $url = null): bool
    {
        return $this->send([
            'included_segments' => ['Total Subscriptions'],
        ], $title, $message, $url);
    }

    /**
     * Send to a specific OneSignal segment.
     */
    public function sendToSegment(string $segment, string $title, string $message, ?string $url = null): bool
    {
        return $this->send([
            'included_segments' => [$segment],
        ], $title, $message, $url);
    }

    /**
     * Send to a specific user by their OneSignal external user ID (e.g. our user ID).
     */
    public function sendToUser(string|int $userId, string $title, string $message, ?string $url = null): bool
    {
        return $this->send([
            'filters' => [
                ['field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => (string) $userId],
            ],
        ], $title, $message, $url);
    }

    /**
     * Send order status push to a specific customer (by user_id tag or email).
     */
    public function sendOrderUpdate(string $orderNumber, string $status, ?string $url = null): bool
    {
        $messages = [
            'CONFIRMED'        => "Your order {$orderNumber} has been confirmed! We're preparing it now.",
            'PROCESSING'       => "Your order {$orderNumber} is now being processed.",
            'DISPATCHED'       => "Your order {$orderNumber} is on its way!",
            'OUT_FOR_DELIVERY' => "Your order {$orderNumber} is out for delivery. Expect it today!",
            'DELIVERED'        => "Your order {$orderNumber} has been delivered. Enjoy!",
            'CANCELLED'        => "Your order {$orderNumber} has been cancelled.",
            'REFUNDED'         => "Refund initiated for order {$orderNumber}.",
        ];

        $body = $messages[$status] ?? "Your order {$orderNumber} status has been updated to {$status}.";

        return $this->sendToAll('Order Update — T-Akomz Agro', $body, $url);
    }

    private function send(array $targeting, string $title, string $message, ?string $url = null): bool
    {
        if (empty($this->appId) || empty($this->apiKey)) {
            Log::warning('OneSignal not configured — skipping push notification');
            return false;
        }

        $payload = array_merge($targeting, [
            'app_id'   => $this->appId,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'chrome_web_icon' => url('/images/logo-mark.svg'),
            'firefox_icon'    => url('/images/logo-mark.svg'),
        ]);

        if ($url) {
            $payload['url'] = $url;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Key {$this->apiKey}",
                'Content-Type'  => 'application/json',
            ])->post("{$this->baseUrl}/notifications", $payload);

            if (!$response->successful()) {
                Log::error('OneSignal push failed', [
                    'status'   => $response->status(),
                    'response' => $response->json(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('OneSignal exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
