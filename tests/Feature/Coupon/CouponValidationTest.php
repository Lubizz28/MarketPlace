<?php

namespace Tests\Feature\Coupon;

use App\Actions\Coupon\ValidateCouponAction;
use App\Enums\CouponType;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CouponValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_validate_fixed_amount_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'FIXED50',
            'name' => 'Diskon 50rb',
            'type' => CouponType::FIXED,
            'amount' => 50000,
            'min_order_amount' => 100000,
            'is_active' => true,
        ]);

        $action = app(ValidateCouponAction::class);
        $result = $action->execute('FIXED50', 200000);

        $this->assertTrue($result['valid']);
        $this->assertEquals(50000, $result['discount_amount']);
    }

    public function test_can_validate_percentage_coupon_with_max_discount_cap(): void
    {
        $coupon = Coupon::create([
            'code' => 'DISC20',
            'name' => 'Diskon 20%',
            'type' => CouponType::PERCENT,
            'amount' => 20,
            'min_order_amount' => 100000,
            'max_discount' => 30000, // 20% of 200.000 is 40.000, but capped at 30.000
            'is_active' => true,
        ]);

        $action = app(ValidateCouponAction::class);
        $result = $action->execute('DISC20', 200000);

        $this->assertTrue($result['valid']);
        $this->assertEquals(30000, $result['discount_amount']);
    }

    public function test_coupon_validation_fails_if_expired(): void
    {
        Coupon::create([
            'code' => 'EXPIRED99',
            'name' => 'Expired Coupon',
            'type' => CouponType::FIXED,
            'amount' => 10000,
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $action = app(ValidateCouponAction::class);
        $action->execute('EXPIRED99', 100000);
    }

    public function test_coupon_validation_fails_if_min_order_not_met(): void
    {
        Coupon::create([
            'code' => 'MIN500K',
            'name' => 'Min 500k Coupon',
            'type' => CouponType::FIXED,
            'amount' => 50000,
            'min_order_amount' => 500000,
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $action = app(ValidateCouponAction::class);
        $action->execute('MIN500K', 200000); // 200k < 500k
    }

    public function test_coupon_validation_fails_if_user_usage_limit_reached(): void
    {
        $user = User::where('role', 'member')->first();
        $coupon = Coupon::create([
            'code' => 'ONETIME',
            'name' => 'One Time Use Coupon',
            'type' => CouponType::FIXED,
            'amount' => 10000,
            'per_user_limit' => 1,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'user_id' => $user->id,
            'customer_type' => 'member',
            'subtotal' => 100000,
            'grand_total' => 90000,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
        ]);

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'discount_applied' => 10000,
        ]);

        $this->expectException(ValidationException::class);

        $action = app(ValidateCouponAction::class);
        $action->execute('ONETIME', 100000, $user);
    }

    public function test_ajax_coupon_validation_endpoint(): void
    {
        $response = $this->postJson(route('checkout.coupon.validate'), [
            'coupon_code' => 'WELCOME10',
            'subtotal' => 200000,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'coupon' => [
                    'code' => 'WELCOME10',
                ],
            ]);
    }
}
