<?php

namespace Tests\Feature\Loyalty;

use App\Actions\Order\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Enums\PointTransactionType;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\LoyaltyPointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoyaltyPointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_member_earns_points_when_order_is_completed(): void
    {
        $member = User::where('role', 'member')->first();
        $member->update(['loyalty_points' => 0]);

        $variant = ProductVariant::with('product')->first();

        $order = Order::create([
            'order_number' => 'ORD-LOYALTY-001',
            'user_id' => $member->id,
            'customer_type' => 'member',
            'status' => OrderStatus::SHIPPED,
            'payment_status' => 'settlement',
            'subtotal' => 200000, // Should earn 20 points (200.000 / 10.000)
            'grand_total' => 218000,
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => '08123456789',
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'variant_name' => $variant->name,
            'sku' => $variant->sku,
            'price' => $order->subtotal,
            'quantity' => 1,
            'subtotal' => $order->subtotal,
        ]);

        $order->shipment()->create([
            'courier_code' => 'jne',
            'courier_name' => 'JNE',
            'service_name' => 'REG',
            'shipping_cost' => 18000,
            'weight_grams' => 1000,
            'status' => 'shipped',
        ]);

        $action = app(UpdateOrderStatusAction::class);
        $action->execute($order, OrderStatus::COMPLETED);

        $member->refresh();
        $this->assertEquals(20, $member->loyalty_points);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $member->id,
            'order_id' => $order->id,
            'type' => PointTransactionType::EARNED->value,
            'points' => 20,
            'balance_after' => 20,
        ]);
    }

    public function test_member_can_redeem_points_and_create_ledger(): void
    {
        $member = User::where('role', 'member')->first();
        $member->update(['loyalty_points' => 500]); // 500 points = Rp 5.000

        $order = Order::create([
            'order_number' => 'ORD-REDEEM-001',
            'user_id' => $member->id,
            'customer_type' => 'member',
            'subtotal' => 100000,
            'points_redeemed' => 200,
            'points_discount' => 2000,
            'grand_total' => 98000,
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => '08123456789',
        ]);

        $pointService = app(LoyaltyPointService::class);
        $pointService->redeemPoints($member, 200, $order);

        $member->refresh();
        $this->assertEquals(300, $member->loyalty_points);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $member->id,
            'order_id' => $order->id,
            'type' => PointTransactionType::REDEEMED->value,
            'points' => -200,
            'balance_after' => 300,
        ]);
    }

    public function test_points_refunded_when_order_is_cancelled(): void
    {
        $member = User::where('role', 'member')->first();
        $member->update(['loyalty_points' => 300]);

        $variant = ProductVariant::with('product')->first();

        $order = Order::create([
            'order_number' => 'ORD-CANCEL-001',
            'user_id' => $member->id,
            'customer_type' => 'member',
            'status' => OrderStatus::PENDING_PAYMENT,
            'payment_status' => 'unpaid',
            'subtotal' => 100000,
            'points_redeemed' => 200,
            'points_discount' => 2000,
            'grand_total' => 98000,
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => '08123456789',
        ]);

        $order->items()->create([
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'variant_name' => $variant->name,
            'sku' => $variant->sku,
            'price' => $order->subtotal,
            'quantity' => 1,
            'subtotal' => $order->subtotal,
        ]);

        $action = app(UpdateOrderStatusAction::class);
        $action->execute($order, OrderStatus::CANCELLED);

        $member->refresh();
        $this->assertEquals(500, $member->loyalty_points); // 300 + 200 refunded

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $member->id,
            'order_id' => $order->id,
            'type' => PointTransactionType::REFUNDED->value,
            'points' => 200,
            'balance_after' => 500,
        ]);
    }

    public function test_ajax_point_calculation_endpoint(): void
    {
        $member = User::where('role', 'member')->first();
        $member->update(['loyalty_points' => 1000]);

        $response = $this->actingAs($member)
            ->postJson(route('checkout.points.calculate'), [
                'points' => 300,
                'subtotal' => 100000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'points_to_redeem' => 300,
                'discount_amount' => 3000, // 300 * 10 = Rp 3.000
            ]);
    }

    public function test_member_can_view_points_page(): void
    {
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($member)->get(route('member.points.index'));
        $response->assertStatus(200)
            ->assertSee('Poin Reward Anda')
            ->assertSee('Riwayat Mutasi Poin');
    }
}
