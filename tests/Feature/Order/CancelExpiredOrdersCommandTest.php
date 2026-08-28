<?php

namespace Tests\Feature\Order;

use App\Actions\Order\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelExpiredOrdersCommandTest extends TestCase
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
            'quantity' => 2,
        ]);

        return app(CreateOrderAction::class)->execute(
            cartItems: collect([$cartItem]),
            data: [
                'customer_name' => 'Expired User',
                'customer_email' => 'expired@example.com',
                'customer_phone' => '08123456789',
                'shipping_address' => [
                    'recipient_name' => 'Expired User',
                    'phone' => '08123456789',
                    'province_name' => 'DKI Jakarta',
                    'city_name' => 'Kota Jakarta Pusat',
                    'postal_code' => '10110',
                    'address_line' => 'Alamat Pengiriman',
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

    public function test_artisan_command_cancels_only_expired_unpaid_orders_and_restores_stock(): void
    {
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;

        // Order 1: Expired 2 hours ago
        $expiredOrder = $this->createSampleOrder();
        $expiredOrder->update(['expires_at' => now()->subHours(2)]);

        // Order 2: Still active (expires in 20 hours)
        $activeOrder = $this->createSampleOrder();
        $activeOrder->update(['expires_at' => now()->addHours(20)]);

        // Stock decreased by 4 items (2 per order)
        $this->assertEquals($initialStock - 4, $variant->fresh()->stock);

        // Run artisan command
        $this->artisan('orders:cancel-expired')
            ->expectsOutput('Scanning for expired pending orders...')
            ->assertSuccessful();

        // Expired order should be cancelled and payment expired
        $this->assertEquals(OrderStatus::CANCELLED, $expiredOrder->fresh()->status);
        $this->assertEquals(PaymentStatus::EXPIRED, $expiredOrder->fresh()->payment_status);

        // Active order must remain pending payment
        $this->assertEquals(OrderStatus::PENDING_PAYMENT, $activeOrder->fresh()->status);

        // Stock of 2 items from expired order should be returned (initialStock - 2)
        $this->assertEquals($initialStock - 2, $variant->fresh()->stock);
    }
}
