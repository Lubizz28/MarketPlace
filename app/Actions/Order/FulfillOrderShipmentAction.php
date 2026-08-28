<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FulfillOrderShipmentAction
{
    public function __construct(
        protected UpdateOrderStatusAction $updateOrderStatusAction
    ) {}

    /**
     * Set waybill number and mark order as shipped.
     */
    public function execute(Order $order, string $trackingNumber, ?string $notes = null, ?User $actor = null): Order
    {
        return DB::transaction(function () use ($order, $trackingNumber, $notes, $actor) {
            $lockedOrder = Order::with('shipment')->lockForUpdate()->findOrFail($order->id);

            if (!$lockedOrder->shipment) {
                throw new InvalidArgumentException("Pesanan tidak memiliki data pengiriman.");
            }

            $cleanTrackingNumber = trim(strtoupper($trackingNumber));
            if (empty($cleanTrackingNumber)) {
                throw new InvalidArgumentException("Nomor resi pengiriman tidak boleh kosong.");
            }

            // Update shipment record
            $lockedOrder->shipment->update([
                'tracking_number' => $cleanTrackingNumber,
                'status' => 'shipped',
                'shipped_at' => now(),
                'notes' => $notes ?: $lockedOrder->shipment->notes,
            ]);

            // If order was in PAID state, transition to PROCESSING first if required, then SHIPPED
            if ($lockedOrder->status === OrderStatus::PAID) {
                $lockedOrder = $this->updateOrderStatusAction->execute($lockedOrder, OrderStatus::PROCESSING, $actor);
            }

            if ($lockedOrder->status === OrderStatus::PROCESSING) {
                $lockedOrder = $this->updateOrderStatusAction->execute($lockedOrder, OrderStatus::SHIPPED, $actor);
            }

            return $lockedOrder->fresh(['items', 'shipment', 'payment', 'address']);
        });
    }
}
