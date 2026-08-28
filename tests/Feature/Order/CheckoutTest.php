<?php

namespace Tests\Feature\Order;

use App\Enums\InventoryMovementType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_checkout_screen_can_be_rendered_with_cart_items(): void
    {
        $variant = ProductVariant::first();
        $sessionId = 'test_session_1';

        CartItem::create([
            'session_id' => $sessionId,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->withSession(['cart_session_id' => $sessionId])->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Konfirmasi Pemesanan & Checkout');
        $response->assertSeeText($variant->product->name);
    }

    public function test_empty_cart_redirects_away_from_checkout(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
    }

    public function test_guest_can_complete_checkout_and_order_is_created(): void
    {
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;
        $sessionId = 'test_session_2';

        CartItem::create([
            'session_id' => $sessionId,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->withSession(['cart_session_id' => $sessionId])->post(route('checkout.process'), [
            'customer_name' => 'Fatimah Az-Zahra',
            'customer_email' => 'fatimah@example.com',
            'customer_phone' => '081234567890',
            'recipient_name' => 'Fatimah Az-Zahra',
            'recipient_phone' => '081234567890',
            'province_id' => '1',
            'province_name' => 'DKI Jakarta',
            'city_id' => '152',
            'city_name' => 'Kota Jakarta Pusat',
            'subdistrict_name' => 'Menteng',
            'postal_code' => '10310',
            'address_line' => 'Jl. Teuku Umar No. 12',
            'courier_code' => 'jne',
            'courier_name' => 'Jalur Nugraha Ekakurir (JNE)',
            'service_name' => 'REG',
            'service_description' => 'Layanan Reguler',
            'etd_days' => '2-3',
            'shipping_cost' => 18000,
            'payment_method' => 'qris',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('orders.show', $order->order_number));

        // Check Order data
        $this->assertEquals('Fatimah Az-Zahra', $order->customer_name);
        $this->assertEquals(OrderStatus::PENDING_PAYMENT, $order->status);
        $this->assertEquals(PaymentStatus::UNPAID, $order->payment_status);
        $this->assertEquals(18000, $order->shipping_cost);
        $this->assertEquals($variant->getPriceFor('retail') * 2 + 18000, $order->grand_total);

        // Check Inventory Ledger (SALE)
        $this->assertEquals($initialStock - 2, $variant->fresh()->stock);
        $this->assertDatabaseHas('inventory_movements', [
            'product_variant_id' => $variant->id,
            'type' => InventoryMovementType::SALE->value,
            'quantity' => 2,
            'reference_type' => 'order',
            'reference_id' => $order->id,
        ]);

        // Check Cart is cleared
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_member_checkout_uses_member_pricing_and_links_user_id(): void
    {
        $member = User::where('email', 'member@medinastyle.com')->first();
        $variant = ProductVariant::first();

        CartItem::create([
            'user_id' => $member->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($member)->post(route('checkout.process'), [
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => $member->phone,
            'recipient_name' => $member->name,
            'recipient_phone' => $member->phone,
            'province_name' => 'Jawa Barat',
            'city_name' => 'Kota Bandung',
            'postal_code' => '40111',
            'address_line' => 'Jl. Dago No. 45',
            'courier_code' => 'sicepat',
            'courier_name' => 'SiCepat Ekspres',
            'service_name' => 'SIUNT',
            'shipping_cost' => 17000,
            'payment_method' => 'bca_va',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals($member->id, $order->user_id);
        $this->assertEquals('member', $order->customer_type->value);
        $this->assertEquals($variant->getPriceFor('member') + 17000, $order->grand_total);
    }
}
