<?php

namespace App\Http\Controllers;

use App\Actions\Payment\ProcessPaymentWebhookAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected ProcessPaymentWebhookAction $processPaymentWebhookAction
    ) {}

    /**
     * Handle incoming payment gateway webhook notification.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received: ' . json_encode($payload));

        $result = $this->processPaymentWebhookAction->execute($payload);

        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }
}
