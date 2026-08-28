<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'amount',
        'status',
        'snap_token',
        'payment_url',
        'payment_payload',
        'paid_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'status' => PaymentStatus::class,
            'amount' => 'integer',
            'payment_payload' => 'array',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::SETTLEMENT;
    }
}
