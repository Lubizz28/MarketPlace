<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case PENDING = 'pending';
    case AVAILABLE = 'available';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Pesanan Selesai',
            self::AVAILABLE => 'Tersedia di Saldo',
            self::PAID => 'Telah Dicairkan',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-300',
            self::AVAILABLE => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::PAID => 'bg-blue-100 text-blue-800 border-blue-300',
            self::CANCELLED => 'bg-rose-100 text-rose-800 border-rose-300',
        };
    }
}
