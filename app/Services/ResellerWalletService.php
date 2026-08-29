<?php

namespace App\Services;

use App\Enums\CommissionStatus;
use App\Enums\WalletTransactionType;
use App\Enums\WithdrawalStatus;
use App\Models\Order;
use App\Models\ResellerCommission;
use App\Models\ResellerWallet;
use App\Models\ResellerWalletTransaction;
use App\Models\ResellerWithdrawal;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResellerWalletService
{
    public const MINIMUM_WITHDRAWAL_AMOUNT = 50000;

    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Get or create wallet for a reseller user.
     */
    public function getOrCreateWallet(User $reseller): ResellerWallet
    {
        return ResellerWallet::firstOrCreate(
            ['user_id' => $reseller->id],
            ['balance' => 0, 'pending_balance' => 0, 'total_withdrawn' => 0]
        );
    }

    /**
     * Allocate pending commission when order is created via referral.
     */
    public function allocatePendingCommission(Order $order): ?ResellerCommission
    {
        if (!$order->reseller_id) {
            return null;
        }

        $reseller = User::with('resellerProfile')->find($order->reseller_id);
        if (!$reseller || !$reseller->isReseller()) {
            return null;
        }

        $rate = (int) ($reseller->resellerProfile?->commission_rate_percent ?? 10);
        $commissionAmount = (int) round(($order->subtotal * $rate) / 100);

        if ($commissionAmount <= 0) {
            return null;
        }

        return DB::transaction(function () use ($order, $reseller, $rate, $commissionAmount) {
            $commission = ResellerCommission::create([
                'reseller_id' => $reseller->id,
                'order_id' => $order->id,
                'subtotal' => $order->subtotal,
                'commission_percent' => $rate,
                'commission_amount' => $commissionAmount,
                'status' => CommissionStatus::PENDING,
            ]);

            $wallet = $this->getOrCreateWallet($reseller);
            $wallet->increment('pending_balance', $commissionAmount);

            return $commission;
        });
    }

    /**
     * Credit available commission to reseller wallet when order is COMPLETED.
     */
    public function creditCommissionOnOrderCompleted(Order $order): ?ResellerCommission
    {
        $commission = ResellerCommission::where('order_id', $order->id)
            ->where('status', CommissionStatus::PENDING)
            ->first();

        if (!$commission) {
            return null;
        }

        return DB::transaction(function () use ($commission, $order) {
            $commission->update([
                'status' => CommissionStatus::AVAILABLE,
                'mature_at' => now(),
            ]);

            $wallet = ResellerWallet::lockForUpdate()->where('user_id', $commission->reseller_id)->first();
            if ($wallet) {
                $deductPending = min($wallet->pending_balance, $commission->commission_amount);
                $wallet->decrement('pending_balance', $deductPending);
                $wallet->increment('balance', $commission->commission_amount);
                $newBalance = $wallet->fresh()->balance;

                ResellerWalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $commission->reseller_id,
                    'type' => WalletTransactionType::COMMISSION_EARNED,
                    'amount' => $commission->commission_amount,
                    'balance_after' => $newBalance,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'description' => "Pencairan komisi {$commission->commission_percent}% untuk pesanan selesai #{$order->order_number}",
                ]);
            }

            return $commission;
        });
    }

    /**
     * Cancel commission when order is CANCELLED or REFUNDED.
     */
    public function cancelCommissionOnOrderCancelled(Order $order): void
    {
        $commission = ResellerCommission::where('order_id', $order->id)
            ->whereIn('status', [CommissionStatus::PENDING, CommissionStatus::AVAILABLE])
            ->first();

        if (!$commission) {
            return;
        }

        DB::transaction(function () use ($commission, $order) {
            $initialStatus = $commission->status;
            $commission->update(['status' => CommissionStatus::CANCELLED]);

            $wallet = ResellerWallet::lockForUpdate()->where('user_id', $commission->reseller_id)->first();
            if ($wallet) {
                if ($initialStatus === CommissionStatus::PENDING) {
                    $deductPending = min($wallet->pending_balance, $commission->commission_amount);
                    $wallet->decrement('pending_balance', $deductPending);
                } elseif ($initialStatus === CommissionStatus::AVAILABLE) {
                    $deductBalance = min($wallet->balance, $commission->commission_amount);
                    $wallet->decrement('balance', $deductBalance);
                    $newBalance = $wallet->fresh()->balance;

                    ResellerWalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'user_id' => $commission->reseller_id,
                        'type' => WalletTransactionType::ADJUSTMENT,
                        'amount' => -$deductBalance,
                        'balance_after' => $newBalance,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'description' => "Pembatalan komisi dari pesanan #{$order->order_number} (Status: {$order->status->label()})",
                    ]);
                }
            }
        });
    }

    /**
     * Request a withdrawal of available balance by reseller.
     */
    public function requestWithdrawal(User $reseller, int $amount, array $bankInfo): ResellerWithdrawal
    {
        if ($amount < self::MINIMUM_WITHDRAWAL_AMOUNT) {
            $minFormatted = 'Rp ' . number_format(self::MINIMUM_WITHDRAWAL_AMOUNT, 0, ',', '.');
            throw ValidationException::withMessages([
                'amount' => ["Minimal penarikan saldo dompet adalah {$minFormatted}."],
            ]);
        }

        return DB::transaction(function () use ($reseller, $amount, $bankInfo) {
            $wallet = ResellerWallet::lockForUpdate()->where('user_id', $reseller->id)->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw ValidationException::withMessages([
                    'amount' => ['Saldo dompet yang tersedia tidak mencukupi untuk penarikan ini.'],
                ]);
            }

            $wallet->decrement('balance', $amount);
            $newBalance = $wallet->fresh()->balance;

            do {
                $withdrawalNumber = 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            } while (ResellerWithdrawal::where('withdrawal_number', $withdrawalNumber)->exists());

            $withdrawal = ResellerWithdrawal::create([
                'withdrawal_number' => $withdrawalNumber,
                'user_id' => $reseller->id,
                'amount' => $amount,
                'bank_name' => $bankInfo['bank_name'],
                'bank_account_number' => $bankInfo['bank_account_number'],
                'bank_account_name' => $bankInfo['bank_account_name'],
                'status' => WithdrawalStatus::PENDING,
                'notes' => $bankInfo['notes'] ?? null,
            ]);

            ResellerWalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $reseller->id,
                'type' => WalletTransactionType::WITHDRAWAL_HOLD,
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'reference_type' => 'withdrawal',
                'reference_id' => $withdrawal->id,
                'description' => "Pengajuan penarikan dana #{$withdrawal->withdrawal_number} ke {$withdrawal->bank_name} ({$withdrawal->bank_account_number})",
            ]);

            return $withdrawal;
        });
    }

    /**
     * Process withdrawal (Approve/Pay or Reject) by Admin.
     */
    public function processWithdrawal(
        ResellerWithdrawal $withdrawal,
        WithdrawalStatus $targetStatus,
        ?User $admin = null,
        ?string $notes = null,
        ?string $proofImage = null
    ): ResellerWithdrawal {
        return DB::transaction(function () use ($withdrawal, $targetStatus, $admin, $notes, $proofImage) {
            $lockedWithdrawal = ResellerWithdrawal::lockForUpdate()->findOrFail($withdrawal->id);

            if ($lockedWithdrawal->status !== WithdrawalStatus::PENDING && $lockedWithdrawal->status !== WithdrawalStatus::APPROVED) {
                throw new \InvalidArgumentException("Pengajuan penarikan ini sudah berstatus {$lockedWithdrawal->status->label()} dan tidak dapat diubah lagi.");
            }

            $wallet = ResellerWallet::lockForUpdate()->where('user_id', $lockedWithdrawal->user_id)->firstOrFail();

            if ($targetStatus === WithdrawalStatus::PAID) {
                $lockedWithdrawal->update([
                    'status' => WithdrawalStatus::PAID,
                    'processed_by' => $admin?->id,
                    'processed_at' => now(),
                    'proof_image' => $proofImage ?? $lockedWithdrawal->proof_image,
                    'notes' => $notes ?? $lockedWithdrawal->notes,
                ]);

                $wallet->increment('total_withdrawn', $lockedWithdrawal->amount);

                ResellerWalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $lockedWithdrawal->user_id,
                    'type' => WalletTransactionType::WITHDRAWAL_PAID,
                    'amount' => 0, // already deducted at hold
                    'balance_after' => $wallet->balance,
                    'reference_type' => 'withdrawal',
                    'reference_id' => $lockedWithdrawal->id,
                    'description' => "Penarikan dana #{$lockedWithdrawal->withdrawal_number} telah sukses ditransfer oleh Admin",
                ]);
            } elseif ($targetStatus === WithdrawalStatus::REJECTED) {
                $lockedWithdrawal->update([
                    'status' => WithdrawalStatus::REJECTED,
                    'processed_by' => $admin?->id,
                    'processed_at' => now(),
                    'notes' => $notes,
                ]);

                // Refund the held amount back to available balance
                $wallet->increment('balance', $lockedWithdrawal->amount);
                $newBalance = $wallet->fresh()->balance;

                ResellerWalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => $lockedWithdrawal->user_id,
                    'type' => WalletTransactionType::WITHDRAWAL_REFUND,
                    'amount' => $lockedWithdrawal->amount,
                    'balance_after' => $newBalance,
                    'reference_type' => 'withdrawal',
                    'reference_id' => $lockedWithdrawal->id,
                    'description' => "Pengembalian saldo dari penolakan penarikan #{$lockedWithdrawal->withdrawal_number}" . ($notes ? " (Alasan: {$notes})" : ''),
                ]);
            } elseif ($targetStatus === WithdrawalStatus::APPROVED) {
                $lockedWithdrawal->update([
                    'status' => WithdrawalStatus::APPROVED,
                    'processed_by' => $admin?->id,
                    'notes' => $notes ?? $lockedWithdrawal->notes,
                ]);
            }

            $freshWithdrawal = $lockedWithdrawal->fresh(['user']);

            // Send Payout Notification
            if ($targetStatus === WithdrawalStatus::PAID || $targetStatus === WithdrawalStatus::REJECTED) {
                $this->notificationService->sendWithdrawalProcessedNotification($freshWithdrawal);
            }

            return $freshWithdrawal;
        });
    }
}
