<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';
    case RESELLER = 'reseller';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::MEMBER => 'Member',
            self::RESELLER => 'Reseller',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-purple-100 text-purple-800 border-purple-200',
            self::MEMBER => 'bg-blue-100 text-blue-800 border-blue-200',
            self::RESELLER => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        };
    }
}
