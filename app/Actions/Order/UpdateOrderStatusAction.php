<?php

namespace App\Actions\Order;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Enums\InventoryMovementType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use App\Services\LoyaltyPointService;
use App\Services\ResellerWalletService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class UpdateOrderStatusAction
{
    public function __construct(
        protected RecordInventoryMovementAction $recordInventoryMovementAction,
        protected LoyaltyPointService $loyaltyPointService,
        protected ResellerWalletService $resellerWalletService
    ) {}

    /**
     * Transition order to a new status with validation and inventory & point & reseller commission side effects.
     */
    public function execute(Order $order, OrderStatus $targetStatus, ?User $actor = null, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $targetStatus, $actor, $reason) {
            $lockedOrder = Order::with(['items.variant', 'payment', 'shipment'])->lockForUpdate()->findOrFail($order->id);

            $currentStatus = $lockedOrder->status;

            if ($currentStatus === $targetStatus) {
                return $lockedOrder;
            }

            // Enforce state transition rules
            $this->validateTransition($currentStatus, $targetStatus);

            $updateData = ['status' => $targetStatus];

            // Specific transition handling
            switch ($targetStatus) {
                case OrderStatus::PAID:
                    $updateData['paid_at'] = $lockedOrder->paid_at ?? now();
                    $updateData['payment_status'] = PaymentStatus::SETTLEMENT;
                    if ($lockedOrder->payment) {
                        $lockedOrder->payment->update([
                            'status' => PaymentStatus::SETTLEMENT,
                            'paid_at' => now(),
                        ]);
                    }
                    break;

                case OrderStatus::PROCESSING:
                    // Processing implies paid or verified
                    if ($lockedOrder->payment_status !== PaymentStatus::SETTLEMENT) {
                        $updateData['payment_status'] = PaymentStatus::SETTLEMENT;
                        $updateData['paid_at'] = $lockedOrder->paid_at ?? now();
                    }
                    break;

                case OrderStatus::SHIPPED:
                    if ($lockedOrder->shipment) {
                        $lockedOrder->shipment->update([
                            'status' => 'shipped',
                            'shipped_at' => now(),
                        ]);
                    }
                    break;

                case OrderStatus::DELIVERED:
                    if ($lockedOrder->shipment) {
                        $lockedOrder->shipment->update([
                            'status' => 'delivered',
                            'delivered_at' => now(),
                        ]);
                    }
                    break;

                case OrderStatus::COMPLETED:
                    $updateData['completed_at'] = now();
                    if ($lockedOrder->shipment) {
                        $lockedOrder->shipment->update([
                            'status' => 'delivered',
                            'delivered_at' => $lockedOrder->shipment->delivered_at ?? now(),
                        ]);
                    }

                    // Award loyalty points to member
                    $this->loyaltyPointService->earnPointsForOrder($lockedOrder);

                    // Credit available commission to reseller wallet
                    $this->resellerWalletService->creditCommissionOnOrderCompleted($lockedOrder);
                    break;

                case OrderStatus::CANCELLED:
                    $updateData['cancelled_at'] = now();
                    if ($lockedOrder->payment_status === PaymentStatus::UNPAID || $lockedOrder->payment_status === PaymentStatus::PENDING) {
                        $updateData['payment_status'] = PaymentStatus::EXPIRED;
                        if ($lockedOrder->payment) {
                            $lockedOrder->payment->update(['status' => PaymentStatus::EXPIRED]);
                        }
                    }

                    // Restore inventory
                    foreach ($lockedOrder->items as $item) {
                        if ($item->variant) {
                            $this->recordInventoryMovementAction->execute(
                                variant: $item->variant,
                                type: InventoryMovementType::RETURN,
                                quantity: $item->quantity,
                                userId: $actor?->id,
                                notes: "Pembatalan pesanan #{$lockedOrder->order_number}" . ($reason ? " ({$reason})" : ''),
                                referenceType: 'order',
                                referenceId: $lockedOrder->id
                            );
                        }
                    }

                    // Refund redeemed points
                    $this->loyaltyPointService->refundPointsForOrder($lockedOrder);

                    // Cancel reseller commission
                    $this->resellerWalletService->cancelCommissionOnOrderCancelled($lockedOrder);
                    break;

                case OrderStatus::REFUNDED:
                    $updateData['payment_status'] = PaymentStatus::REFUNDED;
                    if ($lockedOrder->payment) {
                        $lockedOrder->payment->update(['status' => PaymentStatus::REFUNDED]);
                    }

                    // Restore inventory on refund if previously shipped/processing
                    foreach ($lockedOrder->items as $item) {
                        if ($item->variant) {
                            $this->recordInventoryMovementAction->execute(
                                variant: $item->variant,
                                type: InventoryMovementType::RETURN,
                                quantity: $item->quantity,
                                userId: $actor?->id,
                                notes: "Retur/Refund pesanan #{$lockedOrder->order_number}" . ($reason ? " ({$reason})" : ''),
                                referenceType: 'order',
                                referenceId: $lockedOrder->id
                            );
                        }
                    }

                    // Refund / Reverse points
                    $this->loyaltyPointService->refundPointsForOrder($lockedOrder);

                    // Cancel reseller commission
                    $this->resellerWalletService->cancelCommissionOnOrderCancelled($lockedOrder);
                    break;
            }

            $lockedOrder->update($updateData);

            return $lockedOrder->fresh(['items.variant', 'payment', 'shipment', 'address', 'pointTransactions']);
        });
    }

    protected function validateTransition(OrderStatus $from, OrderStatus $to): void
    {
        // Terminal states cannot be changed
        if (in_array($from, [OrderStatus::COMPLETED, OrderStatus::CANCELLED, OrderStatus::REFUNDED])) {
            throw new InvalidArgumentException("Pesanan dengan status {$from->label()} tidak dapat diubah ke status {$to->label()}.");
        }

        $allowed = match ($from) {
            OrderStatus::PENDING_PAYMENT => [OrderStatus::PAID, OrderStatus::CANCELLED],
            OrderStatus::PAID => [OrderStatus::PROCESSING, OrderStatus::CANCELLED, OrderStatus::REFUNDED],
            OrderStatus::PROCESSING => [OrderStatus::SHIPPED, OrderStatus::CANCELLED, OrderStatus::REFUNDED],
            OrderStatus::SHIPPED => [OrderStatus::DELIVERED, OrderStatus::COMPLETED, OrderStatus::REFUNDED],
            OrderStatus::DELIVERED => [OrderStatus::COMPLETED, OrderStatus::REFUNDED],
            default => [],
        };

        if (!in_array($to, $allowed)) {
            throw new InvalidArgumentException("Transisi status dari '{$from->label()}' ke '{$to->label()}' tidak diizinkan.");
        }
    }
}
