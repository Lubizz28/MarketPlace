<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_customers_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.customers.index'));
        $response->assertStatus(200)
            ->assertSee('Basis Data Pengguna & Member')
            ->assertSee('Member Belanja');
    }

    public function test_admin_can_view_customer_360_profile(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($admin)->get(route('admin.customers.show', $member));
        $response->assertStatus(200)
            ->assertSee($member->name)
            ->assertSee('Lifetime Spend (GMV)')
            ->assertSee('Saldo Poin');
    }

    public function test_admin_can_toggle_customer_ban_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($admin)->post(route('admin.customers.toggle', $member));
        $response->assertRedirect();

        $member->refresh();
        $this->assertEquals(UserStatus::BANNED, $member->status);

        // Unban
        $this->actingAs($admin)->post(route('admin.customers.toggle', $member));
        $member->refresh();
        $this->assertEquals(UserStatus::ACTIVE, $member->status);
    }

    public function test_admin_cannot_ban_administrator(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.customers.toggle', $admin));
        $response->assertRedirect();

        $admin->refresh();
        $this->assertEquals(UserStatus::ACTIVE, $admin->status);
    }
}
