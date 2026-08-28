<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the member's wishlist.
     */
    public function index(): View
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product.category', 'product.primaryImage', 'product.variants.prices'])
            ->latest()
            ->paginate(12);

        return view('member.wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle wishlist item for authenticated member.
     */
    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'unauthenticated',
                    'message' => 'Silakan masuk terlebih dahulu untuk menyimpan wishlist.',
                    'redirect' => route('login'),
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan masuk untuk menyimpan produk ke Wishlist.');
        }

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $added = false;
            $message = 'Produk berhasil dihapus dari Wishlist Anda.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $added = true;
            $message = 'Produk berhasil ditambahkan ke Wishlist Anda.';
        }

        $count = Wishlist::where('user_id', $user->id)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'added' => $added,
                'count' => $count,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
