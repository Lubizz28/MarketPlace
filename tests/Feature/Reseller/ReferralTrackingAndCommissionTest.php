<?php

namespace Tests\Feature\Reseller;

use App\Actions\Order\UpdateOrderStatusAction;
use App\Enums\CommissionStatus;
use App\Enums\OrderStatus;
use App\Enums\WalletTransactionType;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\ResellerCommission;
use App\Models\ResellerProfile;
use App\Models\ResellerWallet;
use App\Models\User;
use App\Services\ResellerWalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferralTrackingAndCommissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_track_referral_middleware_sets_session_and_cookie(): void
    {
        $reseller = User::where('role', 'reseller')->first();

        $response = $this->get('/?ref=KHADIJAH');
        $response->assertStatus(200);
        $response->assertSessionHas('referral_reseller_id', $reseller->id);
        $response->assertCookie('referral_reseller_id', (string) $reseller->id);
    }

    public function test_order_creation_with_referral_allocates_pending_commission(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $initialPending = $wallet->pending_balance;

        $order = Order::create([
            'order_number' => 'ORD-REF-001',
            'customer_type' => 'retail',
            'reseller_id' => $reseller->id,
            'subtotal' => 200000,
            'grand_total' => 218000,
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081299990001',
        ]);

        $walletService = app(ResellerWalletService::class);
        $commission = $walletService->allocatePendingCommission($order);

        $this->assertNotNull($commission);
        $this->assertEquals(CommissionStatus::PENDING, $commission->status);
        $this->assertEquals(20000, $commission->commission_amount); // 10% of 200.000

        $wallet->refresh();
        $this->assertEquals($initialPending + 20000, $wallet->pending_balance);
    }

    public function test_commission_becomes_available_when_order_is_completed(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 0, 'pending_balance' => 0]);

        $variant = ProductVariant::with('product')->first();

        $order = Order::create([
            'order_number' => 'ORD-COMP-001',
            'customer_type' => 'retail',
            'reseller_id' => $reseller->id,
            'status' => OrderStatus::SHIPPED,
            'payment_status' => 'settlement',
            'subtotal' => 300000,
            'grand_total' => 318000,
            'customer_name' => 'Dewi Lestari',
            'customer_email' => 'dewi@example.com',
            'customer_phone' => '081299990002',
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
            'courier_code' => 'sicepat',
            'courier_name' => 'SiCepat',
            'service_name' => 'REG',
            'shipping_cost' => 18000,
            'weight_grams' => 1000,
            'status' => 'shipped',
        ]);

        $walletService = app(ResellerWalletService::class);
        $walletService->allocatePendingCommission($order);

        $wallet->refresh();
        $this->assertEquals(30000, $wallet->pending_balance);
        $this->assertEquals(0, $wallet->balance);

        // Transition order to COMPLETED
        $action = app(UpdateOrderStatusAction::class);
        $action->execute($order, OrderStatus::COMPLETED);

        $wallet->refresh();
        $this->assertEquals(0, $wallet->pending_balance);
        $this->assertEquals(30000, $wallet->balance);

        $this->assertDatabaseHas('reseller_commissions', [
            'order_id' => $order->id,
            'status' => CommissionStatus::AVAILABLE->value,
            'commission_amount' => 30000,
        ]);

        $this->assertDatabaseHas('reseller_wallet_transactions', [
            'user_id' => $reseller->id,
            'type' => WalletTransactionType::COMMISSION_EARNED->value,
            'amount' => 30000,
            'balance_after' => 30000,
        ]);
    }

    public function test_commission_is_cancelled_when_order_is_cancelled(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 0, 'pending_balance' => 0]);

        $variant = ProductVariant::with('product')->first();

        $order = Order::create([
            'order_number' => 'ORD-CANCEL-002',
            'customer_type' => 'retail',
            'reseller_id' => $reseller->id,
            'status' => OrderStatus::PENDING_PAYMENT,
            'payment_status' => 'unpaid',
            'subtotal' => 150000,
            'grand_total' => 165000,
            'customer_name' => 'Rina',
            'customer_email' => 'rina@example.com',
            'customer_phone' => '081299990003',
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

        $walletService = app(ResellerWalletService::class);
        $walletService->allocatePendingCommission($order);

        $wallet->refresh();
        $this->assertEquals(15000, $wallet->pending_balance);

        $action = app(UpdateOrderStatusAction::class);
        $action->execute($order, OrderStatus::CANCELLED);

        $wallet->refresh();
        $this->assertEquals(0, $wallet->pending_balance);

        $this->assertDatabaseHas('reseller_commissions', [
            'order_id' => $order->id,
            'status' => CommissionStatus::CANCELLED->value,
        ]);
    }

    public function test_reseller_can_view_commissions_page(): void
    {
        $reseller = User::where('role', 'reseller')->first();

        $response = $this->actingAs($reseller)->get(route('reseller.commissions.index'));
        $response->assertStatus(200)
            ->assertSee('Daftar Komisi Referral');
    }
}
