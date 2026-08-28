<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_protected_routes(): void
    {
        $this->get('/member/dashboard')->assertRedirect(route('login'));
        $this->get('/reseller/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_member_can_access_member_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($member)->get('/member/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Member');
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($member)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_member_cannot_access_reseller_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($member)->get('/reseller/dashboard');

        $response->assertStatus(403);
    }

    public function test_reseller_can_access_reseller_dashboard(): void
    {
        $reseller = User::factory()->create([
            'role' => UserRole::RESELLER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($reseller)->get('/reseller/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Reseller');
    }

    public function test_reseller_cannot_access_admin_dashboard(): void
    {
        $reseller = User::factory()->create([
            'role' => UserRole::RESELLER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($reseller)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard_and_users(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Pusat Kendali Administrator');

        $usersResponse = $this->actingAs($admin)->get('/admin/users');
        $usersResponse->assertStatus(200);
        $usersResponse->assertSee('Daftar Pengguna Platform');
    }
}
