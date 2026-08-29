<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'amount',
        'min_order_amount',
        'max_discount',
        'max_uses',
        'used_count',
        'per_user_limit',
        'start_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => CouponType::class,
            'amount' => 'integer',
            'min_order_amount' => 'integer',
            'max_discount' => 'integer',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'per_user_limit' => 'integer',
            'start_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_at && $this->start_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $subtotal): int
    {
        if ($subtotal < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === CouponType::FIXED) {
            return min($this->amount, $subtotal);
        }

        // Percentage discount
        $discount = (int) round(($subtotal * $this->amount) / 100);

        if ($this->max_discount !== null && $this->max_discount > 0) {
            $discount = min($discount, $this->max_discount);
        }

        return min($discount, $subtotal);
    }

    public function getFormattedDiscountAttribute(): string
    {
        return $this->type->formatDiscount($this->amount);
    }

    public function getFormattedMinOrderAttribute(): string
    {
        return 'Rp ' . number_format($this->min_order_amount, 0, ',', '.');
    }
}
