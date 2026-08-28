<?php

namespace App\Actions\Payment;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Contracts\PaymentGatewayInterface;
use App\Enums\InventoryMovementType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentWebhookAction
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway,
        protected RecordInventoryMovementAction $recordInventoryMovementAction
    ) {}

    /**
     * Handle payment notification idempotently.
     */
    public function execute(array $payload): array
    {
        $webhookResult = $this->paymentGateway->handleWebhook($payload);

        if (!$webhookResult->isValid) {
            Log::warning("Payment webhook signature invalid: " . json_encode($payload));
            return [
                'status' => 'error',
                'message' => $webhookResult->errorMessage ?? 'Invalid signature.',
            ];
        }

        return DB::transaction(function () use ($webhookResult) {
            /** @var Order|null $order */
            $order = Order::where('order_number', $webhookResult->orderNumber)
                ->with(['payment', 'items.variant'])
                ->lockForUpdate()
                ->first();

            if (!$order) {
                Log::warning("Payment webhook received for non-existent order: {$webhookResult->orderNumber}");
                return [
                    'status' => 'error',
                    'message' => 'Order not found.',
                ];
            }

            /** @var Payment|null $payment */
            $payment = $order->payment ?? Payment::where('order_id', $order->id)->latest()->first();

            if ($payment) {
                // Log webhook transaction record
                $payment->transactions()->create([
                    'gateway_reference' => $webhookResult->transactionId,
                    'event_type' => 'webhook_received',
                    'payload_json' => $webhookResult->rawPayload,
                    'status' => $webhookResult->paymentStatus->value,
                ]);
            }

            // IDEMPOTENCY CHECK: If order is already settled/paid and incoming status is settlement, skip duplicate updates
            if ($order->payment_status === PaymentStatus::SETTLEMENT && $webhookResult->paymentStatus === PaymentStatus::SETTLEMENT) {
                return [
                    'status' => 'success',
                    'message' => 'Payment already settled (idempotent).',
                ];
            }

            // Handle Settlement / Paid
            if ($webhookResult->paymentStatus === PaymentStatus::SETTLEMENT) {
                if ($payment) {
                    $payment->update([
                        'status' => PaymentStatus::SETTLEMENT,
                        'paid_at' => now(),
                    ]);
                }

                $order->update([
                    'status' => OrderStatus::PAID,
                    'payment_status' => PaymentStatus::SETTLEMENT,
                    'paid_at' => now(),
                ]);

                Log::info("Order #{$order->order_number} successfully paid via webhook.");

                return [
                    'status' => 'success',
                    'message' => 'Payment verified and settled successfully.',
                ];
            }

            // Handle Expiry / Failure
            if (in_array($webhookResult->paymentStatus, [PaymentStatus::EXPIRED, PaymentStatus::FAILED])) {
                if ($payment) {
                    $payment->update(['status' => $webhookResult->paymentStatus]);
                }

                // If previously pending, mark cancelled and restore stock
                if ($order->status === OrderStatus::PENDING_PAYMENT) {
                    $order->update([
                        'status' => OrderStatus::CANCELLED,
                        'payment_status' => $webhookResult->paymentStatus,
                        'cancelled_at' => now(),
                    ]);

                    // Restore inventory
                    foreach ($order->items as $item) {
                        if ($item->variant) {
                            $this->recordInventoryMovementAction->execute(
                                variant: $item->variant,
                                type: InventoryMovementType::RETURN,
                                quantity: $item->quantity,
                                notes: "Pengembalian stok pesanan kedaluwarsa #{$order->order_number}",
                                referenceType: 'order',
                                referenceId: $order->id
                            );
                        }
                    }
                }

                return [
                    'status' => 'success',
                    'message' => "Order updated to {$webhookResult->paymentStatus->value}.",
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Notification processed.',
            ];
        });
    }
}
