<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case QRIS = 'qris';
    case BCA_VA = 'bca_va';
    case MANDIRI_VA = 'mandiri_va';
    case BNI_VA = 'bni_va';
    case BRI_VA = 'bri_va';
    case MANUAL_TRANSFER = 'manual_transfer';

    public function label(): string
    {
        return match ($this) {
            self::QRIS => 'QRIS (GoPay, OVO, Dana, ShopeePay, BCA, LinkAja)',
            self::BCA_VA => 'BCA Virtual Account',
            self::MANDIRI_VA => 'Mandiri Bill Payment / VA',
            self::BNI_VA => 'BNI Virtual Account',
            self::BRI_VA => 'BRI Virtual Account (BRIVA)',
            self::MANUAL_TRANSFER => 'Transfer Bank Manual (Verifikasi 1x24 Jam)',
        };
    }

    public function shortName(): string
    {
        return match ($this) {
            self::QRIS => 'QRIS Instan',
            self::BCA_VA => 'BCA VA',
            self::MANDIRI_VA => 'Mandiri VA',
            self::BNI_VA => 'BNI VA',
            self::BRI_VA => 'BRI VA',
            self::MANUAL_TRANSFER => 'Transfer Manual',
        };
    }

    public function isAutomated(): bool
    {
        return $this !== self::MANUAL_TRANSFER;
    }
}
