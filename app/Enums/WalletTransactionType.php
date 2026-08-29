<?php

namespace App\Enums;

enum WalletTransactionType: string
{
    case COMMISSION_EARNED = 'commission_earned';
    case WITHDRAWAL_HOLD = 'withdrawal_hold';
    case WITHDRAWAL_PAID = 'withdrawal_paid';
    case WITHDRAWAL_REFUND = 'withdrawal_refund';
    case ADJUSTMENT = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::COMMISSION_EARNED => 'Pencairan Komisi Pesanan',
            self::WITHDRAWAL_HOLD => 'Pengajuan Penarikan Dana',
            self::WITHDRAWAL_PAID => 'Penarikan Dana Berhasil',
            self::WITHDRAWAL_REFUND => 'Pengembalian Dana Penarikan',
            self::ADJUSTMENT => 'Penyesuaian Saldo Kas',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::COMMISSION_EARNED => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::WITHDRAWAL_HOLD => 'bg-amber-100 text-amber-800 border-amber-300',
            self::WITHDRAWAL_PAID => 'bg-blue-100 text-blue-800 border-blue-300',
            self::WITHDRAWAL_REFUND => 'bg-purple-100 text-purple-800 border-purple-300',
            self::ADJUSTMENT => 'bg-charcoal-100 text-charcoal-800 border-charcoal-300',
        };
    }
}
