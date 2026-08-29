<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_type',
        'reseller_id',
        'coupon_id',
        'coupon_code',
        'status',
        'payment_status',
        'subtotal',
        'coupon_discount',
        'points_redeemed',
        'points_discount',
        'discount_amount',
        'shipping_cost',
        'grand_total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'expires_at',
        'paid_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'customer_type' => CustomerType::class,
            'subtotal' => 'integer',
            'coupon_discount' => 'integer',
            'points_redeemed' => 'integer',
            'points_discount' => 'integer',
            'discount_amount' => 'integer',
            'shipping_cost' => 'integer',
            'grand_total' => 'integer',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function couponUsage(): HasOne
    {
        return $this->hasOne(CouponUsage::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(OrderShipment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getFormattedCouponDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->coupon_discount, 0, ',', '.');
    }

    public function getFormattedPointsDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->points_discount, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }

    public function isPendingPayment(): bool
    {
        return $this->status === OrderStatus::PENDING_PAYMENT;
    }

    public function isPaid(): bool
    {
        return $this->status->isPaid();
    }
}
