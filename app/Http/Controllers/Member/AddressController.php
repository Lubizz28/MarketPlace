<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->latest()->get();

        return view('member.addresses.index', compact('addresses'));
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $isPrimary = $request->boolean('is_primary') || $user->addresses()->count() === 0;

        DB::transaction(function () use ($user, $request, $isPrimary) {
            if ($isPrimary) {
                $user->addresses()->update(['is_primary' => false]);
            }

            $user->addresses()->create([
                ...$request->validated(),
                'is_primary' => $isPrimary,
            ]);
        });

        return back()->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }

    public function setPrimary(Address $address): RedirectResponse
    {
        Gate::authorize('update', $address);

        DB::transaction(function () use ($address) {
            auth()->user()->addresses()->update(['is_primary' => false]);
            $address->update(['is_primary' => true]);
        });

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        Gate::authorize('delete', $address);

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}
