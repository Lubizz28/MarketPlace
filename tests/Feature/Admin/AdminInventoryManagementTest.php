<?php

namespace Tests\Feature\Admin;

use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_inventory_stock_matrix(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.inventory.index'));
        $response->assertStatus(200)
            ->assertSee('Inventori & Stok Varian')
            ->assertSee('Total Unit Fisik');
    }

    public function test_admin_can_filter_low_stock_variants(): void
    {
        $admin = User::where('role', 'admin')->first();
        $variant = ProductVariant::first();
        $variant->update(['stock' => 3]);

        $response = $this->actingAs($admin)->get(route('admin.inventory.index', ['filter' => 'low_stock']));
        $response->assertStatus(200)
            ->assertSee($variant->sku);
    }

    public function test_admin_can_adjust_variant_stock_with_audit_ledger(): void
    {
        $admin = User::where('role', 'admin')->first();
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;

        $response = $this->actingAs($admin)->post(route('admin.inventory.adjust', $variant), [
            'type' => 'restock',
            'quantity' => 15,
            'notes' => 'Restock pengiriman dari vendor konveksi',
        ]);

        $response->assertRedirect();

        $variant->refresh();
        $this->assertEquals($initialStock + 15, $variant->stock);

        $this->assertDatabaseHas('inventory_movements', [
            'product_variant_id' => $variant->id,
            'type' => 'restock',
            'quantity' => 15,
            'balance_after' => $initialStock + 15,
            'notes' => 'Restock pengiriman dari vendor konveksi',
        ]);
    }

    public function test_admin_can_view_variant_movements_history(): void
    {
        $admin = User::where('role', 'admin')->first();
        $variant = ProductVariant::first();

        $response = $this->actingAs($admin)->get(route('admin.inventory.movements', $variant));
        $response->assertStatus(200)
            ->assertSee('Audit Ledger Stok')
            ->assertSee($variant->sku);
    }
}
