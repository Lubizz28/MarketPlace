<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentTransactionController extends Controller
{
    /**
     * Display audit ledger of payment gateway webhook events and transactions.
     */
    public function index(Request $request): View
    {
        $query = PaymentTransaction::with(['payment.order'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('gateway_reference', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%")
                  ->orWhereHas('payment.order', function ($sub) use ($search) {
                      $sub->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('transactions'));
    }
}
