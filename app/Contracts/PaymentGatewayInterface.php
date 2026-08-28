<?php

namespace App\Contracts;

use App\DTOs\PaymentChargeResult;
use App\DTOs\WebhookResult;
use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create payment charge/transaction on the gateway.
     */
    public function createCharge(Order $order, Payment $payment): PaymentChargeResult;

    /**
     * Validate and parse incoming webhook notification payload.
     */
    public function handleWebhook(array $payload): WebhookResult;

    /**
     * Check transaction status directly with gateway API.
     */
    public function checkStatus(string $transactionId): PaymentChargeResult;
}
