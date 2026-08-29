<?php

namespace App\Enums;

enum KycStatus: string
{
    case UNSUBMITTED = 'unsubmitted';
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::UNSUBMITTED => 'Belum Melengkapi Data',
            self::PENDING => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi Aktif',
            self::REJECTED => 'Ditolak',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::UNSUBMITTED => 'bg-charcoal-100 text-charcoal-700 border-charcoal-300',
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-300',
            self::VERIFIED => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::REJECTED => 'bg-rose-100 text-rose-800 border-rose-300',
        };
    }
}
