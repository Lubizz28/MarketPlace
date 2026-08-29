<?php

namespace App\Enums;

enum CouponType: string
{
    case FIXED = 'fixed';
    case PERCENT = 'percent';

    public function label(): string
    {
        return match ($this) {
            self::FIXED => 'Nominal Tetap (Rp)',
            self::PERCENT => 'Persentase (%)',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::FIXED => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::PERCENT => 'bg-amber-100 text-amber-800 border-amber-300',
        };
    }

    public function formatDiscount(int $amount): string
    {
        return match ($this) {
            self::FIXED => 'Rp ' . number_format($amount, 0, ',', '.'),
            self::PERCENT => $amount . '%',
        };
    }
}
