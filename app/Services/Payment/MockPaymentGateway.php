<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\DTOs\PaymentChargeResult;
use App\DTOs\WebhookResult;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;

class MockPaymentGateway implements PaymentGatewayInterface
{
    public function createCharge(Order $order, Payment $payment): PaymentChargeResult
    {
        return new PaymentChargeResult(
            success: true,
            transactionId: 'MOCK-TRX-' . $order->order_number,
            status: PaymentStatus::PENDING,
            snapToken: 'mock-snap-token-' . uniqid(),
            redirectUrl: route('orders.show', $order->order_number),
            rawResponse: ['status' => 'mock_success']
        );
    }

    public function handleWebhook(array $payload): WebhookResult
    {
        return new WebhookResult(
            isValid: true,
            orderNumber: $payload['order_id'] ?? 'MOCK-ORDER',
            transactionId: $payload['transaction_id'] ?? 'MOCK-TRX',
            paymentStatus: PaymentStatus::SETTLEMENT,
            grossAmount: (int) ($payload['gross_amount'] ?? 0),
            rawPayload: $payload
        );
    }

    public function checkStatus(string $transactionId): PaymentChargeResult
    {
        return new PaymentChargeResult(
            success: true,
            transactionId: $transactionId,
            status: PaymentStatus::SETTLEMENT
        );
    }
}
