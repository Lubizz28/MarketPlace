<?php

namespace Tests\Feature\Order;

use App\Actions\Order\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createSampleOrder(?User $user = null): Order
    {
        $variant = ProductVariant::first();
        
        $cartItem = CartItem::create([
            'user_id' => $user?->id,
            'session_id' => 'test-session-' . uniqid(),
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        return app(CreateOrderAction::class)->execute(
            cartItems: collect([$cartItem]),
            data: [
                'customer_name' => $user?->name ?? 'Guest User',
                'customer_email' => $user?->email ?? 'guest@example.com',
                'customer_phone' => $user?->phone ?? '08123456789',
                'shipping_address' => [
                    'recipient_name' => 'Recipient Name',
                    'phone' => '08123456789',
                    'province_name' => 'DKI Jakarta',
                    'city_name' => 'Kota Jakarta Pusat',
                    'postal_code' => '10110',
                    'address_line' => 'Alamat Pengiriman Lengkap',
                ],
                'shipping_service' => [
                    'courier_code' => 'jne',
                    'courier_name' => 'JNE',
                    'service_name' => 'REG',
                    'cost' => 18000,
                ],
                'payment_method' => 'qris',
            ],
            user: $user
        );
    }

    public function test_can_view_order_details_page(): void
    {
        $order = $this->createSampleOrder();

        $response = $this->get(route('orders.show', $order->order_number));

        $response->assertStatus(200);
        $response->assertSeeText($order->order_number);
        $response->assertSeeText('FAKTUR PESANAN');
        $response->assertSeeText('Menunggu Pembayaran');
    }

    public function test_unpaid_order_can_be_cancelled_and_restores_stock(): void
    {
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;

        $order = $this->createSampleOrder();
        $this->assertEquals($initialStock - 1, $variant->fresh()->stock);

        // Cancel the order
        $response = $this->post(route('orders.cancel', $order->order_number));

        $response->assertRedirect(route('orders.show', $order->order_number));
        $this->assertEquals(OrderStatus::CANCELLED, $order->fresh()->status);
        $this->assertEquals(PaymentStatus::EXPIRED, $order->fresh()->payment_status);

        // Stock must be restored
        $this->assertEquals($initialStock, $variant->fresh()->stock);
    }

    public function test_member_can_view_order_history_in_dashboard(): void
    {
        $member = User::where('email', 'member@medinastyle.com')->first();
        $order = $this->createSampleOrder($member);

        $response = $this->actingAs($member)->get(route('member.orders.index'));

        $response->assertStatus(200);
        $response->assertSeeText($order->order_number);
        $response->assertSeeText('Daftar Transaksi & Pesanan');
    }
}
