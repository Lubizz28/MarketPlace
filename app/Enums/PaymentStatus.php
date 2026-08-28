<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case SETTLEMENT = 'settlement';
    case EXPIRED = 'expired';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Dibayar',
            self::PENDING => 'Menunggu Pembayaran',
            self::SETTLEMENT => 'Lunas',
            self::EXPIRED => 'Kedaluwarsa',
            self::FAILED => 'Gagal',
            self::REFUNDED => 'Dikembalikan',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::UNPAID => 'bg-charcoal-100 text-charcoal-800 border-charcoal-200',
            self::PENDING => 'bg-amber-100 text-amber-900 border-amber-300',
            self::SETTLEMENT => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            self::EXPIRED => 'bg-rose-100 text-rose-900 border-rose-300',
            self::FAILED => 'bg-rose-100 text-rose-900 border-rose-300',
            self::REFUNDED => 'bg-purple-100 text-purple-900 border-purple-300',
        };
    }

    public function isSuccess(): bool
    {
        return $this === self::SETTLEMENT;
    }
}
