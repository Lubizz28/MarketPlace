<?php

namespace App\Enums;

enum PointTransactionType: string
{
    case EARNED = 'earned';
    case REDEEMED = 'redeemed';
    case REFUNDED = 'refunded';
    case ADJUSTED = 'adjusted';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::EARNED => 'Perolehan Poin',
            self::REDEEMED => 'Penukaran / Diskon',
            self::REFUNDED => 'Pengembalian Poin',
            self::ADJUSTED => 'Penyesuaian Manual',
            self::EXPIRED => 'Kedaluwarsa',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::EARNED => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::REDEEMED => 'bg-amber-100 text-amber-800 border-amber-300',
            self::REFUNDED => 'bg-blue-100 text-blue-800 border-blue-300',
            self::ADJUSTED => 'bg-purple-100 text-purple-800 border-purple-300',
            self::EXPIRED => 'bg-rose-100 text-rose-800 border-rose-300',
        };
    }
}
