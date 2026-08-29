<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CouponType;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::query()->withCount('usages');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $coupons = $query->latest()->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:64|unique:coupons,code',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'type' => ['required', Rule::enum(CouponType::class)],
            'amount' => 'required|integer|min:1',
            'min_order_amount' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['min_order_amount'] = (int) ($validated['min_order_amount'] ?? 0);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon promo baru berhasil dibuat.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:64', Rule::unique('coupons', 'code')->ignore($coupon->id)],
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'type' => ['required', Rule::enum(CouponType::class)],
            'amount' => 'required|integer|min:1',
            'min_order_amount' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['min_order_amount'] = (int) ($validated['min_order_amount'] ?? 0);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon promo berhasil diperbarui.');
    }

    public function toggle(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        $status = $coupon->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kupon #{$coupon->code} berhasil {$status}.");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->usages()->exists()) {
            return back()->with('error', 'Kupon tidak dapat dihapus karena sudah memiliki riwayat transaksi penggunaan. Nonaktifkan saja.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon promo berhasil dihapus.');
    }
}
