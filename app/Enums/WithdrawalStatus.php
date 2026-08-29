<?php

namespace App\Enums;

enum WithdrawalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case PAID = 'paid';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Verifikasi',
            self::APPROVED => 'Disetujui Admin',
            self::PAID => 'Dana Telah Ditransfer',
            self::REJECTED => 'Ditolak',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-300',
            self::APPROVED => 'bg-blue-100 text-blue-800 border-blue-300',
            self::PAID => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::REJECTED => 'bg-rose-100 text-rose-800 border-rose-300',
        };
    }
}
