<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'total_members' => User::where('role', UserRole::MEMBER)->count(),
            'total_resellers' => User::where('role', UserRole::RESELLER)->count(),
            'total_admins' => User::where('role', UserRole::ADMIN)->count(),
        ];

        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'latestUsers'));
    }

    public function users(): View
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }
}
