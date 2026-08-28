<?php

namespace Tests\Feature\Member;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_address_book(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->get('/member/addresses');

        $response->assertStatus(200);
        $response->assertSee('Buku Alamat Pengiriman');
    }

    public function test_member_can_add_new_address(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
        ]);

        $response = $this->actingAs($user)->post('/member/addresses', [
            'label' => 'Rumah Utama',
            'recipient_name' => 'Fatimah',
            'phone' => '081234567890',
            'address_line' => 'Jl. Merak No. 5',
            'province_name' => 'DKI Jakarta',
            'city_name' => 'Jakarta Selatan',
            'district_name' => 'Tebet',
            'postal_code' => '12810',
            'is_primary' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Fatimah',
            'is_primary' => true,
        ]);
    }

    public function test_member_cannot_modify_other_users_address_idor(): void
    {
        $userA = User::factory()->create(['role' => UserRole::MEMBER]);
        $userB = User::factory()->create(['role' => UserRole::MEMBER]);

        $addressB = Address::create([
            'user_id' => $userB->id,
            'label' => 'Rumah B',
            'recipient_name' => 'User B',
            'phone' => '081299999999',
            'address_line' => 'Alamat B',
            'province_name' => 'Jawa Barat',
            'city_name' => 'Bandung',
            'postal_code' => '40111',
            'is_primary' => true,
        ]);

        // User A tries to set User B's address as primary
        $response = $this->actingAs($userA)->patch("/member/addresses/{$addressB->id}/primary");
        $response->assertStatus(403);

        // User A tries to delete User B's address
        $deleteResponse = $this->actingAs($userA)->delete("/member/addresses/{$addressB->id}");
        $deleteResponse->assertStatus(403);

        $this->assertDatabaseHas('addresses', ['id' => $addressB->id]);
    }
}
