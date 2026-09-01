<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Bergabung Bersama Sulastika Jaya');
    }

    public function test_new_members_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fatimah Zahra',
            'email' => 'fatimah@example.com',
            'phone' => '081234567891',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'member',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard'));

        $user = User::where('email', 'fatimah@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserRole::MEMBER, $user->role);
        $this->assertEquals(UserStatus::ACTIVE, $user->status);
        $this->assertNotNull($user->profile);
    }

    public function test_new_resellers_can_register_with_pending_status(): void
    {
        $response = $this->post('/register', [
            'name' => 'Mitra Busana Hijab',
            'email' => 'mitra@example.com',
            'phone' => '081234567892',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'reseller',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('reseller.dashboard'));

        $user = User::where('email', 'mitra@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserRole::RESELLER, $user->role);
        $this->assertEquals(UserStatus::PENDING, $user->status);
    }

    public function test_registration_fails_with_duplicate_email_or_phone(): void
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
            'phone' => '081111111111',
        ]);

        $response = $this->post('/register', [
            'name' => 'Duplicate User',
            'email' => 'duplicate@example.com',
            'phone' => '081111111111',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'member',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['email', 'phone']);
        $this->assertGuest();
    }
}
