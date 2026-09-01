<?php

namespace Tests\Feature\Security;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_security_headers_are_present_on_all_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_sql_injection_attempt_is_safely_escaped(): void
    {
        $response = $this->get('/catalog?q=' . urlencode("' OR '1'='1"));
        $response->assertStatus(200);
    }

    public function test_unauthenticated_guest_cannot_view_or_cancel_registered_member_order(): void
    {
        $member = User::factory()->create([
            'role' => UserRole::MEMBER,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-SEC-001',
            'user_id' => $member->id,
            'customer_type' => 'member',
            'status' => OrderStatus::PENDING_PAYMENT,
            'subtotal' => 250000,
            'grand_total' => 268000,
            'customer_name' => 'Member User',
            'customer_email' => 'member@example.com',
            'customer_phone' => '081234567890',
        ]);

        // Guest attempts to view member order
        $viewResponse = $this->get(route('orders.show', $order->order_number));
        $viewResponse->assertStatus(403);

        // Guest attempts to view member invoice
        $invoiceResponse = $this->get(route('orders.invoice', $order->order_number));
        $invoiceResponse->assertStatus(403);

        // Guest attempts to cancel member order
        $cancelResponse = $this->post(route('orders.cancel', $order->order_number));
        $cancelResponse->assertStatus(403);
    }

    public function test_member_cannot_view_or_cancel_another_members_order(): void
    {
        $memberA = User::factory()->create(['role' => UserRole::MEMBER]);
        $memberB = User::factory()->create(['role' => UserRole::MEMBER]);

        $orderA = Order::create([
            'order_number' => 'ORD-SEC-002',
            'user_id' => $memberA->id,
            'customer_type' => 'member',
            'status' => OrderStatus::PENDING_PAYMENT,
            'subtotal' => 300000,
            'grand_total' => 318000,
            'customer_name' => 'Member A',
            'customer_email' => 'membera@example.com',
            'customer_phone' => '081234567891',
        ]);

        // Member B attempts to view Member A's order
        $response = $this->actingAs($memberB)->get(route('orders.show', $orderA->order_number));
        $response->assertStatus(403);

        // Member B attempts to cancel Member A's order
        $cancelResponse = $this->actingAs($memberB)->post(route('orders.cancel', $orderA->order_number));
        $cancelResponse->assertStatus(403);
    }

    public function test_member_cannot_modify_or_delete_another_members_address(): void
    {
        $memberA = User::factory()->create(['role' => UserRole::MEMBER]);
        $memberB = User::factory()->create(['role' => UserRole::MEMBER]);

        $addressA = Address::create([
            'user_id' => $memberA->id,
            'label' => 'Rumah A',
            'recipient_name' => 'Member A',
            'phone' => '081234567891',
            'province_id' => '1',
            'province_name' => 'DKI Jakarta',
            'city_id' => '152',
            'city_name' => 'Jakarta Pusat',
            'subdistrict_name' => 'Menteng',
            'postal_code' => '10310',
            'address_line' => 'Jl. Menteng No. 1',
            'is_primary' => true,
        ]);

        // Member B attempts to delete Member A's address
        $response = $this->actingAs($memberB)->delete(route('member.addresses.destroy', $addressA));
        $response->assertStatus(403);

        // Member B attempts to set Member A's address as primary
        $primaryResponse = $this->actingAs($memberB)->patch(route('member.addresses.primary', $addressA));
        $primaryResponse->assertStatus(403);
    }

    public function test_client_side_price_tampering_is_ignored_and_computed_server_side(): void
    {
        $variant = ProductVariant::first();
        $realPrice = $variant->getPriceFor('retail');
        $sessionId = 'sec_tamper_sess';

        CartItem::create([
            'session_id' => $sessionId,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        // Attempt to pass forged subtotal and grand_total in POST body
        $response = $this->withSession(['cart_session_id' => $sessionId])->post(route('checkout.process'), [
            'customer_name' => 'Attacker',
            'customer_email' => 'attacker@example.com',
            'customer_phone' => '081234567899',
            'recipient_name' => 'Attacker',
            'recipient_phone' => '081234567899',
            'province_id' => '1',
            'province_name' => 'DKI Jakarta',
            'city_id' => '152',
            'city_name' => 'Kota Jakarta Pusat',
            'subdistrict_name' => 'Menteng',
            'postal_code' => '10310',
            'address_line' => 'Jl. Cyber No. 99',
            'courier_code' => 'jne',
            'courier_name' => 'Jalur Nugraha Ekakurir (JNE)',
            'service_name' => 'REG',
            'service_description' => 'Layanan Reguler',
            'etd_days' => '2-3',
            'shipping_cost' => 18000,
            'payment_method' => 'qris',
            // Forged prices that attacker hopes will be accepted
            'subtotal' => 100,
            'grand_total' => 100,
            'price' => 100,
        ]);

        $order = Order::where('customer_email', 'attacker@example.com')->first();
        $this->assertNotNull($order);

        // Grand total must reflect genuine price + shipping, completely ignoring forged input
        $this->assertEquals($realPrice + 18000, $order->grand_total);
        $this->assertEquals($realPrice, $order->subtotal);
    }
}
