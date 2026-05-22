<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TermiiService
{
    private string $apiKey;
    private string $senderId;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey   = config('services.termii.api_key', '');
        $this->senderId = config('services.termii.sender_id', 'TAKOMZ');
        $this->baseUrl  = config('services.termii.base_url', 'https://v3.api.termii.com/api');
    }

    public function send(string $to, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::info('Termii SMS skipped — no API key configured.', ['to' => $to]);
            return false;
        }

        $phone = $this->normalizePhone($to);
        if (!$phone) {
            Log::warning('Termii SMS skipped — invalid phone number.', ['to' => $to]);
            return false;
        }

        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/sms/send", [
                'api_key'  => $this->apiKey,
                'to'       => $phone,
                'from'     => $this->senderId,
                'sms'      => $message,
                'type'     => 'plain',
                'channel'  => 'generic',
            ]);

            if (!$response->successful()) {
                Log::error('Termii SMS failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'to'     => $phone,
                ]);
                return false;
            }

            Log::info('Termii SMS sent', ['to' => $phone, 'message_id' => $response->json('message_id')]);
            return true;
        } catch (\Throwable $e) {
            Log::error('Termii SMS exception', ['error' => $e->getMessage(), 'to' => $phone]);
            return false;
        }
    }

    public function sendOrderConfirmed(string $phone, string $orderNumber, string $total): bool
    {
        $msg = "Hi! Your T-Akomz order {$orderNumber} has been confirmed. Total: {$total}. We'll notify you when it's dispatched. Thank you for choosing us!";
        return $this->send($phone, $msg);
    }

    public function sendOrderDispatched(string $phone, string $orderNumber): bool
    {
        $msg = "Great news! Your T-Akomz order {$orderNumber} is on its way to you. Our delivery team will contact you shortly. Track: takomzagro.com/track";
        return $this->send($phone, $msg);
    }

    public function sendOrderDelivered(string $phone, string $orderNumber): bool
    {
        $msg = "Your T-Akomz order {$orderNumber} has been delivered! We hope you enjoy your fresh farm produce. Please rate your experience at takomzagro.com";
        return $this->send($phone, $msg);
    }

    public function sendOrderCancelled(string $phone, string $orderNumber): bool
    {
        $msg = "Your T-Akomz order {$orderNumber} has been cancelled. If you paid online, your refund will be processed within 3-5 business days. Contact us: takomzagro.com/contact";
        return $this->send($phone, $msg);
    }

    private function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return '234' . substr($digits, 1);
        }

        if (strlen($digits) === 13 && str_starts_with($digits, '234')) {
            return $digits;
        }

        if (strlen($digits) === 10) {
            return '234' . $digits;
        }

        return null;
    }
}
