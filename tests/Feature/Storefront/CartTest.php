<?php

namespace Tests\Feature\Storefront;

use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_can_add_item_to_cart(): void
    {
        $variant = ProductVariant::first();

        $response = $this->post(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cartResponse = $this->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($variant->product->name);
    }

    public function test_member_can_add_item_and_gets_member_pricing(): void
    {
        $member = User::where('email', 'member@medinastyle.com')->first();
        $variant = ProductVariant::first();

        $response = $this->actingAs($member)->post(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();

        $cartResponse = $this->actingAs($member)->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee(number_format($variant->getPriceFor('member'), 0, ',', '.'));
    }

    public function test_cannot_add_more_quantity_than_available_stock(): void
    {
        $variant = ProductVariant::first();
        $variant->update(['stock' => 3]);

        $this->post(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 10,
        ]);

        $cartTotals = app(\App\Services\CartService::class)->getCartTotals();
        $this->assertEquals(3, $cartTotals['total_items']);
    }

    public function test_cart_item_quantity_can_be_updated_and_removed(): void
    {
        $variant = ProductVariant::first();

        $this->post(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::first();
        $this->assertNotNull($cartItem);

        // Update quantity
        $this->patch(route('cart.update', $cartItem->id), [
            'quantity' => 3,
        ]);
        $this->assertEquals(3, $cartItem->fresh()->quantity);

        // Remove item
        $this->delete(route('cart.remove', $cartItem->id));
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
