<?php

namespace App\Models;

use App\Enums\KycStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'referral_code',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'kyc_status',
        'id_card_image',
        'commission_rate_percent',
        'approved_at',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'kyc_status' => KycStatus::class,
            'commission_rate_percent' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isVerified(): bool
    {
        return $this->kyc_status === KycStatus::VERIFIED;
    }
}
