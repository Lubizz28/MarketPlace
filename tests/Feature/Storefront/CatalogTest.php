<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_can_be_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('SULASTIKA');
        $response->assertSee('Sulastika Jaya');
        $response->assertSeeText('Kategori Pilihan Sulastika');
        $response->assertSee('scrollToTop');
        $response->assertSee('koleksi-busana');
    }

    public function test_catalog_can_be_rendered_with_products(): void
    {
        $response = $this->get(route('catalog'));

        $response->assertStatus(200);
        $response->assertSeeText('Semua Koleksi Busana Muslim');
        $response->assertSeeText('Abaya Silk Jacquard Medina');
    }

    public function test_catalog_can_be_searched(): void
    {
        $response = $this->get(route('catalog', ['q' => 'Mukena']));

        $response->assertStatus(200);
        $response->assertSeeText('Mukena Sutra Royale');
        $response->assertDontSeeText('Baju Koko Kurta Toyobo');
    }

    public function test_catalog_can_be_filtered_by_category(): void
    {
        $response = $this->get(route('catalog', ['category' => 'gamis-abaya']));

        $response->assertStatus(200);
        $response->assertSeeText('Abaya Silk Jacquard Medina');
        $response->assertSeeText('Gamis Ceruty Babydoll Malika');
        $response->assertDontSeeText('Baju Koko Kurta Toyobo');
    }

    public function test_catalog_can_be_sorted(): void
    {
        $response = $this->get(route('catalog', ['sort' => 'price_low']));

        $response->assertStatus(200);
        $response->assertSeeText('Khimar Syar\'i Silk Voal Ultrafine');
    }

    public function test_product_detail_page_can_be_rendered(): void
    {
        $product = Product::first();

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSeeText($product->name);
        $response->assertSeeText('Pilih Varian:');
        $response->assertSee('selectedVariant');
        $response->assertSee('formatRupiah');
    }
}
