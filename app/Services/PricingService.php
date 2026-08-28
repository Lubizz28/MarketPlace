<?php

namespace App\Services;

use App\Enums\CustomerType;
use App\Enums\UserRole;
use App\Models\ProductVariant;
use App\Models\User;

class PricingService
{
    /**
     * Determine customer tier based on authenticated user.
     */
    public function getCustomerType(?User $user = null): CustomerType
    {
        if (! $user) {
            return CustomerType::RETAIL;
        }

        if ($user->role === UserRole::RESELLER && $user->isActive()) {
            return CustomerType::RESELLER;
        }

        if ($user->role === UserRole::MEMBER && $user->isActive()) {
            return CustomerType::MEMBER;
        }

        return CustomerType::RETAIL;
    }

    /**
     * Get price for a variant given the current user.
     */
    public function getVariantPrice(ProductVariant $variant, ?User $user = null): float
    {
        $customerType = $this->getCustomerType($user);
        return $variant->getPriceFor($customerType);
    }

    /**
     * Format number as IDR Rupiah string.
     */
    public function formatRupiah(float|int|null $amount): string
    {
        $amount = (float) ($amount ?? 0);
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Calculate savings comparison between retail and current user's price.
     */
    public function calculateSavings(ProductVariant $variant, ?User $user = null): array
    {
        $retailPrice = $variant->getPriceFor(CustomerType::RETAIL);
        $userPrice = $this->getVariantPrice($variant, $user);

        $savedAmount = max(0, $retailPrice - $userPrice);
        $savedPercentage = $retailPrice > 0 ? round(($savedAmount / $retailPrice) * 100) : 0;

        return [
            'retail_price' => $retailPrice,
            'user_price' => $userPrice,
            'saved_amount' => $savedAmount,
            'saved_percentage' => $savedPercentage,
            'formatted_retail' => $this->formatRupiah($retailPrice),
            'formatted_user' => $this->formatRupiah($userPrice),
            'has_discount' => $savedAmount > 0,
        ];
    }
}
