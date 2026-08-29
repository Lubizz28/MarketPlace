<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResellerWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'total_withdrawn',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'integer',
            'pending_balance' => 'integer',
            'total_withdrawn' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ResellerWalletTransaction::class, 'wallet_id')->latest();
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }

    public function getFormattedPendingBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->pending_balance, 0, ',', '.');
    }

    public function getFormattedTotalWithdrawnAttribute(): string
    {
        return 'Rp ' . number_format($this->total_withdrawn, 0, ',', '.');
    }
}
