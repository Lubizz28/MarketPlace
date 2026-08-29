<?php

namespace App\Services;

use App\Enums\PointTransactionType;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoyaltyPointService
{
    /**
     * Earning Rate: 1 Point per Rp 10.000 subtotal.
     */
    public const RUPIAH_PER_EARNED_POINT = 10000;

    /**
     * Redemption Value: 1 Point = Rp 10 discount.
     */
    public const RUPIAH_PER_POINT_DISCOUNT = 10;

    /**
     * Maximum subtotal percentage that can be paid using points (50%).
     */
    public const MAX_REDEMPTION_PERCENTAGE = 50;

    /**
     * Calculate how many points can be earned from a given subtotal.
     */
    public function calculateEarnablePoints(int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return (int) floor($subtotal / self::RUPIAH_PER_EARNED_POINT);
    }

    /**
     * Calculate points discount and validate redemption limits.
     *
     * @param int $pointsRequested
     * @param int $subtotal
     * @param User|null $user
     * @return array{
     *     points_to_redeem: int,
     *     discount_amount: int,
     *     formatted_discount: string,
     *     max_allowed_points: int
     * }
     */
    public function calculatePointsDiscount(int $pointsRequested, int $subtotal, ?User $user = null): array
    {
        if ($pointsRequested <= 0 || !$user) {
            return [
                'points_to_redeem' => 0,
                'discount_amount' => 0,
                'formatted_discount' => 'Rp 0',
                'max_allowed_points' => $user ? $this->getMaxRedeemablePoints($user, $subtotal) : 0,
            ];
        }

        $userBalance = $user->loyalty_points;
        $maxPointsAllowed = $this->getMaxRedeemablePoints($user, $subtotal);

        // Cap to available balance and max allowable
        $pointsToRedeem = min($pointsRequested, $userBalance, $maxPointsAllowed);
        $discountAmount = $pointsToRedeem * self::RUPIAH_PER_POINT_DISCOUNT;

        return [
            'points_to_redeem' => $pointsToRedeem,
            'discount_amount' => $discountAmount,
            'formatted_discount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
            'max_allowed_points' => $maxPointsAllowed,
        ];
    }

    /**
     * Maximum points user can redeem for the given subtotal.
     */
    public function getMaxRedeemablePoints(User $user, int $subtotal): int
    {
        $maxDiscount = (int) floor(($subtotal * self::MAX_REDEMPTION_PERCENTAGE) / 100);
        $maxPointsForOrder = (int) floor($maxDiscount / self::RUPIAH_PER_POINT_DISCOUNT);

        return min($user->loyalty_points, $maxPointsForOrder);
    }

    /**
     * Deduct points when placing an order.
     */
    public function redeemPoints(User $user, int $points, Order $order): ?PointTransaction
    {
        if ($points <= 0) {
            return null;
        }

        return DB::transaction(function () use ($user, $points, $order) {
            $lockedUser = User::lockForUpdate()->findOrFail($user->id);

            if ($lockedUser->loyalty_points < $points) {
                throw ValidationException::withMessages([
                    'points_redeemed' => ['Saldo poin loyalitas tidak mencukupi untuk penukaran.'],
                ]);
            }

            $lockedUser->decrement('loyalty_points', $points);
            $newBalance = $lockedUser->fresh()->loyalty_points;

            return PointTransaction::create([
                'user_id' => $lockedUser->id,
                'order_id' => $order->id,
                'type' => PointTransactionType::REDEEMED,
                'points' => -$points,
                'balance_after' => $newBalance,
                'description' => "Penukaran {$points} poin untuk diskon pesanan #{$order->order_number}",
            ]);
        });
    }

    /**
     * Award loyalty points to a member when their order is COMPLETED.
     */
    public function earnPointsForOrder(Order $order): ?PointTransaction
    {
        if (!$order->user_id) {
            return null;
        }

        $earnablePoints = $this->calculateEarnablePoints($order->subtotal);
        if ($earnablePoints <= 0) {
            return null;
        }

        return DB::transaction(function () use ($order, $earnablePoints) {
            // Check if already awarded
            $alreadyAwarded = PointTransaction::where('order_id', $order->id)
                ->where('type', PointTransactionType::EARNED)
                ->exists();

            if ($alreadyAwarded) {
                return null;
            }

            $user = User::lockForUpdate()->find($order->user_id);
            if (!$user) {
                return null;
            }

            $user->increment('loyalty_points', $earnablePoints);
            $newBalance = $user->fresh()->loyalty_points;

            return PointTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => PointTransactionType::EARNED,
                'points' => $earnablePoints,
                'balance_after' => $newBalance,
                'description' => "Perolehan {$earnablePoints} poin dari pesanan #{$order->order_number}",
            ]);
        });
    }

    /**
     * Refund redeemed points when an order is cancelled or refunded.
     */
    public function refundPointsForOrder(Order $order): void
    {
        if (!$order->user_id) {
            return;
        }

        DB::transaction(function () use ($order) {
            // 1. If points were redeemed, refund them
            if ($order->points_redeemed > 0) {
                $alreadyRefunded = PointTransaction::where('order_id', $order->id)
                    ->where('type', PointTransactionType::REFUNDED)
                    ->exists();

                if (!$alreadyRefunded) {
                    $user = User::lockForUpdate()->find($order->user_id);
                    if ($user) {
                        $user->increment('loyalty_points', $order->points_redeemed);
                        $newBalance = $user->fresh()->loyalty_points;

                        PointTransaction::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'type' => PointTransactionType::REFUNDED,
                            'points' => $order->points_redeemed,
                            'balance_after' => $newBalance,
                            'description' => "Pengembalian {$order->points_redeemed} poin dari pembatalan pesanan #{$order->order_number}",
                        ]);
                    }
                }
            }

            // 2. If points were previously earned and now refunded, reverse the earned points
            $earnedTx = PointTransaction::where('order_id', $order->id)
                ->where('type', PointTransactionType::EARNED)
                ->first();

            if ($earnedTx) {
                $reversed = PointTransaction::where('order_id', $order->id)
                    ->where('type', PointTransactionType::ADJUSTED)
                    ->where('points', -$earnedTx->points)
                    ->exists();

                if (!$reversed) {
                    $user = User::lockForUpdate()->find($order->user_id);
                    if ($user) {
                        $deduction = min($user->loyalty_points, $earnedTx->points);
                        $user->decrement('loyalty_points', $deduction);
                        $newBalance = $user->fresh()->loyalty_points;

                        PointTransaction::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'type' => PointTransactionType::ADJUSTED,
                            'points' => -$deduction,
                            'balance_after' => $newBalance,
                            'description' => "Pembatalan perolehan poin dari pesanan #{$order->order_number} (Status: {$order->status->label()})",
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Manually adjust a user's loyalty points by admin.
     */
    public function manualAdjust(User $user, int $pointsChange, string $reason, ?User $actor = null): PointTransaction
    {
        return DB::transaction(function () use ($user, $pointsChange, $reason, $actor) {
            $lockedUser = User::lockForUpdate()->findOrFail($user->id);

            $currentBalance = $lockedUser->loyalty_points;
            $newBalance = max(0, $currentBalance + $pointsChange);
            $actualPoints = $newBalance - $currentBalance;

            $lockedUser->update(['loyalty_points' => $newBalance]);

            $actorNote = $actor ? " (Admin: {$actor->name})" : '';

            return PointTransaction::create([
                'user_id' => $lockedUser->id,
                'order_id' => null,
                'type' => PointTransactionType::ADJUSTED,
                'points' => $actualPoints,
                'balance_after' => $newBalance,
                'description' => "Penyesuaian saldo poin: {$reason}{$actorNote}",
            ]);
        });
    }
}
