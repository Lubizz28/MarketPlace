<?php

namespace Tests\Feature\Admin;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBroadcastTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_broadcasts_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.broadcasts.index'));
        $response->assertStatus(200)
            ->assertSee('Pesan Siaran (Broadcast)');
    }

    public function test_admin_can_create_and_dispatch_broadcast(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post(route('admin.broadcasts.store'), [
            'title' => 'Diskon Kilat Ramadhan',
            'message' => 'Dapatkan diskon 25% khusus busana muslimah hari ini!',
            'target_role' => 'all',
            'channel' => 'both',
        ]);

        $response->assertRedirect(route('admin.broadcasts.index'));

        $this->assertDatabaseHas('broadcast_messages', [
            'title' => 'Diskon Kilat Ramadhan',
            'status' => 'sent',
        ]);

        $this->assertDatabaseHas('broadcast_logs', [
            'channel' => 'whatsapp',
            'status' => 'sent',
        ]);
    }

    public function test_non_admin_cannot_access_broadcasts(): void
    {
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($member)->get(route('admin.broadcasts.index'));
        $response->assertStatus(403);
    }
}
