<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'recipient_name',
        'phone',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'subdistrict_id',
        'subdistrict_name',
        'postal_code',
        'address_line',
        'notes',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line,
            $this->subdistrict_name,
            $this->city_name,
            $this->province_name,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}
