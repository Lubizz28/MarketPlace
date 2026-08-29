<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PointTransactionType;
use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\User;
use App\Services\LoyaltyPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PointTransactionController extends Controller
{
    public function __construct(
        protected LoyaltyPointService $loyaltyPointService
    ) {}

    public function index(Request $request): View
    {
        $query = PointTransaction::query()->with(['user', 'order']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        $totalActivePoints = User::sum('loyalty_points');
        $totalPointsEarned = PointTransaction::where('points', '>', 0)->sum('points');
        $totalPointsRedeemed = abs(PointTransaction::where('points', '<', 0)->sum('points'));

        $members = User::where('role', 'member')->orderBy('name')->get();

        return view('admin.points.index', compact('transactions', 'totalActivePoints', 'totalPointsEarned', 'totalPointsRedeemed', 'members'));
    }

    public function adjust(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($validated['user_id']);

        $this->loyaltyPointService->manualAdjust(
            user: $user,
            pointsChange: (int) $validated['points'],
            reason: $validated['reason'],
            actor: auth()->user()
        );

        return back()->with('success', "Saldo poin member {$user->name} berhasil disesuaikan.");
    }
}
