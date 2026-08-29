<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Enums\WalletTransactionType;
use App\Enums\WithdrawalStatus;
use App\Models\ResellerWallet;
use App\Models\ResellerWithdrawal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResellerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_resellers_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.resellers.index'));
        $response->assertStatus(200)
            ->assertSee('Manajemen Mitra Reseller')
            ->assertSee('Khadijah Hijab Store');
    }

    public function test_admin_can_verify_pending_reseller(): void
    {
        $admin = User::where('role', 'admin')->first();
        $reseller = User::where('role', 'reseller')->first();
        $reseller->update(['status' => UserStatus::PENDING]);

        $response = $this->actingAs($admin)->post(route('admin.resellers.verify', $reseller));
        $response->assertRedirect();

        $reseller->refresh();
        $this->assertEquals(UserStatus::ACTIVE, $reseller->status);
    }

    public function test_admin_can_approve_and_pay_withdrawal(): void
    {
        $admin = User::where('role', 'admin')->first();
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 100000, 'total_withdrawn' => 0]);

        $withdrawal = ResellerWithdrawal::create([
            'withdrawal_number' => 'WD-TEST-001',
            'user_id' => $reseller->id,
            'amount' => 50000,
            'bank_name' => 'BCA',
            'bank_account_number' => '123456789',
            'bank_account_name' => 'Khadijah',
            'status' => WithdrawalStatus::PENDING,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.withdrawals.process', $withdrawal), [
            'status' => WithdrawalStatus::PAID->value,
            'notes' => 'Transfer berhasil via KlikBCA Ref #889911',
        ]);

        $response->assertRedirect();

        $withdrawal->refresh();
        $this->assertEquals(WithdrawalStatus::PAID, $withdrawal->status);
        $this->assertEquals($admin->id, $withdrawal->processed_by);

        $wallet->refresh();
        $this->assertEquals(50000, $wallet->total_withdrawn);

        $this->assertDatabaseHas('reseller_wallet_transactions', [
            'user_id' => $reseller->id,
            'type' => WalletTransactionType::WITHDRAWAL_PAID->value,
        ]);
    }

    public function test_admin_can_reject_withdrawal_and_refunds_balance(): void
    {
        $admin = User::where('role', 'admin')->first();
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 50000]); // 100k - 50k held

        $withdrawal = ResellerWithdrawal::create([
            'withdrawal_number' => 'WD-TEST-002',
            'user_id' => $reseller->id,
            'amount' => 50000,
            'bank_name' => 'BCA',
            'bank_account_number' => '123456789',
            'bank_account_name' => 'Khadijah',
            'status' => WithdrawalStatus::PENDING,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.withdrawals.process', $withdrawal), [
            'status' => WithdrawalStatus::REJECTED->value,
            'notes' => 'Nomor rekening salah',
        ]);

        $response->assertRedirect();

        $withdrawal->refresh();
        $this->assertEquals(WithdrawalStatus::REJECTED, $withdrawal->status);

        $wallet->refresh();
        $this->assertEquals(100000, $wallet->balance); // 50k + 50k refunded

        $this->assertDatabaseHas('reseller_wallet_transactions', [
            'user_id' => $reseller->id,
            'type' => WalletTransactionType::WITHDRAWAL_REFUND->value,
            'amount' => 50000,
            'balance_after' => 100000,
        ]);
    }

    public function test_non_admin_cannot_access_admin_resellers(): void
    {
        $reseller = User::where('role', 'reseller')->first();

        $response = $this->actingAs($reseller)->get(route('admin.resellers.index'));
        $response->assertStatus(403);
    }
}
