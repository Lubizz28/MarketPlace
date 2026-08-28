<?php

namespace App\Enums;

enum CustomerType: string
{
    case RETAIL = 'retail';
    case MEMBER = 'member';
    case RESELLER = 'reseller';

    public function label(): string
    {
        return match ($this) {
            self::RETAIL => 'Harga Retail (Guest)',
            self::MEMBER => 'Harga Khusus Member',
            self::RESELLER => 'Harga Grosir Reseller',
        };
    }
}
