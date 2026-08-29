<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_product_catalog(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200)
            ->assertSee('Daftar Produk Busana')
            ->assertSee('+ Tambah Produk Baru');
    }

    public function test_admin_can_create_product_with_initial_variant_and_stock(): void
    {
        $admin = User::where('role', 'admin')->first();
        $category = Category::first();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Abaya Silk Al-Fath Premium',
            'category_id' => $category->id,
            'base_price' => 380000,
            'member_price' => 342000,
            'reseller_price' => 304000,
            'weight_grams' => 600,
            'description' => 'Abaya sutra arab premium dengan renda eksklusif.',
            'sku_prefix' => 'ABY-ALFATH',
            'variant_name' => 'L / Hitam Midnight',
            'initial_stock' => 25,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Abaya Silk Al-Fath Premium',
            'sku' => 'ABY-ALFATH',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'ABY-ALFATH-01',
            'stock' => 25,
        ]);

        $this->assertDatabaseHas('product_prices', [
            'customer_type' => 'retail',
            'price' => 380000,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'type' => 'opening',
            'quantity' => 25,
            'balance_after' => 25,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = User::where('role', 'admin')->first();
        $product = Product::first();
        $category = Category::first();

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), [
            'name' => 'Gamis Medina Updated',
            'category_id' => $category->id,
            'base_price' => 420000,
            'member_price' => 380000,
            'reseller_price' => 340000,
            'weight_grams' => 550,
            'description' => 'Updated description text.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertEquals('Gamis Medina Updated', $product->name);
        $this->assertEquals(420000, $product->getMinPriceFor('retail'));
    }

    public function test_admin_can_toggle_product_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $product = Product::first();
        $initialStatus = $product->is_active;

        $response = $this->actingAs($admin)->post(route('admin.products.toggle', $product));
        $response->assertRedirect();

        $product->refresh();
        $this->assertNotEquals($initialStatus, $product->is_active);
    }

    public function test_non_admin_cannot_access_product_management(): void
    {
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($member)->get(route('admin.products.index'));
        $response->assertStatus(403);
    }
}
