<?php

namespace Tests\Feature\Admin;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCouponManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_coupons_index(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.coupons.index'));
        $response->assertStatus(200)
            ->assertSee('Manajemen Kupon')
            ->assertSee('WELCOME10');
    }

    public function test_admin_can_create_new_coupon(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.coupons.store'), [
            'code' => 'SUPERPROMO',
            'name' => 'Super Promo Spesial',
            'description' => 'Diskon promo hebat',
            'type' => 'fixed',
            'amount' => 25000,
            'min_order_amount' => 100000,
            'max_uses' => 100,
            'per_user_limit' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code' => 'SUPERPROMO',
            'amount' => 25000,
        ]);
    }

    public function test_admin_can_toggle_coupon_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $coupon = Coupon::first();

        $initialStatus = $coupon->is_active;

        $response = $this->actingAs($admin)->post(route('admin.coupons.toggle', $coupon));
        $response->assertRedirect();

        $coupon->refresh();
        $this->assertEquals(!$initialStatus, $coupon->is_active);
    }

    public function test_admin_can_manually_adjust_member_points(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();
        $member->update(['loyalty_points' => 100]);

        $response = $this->actingAs($admin)->post(route('admin.points.adjust'), [
            'user_id' => $member->id,
            'points' => 250,
            'reason' => 'Bonus promo Ramadhan',
        ]);

        $response->assertRedirect();

        $member->refresh();
        $this->assertEquals(350, $member->loyalty_points);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $member->id,
            'points' => 250,
            'balance_after' => 350,
        ]);
    }

    public function test_non_admin_cannot_access_coupon_management(): void
    {
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($member)->get(route('admin.coupons.index'));
        $response->assertStatus(403);
    }
}
