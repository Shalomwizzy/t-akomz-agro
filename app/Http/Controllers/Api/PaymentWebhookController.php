<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaystackService;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function paystack(Request $request)
    {
        $signature = $request->header('x-paystack-signature', '');
        $payload   = $request->getContent();

        $paystack = app(PaystackService::class);

        if (!$paystack->validateWebhook($payload, $signature)) {
            Log::warning('Paystack webhook: invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->json('event');
        $data  = $request->json('data');

        if ($event === 'charge.success') {
            try {
                $paystack->processVerifiedPayment($data);
                Log::info('Paystack webhook: payment processed', ['ref' => $data['reference'] ?? '']);
            } catch (\Throwable $e) {
                Log::error('Paystack webhook processing error', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function flutterwave(Request $request)
    {
        $signature = $request->header('verif-hash', '');
        $flw       = app(FlutterwaveService::class);

        if (!$flw->validateWebhook($signature)) {
            Log::warning('Flutterwave webhook: invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->input('event');
        $data  = $request->input('data');

        if ($event === 'charge.completed') {
            try {
                $flw->processVerifiedPayment($data);
                Log::info('Flutterwave webhook: payment processed', ['ref' => $data['tx_ref'] ?? '']);
            } catch (\Throwable $e) {
                Log::error('Flutterwave webhook processing error', ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
