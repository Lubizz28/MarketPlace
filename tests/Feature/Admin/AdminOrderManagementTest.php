<?php

namespace Tests\Feature\Admin;

use App\Actions\Order\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@medinastyle.com')->first();
        $this->member = User::where('email', 'member@medinastyle.com')->first();
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
                'customer_name' => 'Fulan bin Fulan',
                'customer_email' => 'fulan@example.com',
                'customer_phone' => '08123456789',
                'shipping_address' => [
                    'recipient_name' => 'Fulan bin Fulan',
                    'phone' => '08123456789',
                    'province_name' => 'DKI Jakarta',
                    'city_name' => 'Kota Jakarta Pusat',
                    'postal_code' => '10110',
                    'address_line' => 'Jl. Kebon Sirih No. 10',
                ],
                'shipping_service' => [
                    'courier_code' => 'jne',
                    'courier_name' => 'Jalur Nugraha Ekakurir (JNE)',
                    'service_name' => 'REG',
                    'cost' => 18000,
                ],
                'payment_method' => 'qris',
            ],
            user: $user
        );
    }

    public function test_admin_can_view_orders_index_with_statistics(): void
    {
        $order = $this->createSampleOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSeeText($order->order_number);
        $response->assertSeeText('Manajemen Pesanan & Lifecycle');
    }

    public function test_admin_can_view_single_order_details_and_transaction_logs(): void
    {
        $order = $this->createSampleOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order->order_number));

        $response->assertStatus(200);
        $response->assertSeeText($order->order_number);
        $response->assertSeeText('Audit Trail Webhook & Transaksi Pembayaran');
    }

    public function test_admin_can_fulfill_shipment_with_tracking_number(): void
    {
        $order = $this->createSampleOrder();
        $order->update(['status' => OrderStatus::PAID, 'payment_status' => PaymentStatus::SETTLEMENT]);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.shipment', $order->order_number), [
            'tracking_number' => 'JNE-987654321',
            'notes' => 'Paket sudah dipickup kurir sore hari',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order->order_number));

        $freshOrder = $order->fresh(['shipment']);
        $this->assertEquals(OrderStatus::SHIPPED, $freshOrder->status);
        $this->assertEquals('JNE-987654321', $freshOrder->shipment->tracking_number);
        $this->assertEquals('shipped', $freshOrder->shipment->status);
        $this->assertNotNull($freshOrder->shipment->shipped_at);
    }

    public function test_admin_can_update_order_status_to_processing(): void
    {
        $order = $this->createSampleOrder();
        $order->update(['status' => OrderStatus::PAID, 'payment_status' => PaymentStatus::SETTLEMENT]);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.status', $order->order_number), [
            'status' => OrderStatus::PROCESSING->value,
            'reason' => 'Sedang dijahit dan disiapkan di butik',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order->order_number));
        $this->assertEquals(OrderStatus::PROCESSING, $order->fresh()->status);
    }

    public function test_admin_can_view_payment_transactions_audit_log(): void
    {
        $order = $this->createSampleOrder();

        $response = $this->actingAs($this->admin)->get(route('admin.payments.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Audit Trail Webhook & Transaksi Pembayaran');
        $response->assertSeeText($order->order_number);
    }

    public function test_non_admin_cannot_access_admin_orders(): void
    {
        $order = $this->createSampleOrder();

        $response = $this->actingAs($this->member)->get(route('admin.orders.index'));
        $response->assertStatus(403);
    }
}
