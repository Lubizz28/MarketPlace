<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'customer_type',
        'price',
        'min_quantity',
    ];

    protected $casts = [
        'customer_type' => CustomerType::class,
        'price' => 'decimal:2',
        'min_quantity' => 'integer',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
