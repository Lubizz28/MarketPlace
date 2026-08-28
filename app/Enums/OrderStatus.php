<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::PAID => 'Sudah Dibayar',
            self::PROCESSING => 'Sedang Diproses',
            self::SHIPPED => 'Dalam Pengiriman',
            self::DELIVERED => 'Terkirim',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
            self::REFUNDED => 'Dikembalikan (Refund)',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'bg-amber-100 text-amber-900 border-amber-300',
            self::PAID => 'bg-sky-100 text-sky-900 border-sky-300',
            self::PROCESSING => 'bg-indigo-100 text-indigo-900 border-indigo-300',
            self::SHIPPED => 'bg-purple-100 text-purple-900 border-purple-300',
            self::DELIVERED => 'bg-teal-100 text-teal-900 border-teal-300',
            self::COMPLETED => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            self::CANCELLED => 'bg-rose-100 text-rose-900 border-rose-300',
            self::REFUNDED => 'bg-gray-100 text-gray-900 border-gray-300',
        };
    }

    public function isPaid(): bool
    {
        return in_array($this, [self::PAID, self::PROCESSING, self::SHIPPED, self::DELIVERED, self::COMPLETED]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::PENDING_PAYMENT]);
    }
}
