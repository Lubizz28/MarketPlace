<?php

namespace Tests\Feature\Reseller;

use App\Enums\WalletTransactionType;
use App\Enums\WithdrawalStatus;
use App\Models\ResellerProfile;
use App\Models\ResellerWallet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResellerWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_reseller_cannot_withdraw_below_minimum(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 200000]);

        $response = $this->actingAs($reseller)->post(route('reseller.withdrawals.store'), [
            'amount' => 20000, // Below 50.000
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Khadijah',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_reseller_cannot_withdraw_more_than_available_balance(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 60000]);

        $response = $this->actingAs($reseller)->post(route('reseller.withdrawals.store'), [
            'amount' => 100000, // 100.000 > 60.000
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Khadijah',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_valid_withdrawal_holds_balance_and_creates_ledger(): void
    {
        $reseller = User::where('role', 'reseller')->first();
        $wallet = ResellerWallet::where('user_id', $reseller->id)->first();
        $wallet->update(['balance' => 200000]);

        $response = $this->actingAs($reseller)->post(route('reseller.withdrawals.store'), [
            'amount' => 75000,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Khadijah Hijab Store',
        ]);

        $response->assertRedirect(route('reseller.withdrawals.index'));

        $wallet->refresh();
        $this->assertEquals(125000, $wallet->balance);

        $this->assertDatabaseHas('reseller_withdrawals', [
            'user_id' => $reseller->id,
            'amount' => 75000,
            'status' => WithdrawalStatus::PENDING->value,
        ]);

        $this->assertDatabaseHas('reseller_wallet_transactions', [
            'user_id' => $reseller->id,
            'type' => WalletTransactionType::WITHDRAWAL_HOLD->value,
            'amount' => -75000,
            'balance_after' => 125000,
        ]);
    }

    public function test_reseller_can_update_bank_profile(): void
    {
        $reseller = User::where('role', 'reseller')->first();

        $response = $this->actingAs($reseller)->post(route('reseller.profile.update'), [
            'store_name' => 'Khadijah Boutique',
            'referral_code' => 'KHADIJAHNEW',
            'bank_name' => 'Mandiri',
            'bank_account_number' => '9876543210',
            'bank_account_name' => 'Khadijah Putri',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('reseller_profiles', [
            'user_id' => $reseller->id,
            'store_name' => 'Khadijah Boutique',
            'referral_code' => 'KHADIJAHNEW',
            'bank_name' => 'Mandiri',
        ]);
    }
}
