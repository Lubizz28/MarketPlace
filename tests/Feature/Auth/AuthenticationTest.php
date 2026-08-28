<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
    }

    public function test_users_can_authenticate_using_email(): void
    {
        $user = User::factory()->create([
            'email' => 'buyer@example.com',
            'phone' => '081234567890',
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make('Password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'buyer@example.com',
            'password' => 'Password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard'));
    }

    public function test_users_can_authenticate_using_phone_number(): void
    {
        $user = User::factory()->create([
            'email' => 'seller@example.com',
            'phone' => '081999888777',
            'role' => UserRole::RESELLER,
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make('Password123'),
        ]);

        $response = $this->post('/login', [
            'email' => '081999888777',
            'password' => 'Password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('reseller.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123'),
        ]);

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_banned_users_cannot_authenticate(): void
    {
        $user = User::factory()->create([
            'email' => 'banned@example.com',
            'status' => UserStatus::BANNED,
            'password' => Hash::make('Password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'banned@example.com',
            'password' => 'Password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }
}
