<?php

namespace App\Enums;

enum InventoryMovementType: string
{
    case OPENING = 'opening';
    case RESTOCK = 'restock';
    case SALE = 'sale';
    case RETURN = 'return';
    case ADJUSTMENT = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::OPENING => 'Stok Awal',
            self::RESTOCK => 'Penambahan Stok (Restock)',
            self::SALE => 'Pengurangan Penjualan',
            self::RETURN => 'Pengembalian (Retur)',
            self::ADJUSTMENT => 'Penyesuaian Stok (Opname)',
        };
    }
}
