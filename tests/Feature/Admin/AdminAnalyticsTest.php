<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_analytics_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.analytics.index'));
        $response->assertStatus(200)
            ->assertSee('Laporan & Analitik Penjualan')
            ->assertSee('Gross Revenue (GMV)')
            ->assertSee('Tren Omzet Penjualan');
    }

    public function test_admin_can_filter_analytics_by_period(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.analytics.index', ['period' => 'this_year']));
        $response->assertStatus(200)
            ->assertSee('Tahun Ini');
    }

    public function test_admin_can_export_orders_csv(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.analytics.export.orders', ['period' => 'this_month']));
        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_non_admin_cannot_access_analytics(): void
    {
        $member = User::where('role', 'member')->first();

        $response = $this->actingAs($member)->get(route('admin.analytics.index'));
        $response->assertStatus(403);
    }
}
