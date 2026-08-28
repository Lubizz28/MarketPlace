<?php

namespace App\Actions\Inventory;

use App\Enums\InventoryMovementType;
use App\Models\InventoryMovement;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RecordInventoryMovementAction
{
    /**
     * Atomically adjust variant stock and record audit ledger entry.
     */
    public function execute(
        ProductVariant $variant,
        InventoryMovementType $type,
        int $quantity,
        ?int $userId = null,
        ?string $notes = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): InventoryMovement {
        return DB::transaction(function () use (
            $variant,
            $type,
            $quantity,
            $userId,
            $notes,
            $referenceType,
            $referenceId
        ) {
            $lockedVariant = ProductVariant::lockForUpdate()->findOrFail($variant->id);

            // Determine stock multiplier based on movement type
            $stockDelta = match ($type) {
                InventoryMovementType::SALE => -abs($quantity),
                InventoryMovementType::RETURN,
                InventoryMovementType::RESTOCK,
                InventoryMovementType::OPENING => abs($quantity),
                InventoryMovementType::ADJUSTMENT => $quantity,
            };

            $newBalance = $lockedVariant->stock + $stockDelta;

            if ($newBalance < 0) {
                throw new InvalidArgumentException("Stok tidak mencukupi untuk varian {$lockedVariant->sku}. Stok saat ini: {$lockedVariant->stock}, diminta: " . abs($quantity));
            }

            $lockedVariant->update(['stock' => $newBalance]);

            return InventoryMovement::create([
                'product_variant_id' => $lockedVariant->id,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => abs($quantity),
                'balance_after' => $newBalance,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => $notes,
            ]);
        });
    }
}
