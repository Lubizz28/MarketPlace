<?php

namespace App\Actions\Coupon;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ValidateCouponAction
{
    /**
     * Validate coupon code against subtotal and user.
     *
     * @param string|null $code
     * @param int $subtotal
     * @param User|null $user
     * @return array{
     *     valid: bool,
     *     coupon: Coupon|null,
     *     discount_amount: int,
     *     formatted_discount: string,
     *     message: string
     * }
     * @throws ValidationException
     */
    public function execute(?string $code, int $subtotal, ?User $user = null): array
    {
        if (empty($code)) {
            return [
                'valid' => false,
                'coupon' => null,
                'discount_amount' => 0,
                'formatted_discount' => 'Rp 0',
                'message' => 'Kode promo tidak boleh kosong.',
            ];
        }

        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => ['Kode kupon promo tidak ditemukan atau tidak valid.'],
            ]);
        }

        if (!$coupon->is_active) {
            throw ValidationException::withMessages([
                'coupon_code' => ['Kupon promo ini saat ini sedang tidak aktif.'],
            ]);
        }

        if ($coupon->start_at && $coupon->start_at->isFuture()) {
            throw ValidationException::withMessages([
                'coupon_code' => ['Kupon promo ini baru dapat digunakan pada ' . $coupon->start_at->translatedFormat('d F Y H:i') . '.'],
            ]);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'coupon_code' => ['Kupon promo ini telah kedaluwarsa pada ' . $coupon->expires_at->translatedFormat('d F Y') . '.'],
            ]);
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            throw ValidationException::withMessages([
                'coupon_code' => ['Kuota penggunaan kupon promo ini telah habis.'],
            ]);
        }

        if ($subtotal < $coupon->min_order_amount) {
            $minFormatted = 'Rp ' . number_format($coupon->min_order_amount, 0, ',', '.');
            throw ValidationException::withMessages([
                'coupon_code' => ["Minimal total belanja untuk menggunakan kupon ini adalah {$minFormatted}."],
            ]);
        }

        if ($user && $coupon->per_user_limit > 0) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();

            if ($userUsageCount >= $coupon->per_user_limit) {
                throw ValidationException::withMessages([
                    'coupon_code' => ["Anda telah mencapai batas maksimal ({$coupon->per_user_limit}x) penggunaan kupon promo ini."],
                ]);
            }
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $discount,
            'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'message' => "Kupon promo {$coupon->name} berhasil diterapkan!",
        ];
    }
}
