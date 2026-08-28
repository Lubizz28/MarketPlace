<?php

namespace Database\Seeders;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Enums\CustomerType;
use App\Enums\InventoryMovementType;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $movementAction = app(RecordInventoryMovementAction::class);

        // 1. Categories
        $categoriesData = [
            ['name' => 'Gamis & Abaya', 'slug' => 'gamis-abaya', 'icon' => 'sparkles', 'sort_order' => 1],
            ['name' => 'Hijab & Khimar', 'slug' => 'hijab-khimar', 'icon' => 'swatch', 'sort_order' => 2],
            ['name' => 'Baju Koko & Kurta', 'slug' => 'koko-kurta', 'icon' => 'shield-check', 'sort_order' => 3],
            ['name' => 'Mukena Sutra', 'slug' => 'mukena-sutra', 'icon' => 'heart', 'sort_order' => 4],
            ['name' => 'Aksesoris Muslim', 'slug' => 'aksesoris-muslim', 'icon' => 'tag', 'sort_order' => 5],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
        }

        // 2. Brands
        $brandsData = [
            ['name' => 'Medina Signature', 'slug' => 'medina-signature', 'description' => 'Koleksi busana syar\'i haute couture MedinaStyle.'],
            ['name' => 'Al-Zahra Haute', 'slug' => 'al-zahra-haute', 'description' => 'Gamis sutra jacquard dan abaya bordir eksklusif.'],
            ['name' => 'El-Malik Exclusive', 'slug' => 'el-malik-exclusive', 'description' => 'Pakaian koko kurta pria modern bahan premium.'],
            ['name' => 'Royale Silk', 'slug' => 'royale-silk', 'description' => 'Mukena dan hijab sutra berkelas internasional.'],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::firstOrCreate(
                ['slug' => $b['slug']],
                array_merge($b, ['is_active' => true])
            );
        }

        // 3. Products List with Variants & Multi-tier Pricing
        $productsData = [
            [
                'name' => 'Abaya Silk Jacquard Medina',
                'slug' => 'abaya-silk-jacquard-medina',
                'sku' => 'MED-ABY-001',
                'category_slug' => 'gamis-abaya',
                'brand_slug' => 'medina-signature',
                'short_description' => 'Abaya sutra jacquard eksklusif dengan sentuhan bordir emas halus berstandar butik syar\'i.',
                'description' => 'Dibuat dari kain sutra jacquard impor yang lembut, dingin, dan jatuh anggun. Dihiasi bordir presisi di bagian pergelangan tangan dan dada, dilengkapi resleting depan ramah busui (busui-friendly) dan wudhu-friendly.',
                'weight_grams' => 600,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=800&auto=format&fit=crop',
                'variants' => [
                    ['name' => 'Hitam Jetblack - Size M', 'color' => 'Jetblack', 'color_hex' => '#121212', 'size' => 'M', 'stock' => 25, 'retail' => 450000, 'member' => 405000, 'reseller' => 315000],
                    ['name' => 'Hitam Jetblack - Size L', 'color' => 'Jetblack', 'color_hex' => '#121212', 'size' => 'L', 'stock' => 20, 'retail' => 450000, 'member' => 405000, 'reseller' => 315000],
                    ['name' => 'Emerald Green - Size M', 'color' => 'Emerald', 'color_hex' => '#164E3D', 'size' => 'M', 'stock' => 15, 'retail' => 450000, 'member' => 405000, 'reseller' => 315000],
                ],
            ],
            [
                'name' => 'Gamis Ceruty Babydoll Malika',
                'slug' => 'gamis-ceruty-babydoll-malika',
                'sku' => 'MED-GMS-002',
                'category_slug' => 'gamis-abaya',
                'brand_slug' => 'al-zahra-haute',
                'short_description' => 'Gamis anggun bertingkat bahan Ceruty Babydoll premium berlapis furing katun adem.',
                'description' => 'Kombinasi layer ceruty premium dengan furing katun hero yang menyerap keringat. Potongan flare lebar syar\'i memberikan siluet mewah saat berjalan.',
                'weight_grams' => 650,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=800&auto=format&fit=crop',
                'variants' => [
                    ['name' => 'Misty Rose - Size L', 'color' => 'Misty Rose', 'color_hex' => '#DDA0DD', 'size' => 'L', 'stock' => 30, 'retail' => 385000, 'member' => 345000, 'reseller' => 270000],
                    ['name' => 'Misty Rose - Size XL', 'color' => 'Misty Rose', 'color_hex' => '#DDA0DD', 'size' => 'XL', 'stock' => 18, 'retail' => 385000, 'member' => 345000, 'reseller' => 270000],
                    ['name' => 'Sage Green - Size L', 'color' => 'Sage Green', 'color_hex' => '#9CAF88', 'size' => 'L', 'stock' => 22, 'retail' => 385000, 'member' => 345000, 'reseller' => 270000],
                ],
            ],
            [
                'name' => 'Khimar Syar\'i Silk Voal Ultrafine',
                'slug' => 'khimar-syari-silk-voal-ultrafine',
                'sku' => 'MED-KHM-003',
                'category_slug' => 'hijab-khimar',
                'brand_slug' => 'royale-silk',
                'short_description' => 'Khimar syar\'i potongan lancip modern berbahan voal ultrafine silk yang tegak di dahi.',
                'description' => 'Khimar mewah berukuran jumbo yang menutup dada dan punggung sempurna. Serat voal ultrafine memberikan sirkulasi udara maksimal tanpa mudah kusut.',
                'weight_grams' => 250,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?q=80&w=800&auto=format&fit=crop',
                'variants' => [
                    ['name' => 'Champagne Cream - All Size', 'color' => 'Champagne', 'color_hex' => '#F7E7CE', 'size' => 'All Size', 'stock' => 50, 'retail' => 195000, 'member' => 175000, 'reseller' => 135000],
                    ['name' => 'Charcoal Grey - All Size', 'color' => 'Charcoal', 'color_hex' => '#36454F', 'size' => 'All Size', 'stock' => 40, 'retail' => 195000, 'member' => 175000, 'reseller' => 135000],
                ],
            ],
            [
                'name' => 'Baju Koko Kurta Toyobo El-Malik',
                'slug' => 'baju-koko-kurta-toyobo-el-malik',
                'sku' => 'MED-KOK-004',
                'category_slug' => 'koko-kurta',
                'brand_slug' => 'el-malik-exclusive',
                'short_description' => 'Koko kurta pria muslim bahan Katun Toyobo Fodu Jepang dengan aksen kerah mandarin.',
                'description' => 'Kemeja koko kurta pria modern dengan jahitan butik rapi, kancing eksklusif berukir logo, dan kantong fungsional di bagian samping dan dada.',
                'weight_grams' => 350,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop',
                'variants' => [
                    ['name' => 'Pure White - Size L', 'color' => 'Pure White', 'color_hex' => '#FFFFFF', 'size' => 'L', 'stock' => 30, 'retail' => 295000, 'member' => 265000, 'reseller' => 205000],
                    ['name' => 'Pure White - Size XL', 'color' => 'Pure White', 'color_hex' => '#FFFFFF', 'size' => 'XL', 'stock' => 25, 'retail' => 295000, 'member' => 265000, 'reseller' => 205000],
                    ['name' => 'Navy Blue - Size L', 'color' => 'Navy Blue', 'color_hex' => '#000080', 'size' => 'L', 'stock' => 20, 'retail' => 295000, 'member' => 265000, 'reseller' => 205000],
                ],
            ],
            [
                'name' => 'Mukena Sutra Royale Renda Giper',
                'slug' => 'mukena-sutra-royale-renda-giper',
                'sku' => 'MED-MUK-005',
                'category_slug' => 'mukena-sutra',
                'brand_slug' => 'royale-silk',
                'short_description' => 'Set perlengkapan ibadah mukena sutra lembut bertabur renda giper mewah dan tas pouch bepergian.',
                'description' => 'Memberikan kenyamanan dan ketenangan maksimal saat bermunajat. Terbuat dari serat sutra lembut berdaya tahan tinggi dengan hiasan renda giper import.',
                'weight_grams' => 700,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop',
                'variants' => [
                    ['name' => 'Soft Pearl White - All Size', 'color' => 'Pearl White', 'color_hex' => '#FDF6E2', 'size' => 'All Size', 'stock' => 15, 'retail' => 580000, 'member' => 520000, 'reseller' => 410000],
                    ['name' => 'Dusty Lilac - All Size', 'color' => 'Dusty Lilac', 'color_hex' => '#C8A2C8', 'size' => 'All Size', 'stock' => 12, 'retail' => 580000, 'member' => 520000, 'reseller' => 410000],
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::firstOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $categories[$pData['category_slug']]->id,
                    'brand_id' => $brands[$pData['brand_slug']]->id,
                    'name' => $pData['name'],
                    'sku' => $pData['sku'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'weight_grams' => $pData['weight_grams'],
                    'is_featured' => $pData['is_featured'],
                    'is_active' => true,
                ]
            );

            // Primary Image
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $pData['image']],
                ['is_primary' => true, 'sort_order' => 0]
            );

            // Variants & Prices & Initial Inventory Ledger
            foreach ($pData['variants'] as $vData) {
                $variantSku = $product->sku . '-' . Str::slug($vData['name']);

                $variant = ProductVariant::firstOrCreate(
                    ['sku' => $variantSku],
                    [
                        'product_id' => $product->id,
                        'name' => $vData['name'],
                        'color_name' => $vData['color'],
                        'color_hex' => $vData['color_hex'],
                        'size' => $vData['size'],
                        'stock' => $vData['stock'],
                        'is_active' => true,
                    ]
                );

                // Multi-tier Prices
                ProductPrice::updateOrCreate(
                    ['product_variant_id' => $variant->id, 'customer_type' => CustomerType::RETAIL],
                    ['price' => $vData['retail'], 'min_quantity' => 1]
                );
                ProductPrice::updateOrCreate(
                    ['product_variant_id' => $variant->id, 'customer_type' => CustomerType::MEMBER],
                    ['price' => $vData['member'], 'min_quantity' => 1]
                );
                ProductPrice::updateOrCreate(
                    ['product_variant_id' => $variant->id, 'customer_type' => CustomerType::RESELLER],
                    ['price' => $vData['reseller'], 'min_quantity' => 1]
                );

                // Initial Opening Stock in Inventory Ledger if not yet recorded
                if ($variant->inventoryMovements()->count() === 0) {
                    $variant->inventoryMovements()->create([
                        'type' => InventoryMovementType::OPENING,
                        'quantity' => $vData['stock'],
                        'balance_after' => $vData['stock'],
                        'notes' => 'Stok awal inisialisasi katalog katalog butik',
                    ]);
                }
            }
        }
    }
}
