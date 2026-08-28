<?php

namespace Tests\Feature\Payment;

use App\Actions\Order\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createSampleOrder(): Order
    {
        $variant = ProductVariant::first();
        
        $cartItem = CartItem::create([
            'session_id' => 'test-session-' . uniqid(),
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        return app(CreateOrderAction::class)->execute(
            cartItems: collect([$cartItem]),
            data: [
                'customer_name' => 'Ahmad Fauzi',
                'customer_email' => 'ahmad@example.com',
                'customer_phone' => '08123456789',
                'shipping_address' => [
                    'recipient_name' => 'Ahmad Fauzi',
                    'phone' => '08123456789',
                    'province_name' => 'DKI Jakarta',
                    'city_name' => 'Kota Jakarta Pusat',
                    'postal_code' => '10110',
                    'address_line' => 'Jl. Kebon Sirih No. 10',
                ],
                'shipping_service' => [
                    'courier_code' => 'jne',
                    'courier_name' => 'JNE',
                    'service_name' => 'REG',
                    'cost' => 18000,
                ],
                'payment_method' => 'qris',
            ]
        );
    }

    public function test_settlement_webhook_updates_order_to_paid(): void
    {
        $order = $this->createSampleOrder();

        $payload = [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => (string) $order->grand_total,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'transaction_id' => 'TRX-' . uniqid(),
            'signature_key' => 'dummy-signature',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson(route('webhook.midtrans'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $freshOrder = $order->fresh();
        $this->assertEquals(OrderStatus::PAID, $freshOrder->status);
        $this->assertEquals(PaymentStatus::SETTLEMENT, $freshOrder->payment_status);
        $this->assertNotNull($freshOrder->paid_at);
    }

    public function test_webhook_is_idempotent_on_duplicate_calls(): void
    {
        $order = $this->createSampleOrder();

        $payload = [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => (string) $order->grand_total,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'transaction_id' => 'TRX-' . uniqid(),
            'signature_key' => 'dummy-signature',
        ];

        // 1st call
        $this->postJson(route('webhook.midtrans'), $payload);
        $this->assertEquals(OrderStatus::PAID, $order->fresh()->status);

        // 2nd duplicate call
        $response2 = $this->postJson(route('webhook.midtrans'), $payload);
        $response2->assertStatus(200);
        $response2->assertJson(['status' => 'success']);
    }

    public function test_expired_webhook_cancels_order_and_restores_stock(): void
    {
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;

        $order = $this->createSampleOrder();
        $this->assertEquals($initialStock - 1, $variant->fresh()->stock);

        $payload = [
            'order_id' => $order->order_number,
            'status_code' => '202',
            'gross_amount' => (string) $order->grand_total,
            'transaction_status' => 'expire',
            'transaction_id' => 'TRX-' . uniqid(),
            'signature_key' => 'dummy-signature',
        ];

        $response = $this->postJson(route('webhook.midtrans'), $payload);

        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::CANCELLED, $order->fresh()->status);
        $this->assertEquals(PaymentStatus::EXPIRED, $order->fresh()->payment_status);

        // Stock restored
        $this->assertEquals($initialStock, $variant->fresh()->stock);
    }
}
