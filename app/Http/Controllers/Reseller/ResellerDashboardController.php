<?php

namespace App\Http\Controllers\Reseller;

use App\Enums\KycStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ResellerCommission;
use App\Models\ResellerProfile;
use App\Models\ResellerWalletTransaction;
use App\Models\ResellerWithdrawal;
use App\Services\ResellerWalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResellerDashboardController extends Controller
{
    public function __construct(
        protected ResellerWalletService $resellerWalletService
    ) {}

    /**
     * Reseller Hub Overview Dashboard.
     */
    public function index(): View
    {
        $user = auth()->user()->load(['profile', 'resellerProfile', 'resellerWallet']);
        $wallet = $this->resellerWalletService->getOrCreateWallet($user);

        // Ensure reseller profile exists
        $resellerProfile = $user->resellerProfile ?? ResellerProfile::create([
            'user_id' => $user->id,
            'referral_code' => 'RES-' . strtoupper(Str::random(6)),
            'kyc_status' => KycStatus::VERIFIED,
            'commission_rate_percent' => 10,
            'approved_at' => now(),
        ]);

        $referralOrdersCount = Order::where('reseller_id', $user->id)->count();
        $recentCommissions = ResellerCommission::where('reseller_id', $user->id)
            ->with('order')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = ResellerWalletTransaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('reseller.dashboard', compact(
            'user',
            'wallet',
            'resellerProfile',
            'referralOrdersCount',
            'recentCommissions',
            'recentTransactions'
        ));
    }

    /**
     * Reseller Commissions List Page.
     */
    public function commissions(Request $request): View
    {
        $user = auth()->user();
        $query = ResellerCommission::where('reseller_id', $user->id)->with('order');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $commissions = $query->latest()->paginate(15)->withQueryString();

        $totalEarned = ResellerCommission::where('reseller_id', $user->id)
            ->whereIn('status', ['available', 'paid'])
            ->sum('commission_amount');

        $totalPending = ResellerCommission::where('reseller_id', $user->id)
            ->where('status', 'pending')
            ->sum('commission_amount');

        return view('reseller.commissions.index', compact('user', 'commissions', 'totalEarned', 'totalPending'));
    }

    /**
     * Reseller Wallet Ledger Page.
     */
    public function wallet(Request $request): View
    {
        $user = auth()->user();
        $wallet = $this->resellerWalletService->getOrCreateWallet($user);

        $transactions = ResellerWalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('reseller.wallet.index', compact('user', 'wallet', 'transactions'));
    }

    /**
     * Reseller Withdrawals Page.
     */
    public function withdrawals(Request $request): View
    {
        $user = auth()->user()->load('resellerProfile');
        $wallet = $this->resellerWalletService->getOrCreateWallet($user);

        $withdrawals = ResellerWithdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('reseller.withdrawals.index', compact('user', 'wallet', 'withdrawals'));
    }

    /**
     * Submit Withdrawal Request.
     */
    public function storeWithdrawal(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->resellerProfile;

        $validated = $request->validate([
            'amount' => 'required|integer|min:' . ResellerWalletService::MINIMUM_WITHDRAWAL_AMOUNT,
            'bank_name' => 'required|string|max:64',
            'bank_account_number' => 'required|string|max:64',
            'bank_account_name' => 'required|string|max:150',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $withdrawal = $this->resellerWalletService->requestWithdrawal(
                reseller: $user,
                amount: (int) $validated['amount'],
                bankInfo: $validated
            );

            return redirect()->route('reseller.withdrawals.index')
                ->with('success', "Pengajuan penarikan dana #{$withdrawal->withdrawal_number} sebesar {$withdrawal->formatted_amount} berhasil dikirim.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Reseller Bank & Profile Settings Page.
     */
    public function profile(): View
    {
        $user = auth()->user()->load(['profile', 'resellerProfile']);
        return view('reseller.profile', compact('user'));
    }

    /**
     * Update Reseller Bank & Profile Details.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'store_name' => 'nullable|string|max:150',
            'referral_code' => 'required|string|max:64|unique:reseller_profiles,referral_code,' . ($user->resellerProfile?->id ?? 'NULL'),
            'bank_name' => 'required|string|max:64',
            'bank_account_number' => 'required|string|max:64',
            'bank_account_name' => 'required|string|max:150',
        ]);

        $validated['referral_code'] = strtoupper(trim($validated['referral_code']));

        $user->resellerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Data profil kemitraan dan rekening payout berhasil disimpan.');
    }
}
