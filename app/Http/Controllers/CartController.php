<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Dedicated full cart page view.
     */
    public function index(): View
    {
        $cartTotals = $this->cartService->getCartTotals(auth()->user());

        return view('storefront.cart', [
            'cartTotals' => $cartTotals,
        ]);
    }

    /**
     * Add item to cart via HTTP or AJAX.
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        try {
            $this->cartService->addItem($validated['variant_id'], $quantity, auth()->user());

            if ($request->wantsJson()) {
                $totals = $this->cartService->getCartTotals(auth()->user());
                return response()->json([
                    'status' => 'success',
                    'message' => 'Produk berhasil ditambahkan ke keranjang belanja.',
                    'total_items' => $totals['total_items'],
                    'formatted_subtotal' => $totals['formatted_subtotal'],
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang belanja.');
        } catch (InvalidArgumentException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update quantity of an item in cart.
     */
    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $this->cartService->updateQuantity($id, $validated['quantity'], auth()->user());

        if ($request->wantsJson()) {
            $totals = $this->cartService->getCartTotals(auth()->user());
            return response()->json([
                'status' => 'success',
                'message' => 'Keranjang berhasil diperbarui.',
                'totals' => $totals,
            ]);
        }

        return back()->with('success', 'Keranjang belanja berhasil diperbarui.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $this->cartService->removeItem($id, auth()->user());

        if ($request->wantsJson()) {
            $totals = $this->cartService->getCartTotals(auth()->user());
            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil dihapus dari keranjang.',
                'totals' => $totals,
            ]);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
