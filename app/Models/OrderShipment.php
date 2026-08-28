<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_code',
        'courier_name',
        'service_name',
        'service_description',
        'etd_days',
        'shipping_cost',
        'weight_grams',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'integer',
            'weight_grams' => 'integer',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getFormattedCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getCourierBadgeAttribute(): string
    {
        return strtoupper($this->courier_code) . ' — ' . $this->service_name;
    }
}
