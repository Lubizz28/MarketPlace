<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'weight_grams',
        'is_active',
        'is_featured',
        'view_count',
    ];

    protected $casts = [
        'weight_grams' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            return $this->primaryImage->image_path;
        }

        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            return $this->images->first()->image_path;
        }

        $image = $this->images()->orderBy('is_primary', 'desc')->first();
        return $image ? $image->image_path : 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop';
    }

    public function getTotalStockAttribute(): int
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->sum('stock');
        }

        return $this->variants()->sum('stock');
    }

    public function getMinPriceFor(CustomerType|string|null $customerType = null): float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->with('prices')->get();
        if ($variants->isEmpty()) {
            return 0.0;
        }

        $prices = $variants->map(fn ($v) => $v->getPriceFor($customerType));
        return $prices->min() ?? 0.0;
    }

    public function getMaxPriceFor(CustomerType|string|null $customerType = null): float
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->with('prices')->get();
        if ($variants->isEmpty()) {
            return 0.0;
        }

        $prices = $variants->map(fn ($v) => $v->getPriceFor($customerType));
        return $prices->max() ?? 0.0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%")
              ->orWhere('short_description', 'like', "%{$term}%")
              ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$term}%"))
              ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$term}%"));
        });
    }

    public function scopeFilterByCategory($query, ?string $categorySlug)
    {
        if (blank($categorySlug) || $categorySlug === 'all') {
            return $query;
        }

        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug)
              ->orWhereHas('parent', fn ($p) => $p->where('slug', $categorySlug));
        });
    }

    public function scopeFilterByBrand($query, ?string $brandSlug)
    {
        if (blank($brandSlug) || $brandSlug === 'all') {
            return $query;
        }

        return $query->whereHas('brand', fn ($b) => $b->where('slug', $brandSlug));
    }

    public function scopeSortedBy($query, ?string $sortBy)
    {
        return match ($sortBy) {
            'price_low' => $query->orderBy(
                ProductVariant::select('price')
                    ->join('product_prices', 'product_variants.id', '=', 'product_prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('product_prices.customer_type', 'retail')
                    ->orderBy('price', 'asc')
                    ->limit(1),
                'asc'
            ),
            'price_high' => $query->orderBy(
                ProductVariant::select('price')
                    ->join('product_prices', 'product_variants.id', '=', 'product_prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('product_prices.customer_type', 'retail')
                    ->orderBy('price', 'desc')
                    ->limit(1),
                'desc'
            ),
            'popular' => $query->orderBy('view_count', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // 'newest'
        };
    }
}
