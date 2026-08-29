<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsAndActivityLogsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_and_update_settings(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. View settings
        $response = $this->actingAs($admin)->get(route('admin.settings.index'));
        $response->assertStatus(200)
            ->assertSee('Pengaturan Platform & Toko')
            ->assertSee('Nama Toko Marketplace');

        // 2. Update settings
        $updateResponse = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'store_name' => 'MedinaStyle Official',
            'cs_phone' => '081299990000',
            'min_withdrawal_amount' => '75000',
        ]);

        $updateResponse->assertRedirect(route('admin.settings.index'));

        $this->assertEquals('MedinaStyle Official', Setting::get('store_name'));
        $this->assertEquals('081299990000', Setting::get('cs_phone'));
        $this->assertEquals('75000', Setting::get('min_withdrawal_amount'));

        // 3. Verify activity log was recorded
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'settings_updated',
            'user_id' => $admin->id,
        ]);
    }

    public function test_admin_can_view_and_filter_activity_logs(): void
    {
        $admin = User::where('role', 'admin')->first();

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'withdrawal_approved',
            'description' => 'Menyetujui pencairan dana reseller #WD-1001',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test Agent',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.activity-logs.index'));
        $response->assertStatus(200)
            ->assertSee('Log Aktivitas & Audit Trail')
            ->assertSee('withdrawal_approved');

        // Filter by action
        $filterResponse = $this->actingAs($admin)->get(route('admin.activity-logs.index', ['action' => 'withdrawal_approved']));
        $filterResponse->assertStatus(200)
            ->assertSee('Menyetujui pencairan dana reseller #WD-1001');
    }

    public function test_non_admin_cannot_access_settings_or_logs(): void
    {
        $member = User::where('role', 'member')->first();

        $this->actingAs($member)->get(route('admin.settings.index'))->assertStatus(403);
        $this->actingAs($member)->get(route('admin.activity-logs.index'))->assertStatus(403);
    }
}
