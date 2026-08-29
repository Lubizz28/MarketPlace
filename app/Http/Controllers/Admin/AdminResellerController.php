<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KycStatus;
use App\Enums\UserRole;
use App\Enums\WithdrawalStatus;
use App\Http\Controllers\Controller;
use App\Models\ResellerCommission;
use App\Models\ResellerProfile;
use App\Models\ResellerWithdrawal;
use App\Models\User;
use App\Services\ResellerWalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminResellerController extends Controller
{
    public function __construct(
        protected ResellerWalletService $resellerWalletService
    ) {}

    /**
     * List all Resellers & Applications.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', UserRole::RESELLER)
            ->with(['resellerProfile', 'resellerWallet'])
            ->withCount('referralOrders');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $resellers = $query->latest()->paginate(15)->withQueryString();

        $totalResellersCount = User::where('role', UserRole::RESELLER)->count();
        $pendingResellersCount = User::where('role', UserRole::RESELLER)->where('status', 'pending')->count();
        $totalCommissionsPaid = ResellerWithdrawal::where('status', WithdrawalStatus::PAID)->sum('amount');

        return view('admin.resellers.index', compact('resellers', 'totalResellersCount', 'pendingResellersCount', 'totalCommissionsPaid'));
    }

    /**
     * Show Reseller Details.
     */
    public function show(User $reseller): View
    {
        abort_unless($reseller->isReseller(), 404);

        $reseller->load(['profile', 'resellerProfile', 'resellerWallet']);
        $wallet = $this->resellerWalletService->getOrCreateWallet($reseller);

        $commissions = ResellerCommission::where('reseller_id', $reseller->id)
            ->with('order')
            ->latest()
            ->paginate(15, ['*'], 'commissions_page');

        $withdrawals = ResellerWithdrawal::where('user_id', $reseller->id)
            ->latest()
            ->paginate(10, ['*'], 'withdrawals_page');

        return view('admin.resellers.show', compact('reseller', 'wallet', 'commissions', 'withdrawals'));
    }

    /**
     * Approve / Verify Reseller Account.
     */
    public function verify(User $reseller): RedirectResponse
    {
        abort_unless($reseller->isReseller(), 404);

        $reseller->update(['status' => 'active']);

        $reseller->resellerProfile()->updateOrCreate(
            ['user_id' => $reseller->id],
            [
                'kyc_status' => KycStatus::VERIFIED,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]
        );

        return back()->with('success', "Akun kemitraan reseller {$reseller->name} telah berhasil diverifikasi.");
    }

    /**
     * Reject Reseller Account.
     */
    public function reject(User $reseller): RedirectResponse
    {
        abort_unless($reseller->isReseller(), 404);

        $reseller->update(['status' => 'suspended']);

        $reseller->resellerProfile()->updateOrCreate(
            ['user_id' => $reseller->id],
            ['kyc_status' => KycStatus::REJECTED]
        );

        return back()->with('success', "Pendaftaran reseller {$reseller->name} telah ditolak.");
    }

    /**
     * List all Reseller Withdrawals.
     */
    public function withdrawals(Request $request): View
    {
        $query = ResellerWithdrawal::with(['user', 'processor']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('withdrawal_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(20)->withQueryString();

        $totalPendingWithdrawals = ResellerWithdrawal::where('status', WithdrawalStatus::PENDING)->sum('amount');
        $totalPaidWithdrawals = ResellerWithdrawal::where('status', WithdrawalStatus::PAID)->sum('amount');

        return view('admin.withdrawals.index', compact('withdrawals', 'totalPendingWithdrawals', 'totalPaidWithdrawals'));
    }

    /**
     * Process Withdrawal (Approve / Pay / Reject).
     */
    public function processWithdrawal(Request $request, ResellerWithdrawal $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(WithdrawalStatus::class)],
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->resellerWalletService->processWithdrawal(
                withdrawal: $withdrawal,
                targetStatus: WithdrawalStatus::from($validated['status']),
                admin: auth()->user(),
                notes: $validated['notes'] ?? null
            );

            return back()->with('success', "Status penarikan dana #{$withdrawal->withdrawal_number} berhasil diperbarui.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}
