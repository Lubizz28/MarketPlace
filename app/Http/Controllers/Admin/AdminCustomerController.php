<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCustomerController extends Controller
{
    /**
     * Display list of registered customers and members.
     */
    public function index(Request $request): View
    {
        $query = User::with(['resellerProfile', 'pointTransactions'])->latest();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total_users' => User::count(),
            'total_members' => User::where('role', UserRole::MEMBER)->count(),
            'total_resellers' => User::where('role', UserRole::RESELLER)->count(),
            'banned_count' => User::where('status', UserStatus::BANNED)->count(),
        ];

        return view('admin.customers.index', compact('users', 'stats'));
    }

    /**
     * 360-degree Customer CRM Profile.
     */
    public function show(User $customer): View
    {
        $customer->load(['addresses', 'resellerProfile', 'resellerWallet', 'pointTransactions']);

        $orders = $customer->orders()->with(['items', 'shipment', 'payment'])->latest()->paginate(10);

        $lifetimeSpend = $customer->orders()
            ->whereIn('status', [OrderStatus::PAID, OrderStatus::PROCESSING, OrderStatus::SHIPPED, OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->sum('grand_total');

        $totalOrdersCount = $customer->orders()->count();

        return view('admin.customers.show', compact('customer', 'orders', 'lifetimeSpend', 'totalOrdersCount'));
    }

    /**
     * Ban or activate a user account.
     */
    public function toggleStatus(User $customer): RedirectResponse
    {
        if ($customer->isAdmin()) {
            return back()->with('error', 'Akun Administrator tidak dapat dinonaktifkan.');
        }

        $newStatus = $customer->status === UserStatus::ACTIVE ? UserStatus::BANNED : UserStatus::ACTIVE;
        $customer->update(['status' => $newStatus]);

        $actionText = $newStatus === UserStatus::ACTIVE ? 'diaktifkan kembali' : 'dinonaktifkan (banned)';

        return back()->with('success', "Akun pengguna {$customer->name} berhasil {$actionText}.");
    }
}
