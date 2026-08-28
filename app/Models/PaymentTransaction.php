<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'gateway_reference',
        'event_type',
        'payload_json',
        'response_json',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payload_json' => 'array',
            'response_json' => 'array',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
