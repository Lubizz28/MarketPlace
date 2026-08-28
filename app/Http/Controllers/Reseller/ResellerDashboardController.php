<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ResellerDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load(['profile']);

        return view('reseller.dashboard', compact('user'));
    }
}
