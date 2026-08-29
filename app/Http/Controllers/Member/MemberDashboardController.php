<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\PointTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load(['profile', 'addresses']);

        $totalOrdersCount = Order::where('user_id', $user->id)->count();
        $activeCouponsCount = Coupon::active()->count();
        $recentPointTransactions = PointTransaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('member.dashboard', compact('user', 'totalOrdersCount', 'activeCouponsCount', 'recentPointTransactions'));
    }

    public function profile(): View
    {
        $user = auth()->user()->load('profile');

        return view('member.profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $request->validated('bio'),
                'gender' => $request->validated('gender'),
                'birthdate' => $request->validated('birthdate'),
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Display member loyalty points and ledger transactions.
     */
    public function points(Request $request): View
    {
        $user = auth()->user();

        $transactions = PointTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        $totalEarned = PointTransaction::where('user_id', $user->id)
            ->where('points', '>', 0)
            ->sum('points');

        $totalRedeemed = abs(PointTransaction::where('user_id', $user->id)
            ->where('points', '<', 0)
            ->sum('points'));

        return view('member.points.index', compact('user', 'transactions', 'totalEarned', 'totalRedeemed'));
    }

    /**
     * Display available coupons and vouchers for member.
     */
    public function coupons(Request $request): View
    {
        $user = auth()->user();

        $activeCoupons = Coupon::active()
            ->latest()
            ->paginate(12);

        $myUsages = $user->couponUsages()->with('coupon', 'order')->latest()->get();

        return view('member.coupons.index', compact('user', 'activeCoupons', 'myUsages'));
    }
}
