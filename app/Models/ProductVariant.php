<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'color_name',
        'color_hex',
        'size',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest();
    }

    /**
     * Get price for specific customer type (retail, member, reseller)
     */
    public function getPriceFor(CustomerType|string|null $type): float
    {
        $typeString = is_object($type) ? $type->value : ($type ?? 'retail');

        $priceRecord = $this->prices->firstWhere('customer_type.value', $typeString)
            ?? $this->prices->firstWhere('customer_type', $typeString);

        if ($priceRecord) {
            return (float) $priceRecord->price;
        }

        // Fallback to retail price
        $retailRecord = $this->prices->firstWhere('customer_type.value', 'retail')
            ?? $this->prices->firstWhere('customer_type', 'retail');

        return $retailRecord ? (float) $retailRecord->price : 0.0;
    }

    public function getRetailPriceAttribute(): float
    {
        return $this->getPriceFor(CustomerType::RETAIL);
    }

    public function getMemberPriceAttribute(): float
    {
        return $this->getPriceFor(CustomerType::MEMBER);
    }

    public function getResellerPriceAttribute(): float
    {
        return $this->getPriceFor(CustomerType::RESELLER);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
