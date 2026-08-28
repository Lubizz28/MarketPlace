<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class CartService
{
    public function __construct(
        protected PricingService $pricingService
    ) {}

    public function getSessionId(): string
    {
        if (! Session::has('cart_session_id')) {
            Session::put('cart_session_id', 'sess_' . bin2hex(random_bytes(16)));
        }
        return Session::get('cart_session_id');
    }

    /**
     * Get items in cart with eager loaded variants, products, and images.
     */
    public function getCartItems(?User $user = null): Collection
    {
        $user = $user ?? auth()->user();
        $query = CartItem::with(['variant.product.images', 'variant.prices']);

        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', $this->getSessionId())->whereNull('user_id');
        }

        $items = $query->get();

        // Calculate line total and unit price on the fly server-side
        return $items->map(function (CartItem $item) use ($user) {
            $unitPrice = $this->pricingService->getVariantPrice($item->variant, $user);
            $lineTotal = $unitPrice * $item->quantity;

            $item->unit_price = $unitPrice;
            $item->formatted_unit_price = $this->pricingService->formatRupiah($unitPrice);
            $item->line_total = $lineTotal;
            $item->formatted_line_total = $this->pricingService->formatRupiah($lineTotal);
            $item->is_in_stock = $item->variant->stock >= $item->quantity;
            $item->available_stock = $item->variant->stock;

            return $item;
        });
    }

    /**
     * Add variant item to cart.
     */
    public function addItem(int $variantId, int $quantity = 1, ?User $user = null): CartItem
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        if (! $variant->is_active || ! $variant->product->is_active) {
            throw new InvalidArgumentException("Produk ini saat ini tidak aktif.");
        }

        if ($variant->stock < 1) {
            throw new InvalidArgumentException("Maaf, stok untuk varian ini sedang habis.");
        }

        $sessionId = $user ? null : $this->getSessionId();

        $cartItem = CartItem::where('product_variant_id', $variantId)
            ->when($user, fn ($q) => $q->where('user_id', $user->id))
            ->when(! $user, fn ($q) => $q->where('session_id', $sessionId)->whereNull('user_id'))
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $variant->stock) {
                $newQuantity = $variant->stock;
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $initialQuantity = min($quantity, $variant->stock);
            $cartItem = CartItem::create([
                'user_id' => $user?->id,
                'session_id' => $sessionId,
                'product_variant_id' => $variantId,
                'quantity' => $initialQuantity,
            ]);
        }

        return $cartItem;
    }

    /**
     * Update quantity of an item in cart.
     */
    public function updateQuantity(int $cartItemId, int $quantity, ?User $user = null): ?CartItem
    {
        $cartItem = CartItem::with('variant')->find($cartItemId);

        if (! $cartItem) {
            return null;
        }

        // Verify ownership
        if ($user && $cartItem->user_id !== $user->id) {
            return null;
        }
        if (! $user && $cartItem->session_id !== $this->getSessionId()) {
            return null;
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return null;
        }

        // Limit by stock
        $maxStock = $cartItem->variant->stock;
        $adjustedQuantity = min($quantity, $maxStock);

        $cartItem->update(['quantity' => $adjustedQuantity]);

        return $cartItem;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $cartItemId, ?User $user = null): bool
    {
        $cartItem = CartItem::find($cartItemId);
        if (! $cartItem) {
            return false;
        }

        if ($user && $cartItem->user_id !== $user->id) {
            return false;
        }
        if (! $user && $cartItem->session_id !== $this->getSessionId()) {
            return false;
        }

        return (bool) $cartItem->delete();
    }

    /**
     * Calculate summary of cart items.
     */
    public function getCartTotals(?User $user = null): array
    {
        $items = $this->getCartItems($user);

        $subtotal = $items->sum('line_total');
        $totalItems = $items->sum('quantity');
        $totalWeightGrams = $items->sum(fn ($item) => ($item->variant->product->weight_grams ?? 500) * $item->quantity);
        $allInStock = $items->every(fn ($item) => $item->is_in_stock);

        return [
            'items' => $items,
            'cart_items' => $items,
            'subtotal' => $subtotal,
            'formatted_subtotal' => $this->pricingService->formatRupiah($subtotal),
            'total_items' => $totalItems,
            'total_weight_grams' => $totalWeightGrams,
            'formatted_weight_kg' => round($totalWeightGrams / 1000, 2) . ' kg',
            'all_in_stock' => $allInStock,
            'is_empty' => $items->isEmpty(),
        ];
    }

    /**
     * Merge guest cart items into authenticated user's cart upon login.
     */
    public function migrateGuestCartToUser(string $sessionId, User $user): void
    {
        $guestItems = CartItem::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();

        foreach ($guestItems as $guestItem) {
            $userItem = CartItem::where('user_id', $user->id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($userItem) {
                $userItem->update([
                    'quantity' => $userItem->quantity + $guestItem->quantity,
                ]);
                $guestItem->delete();
            } else {
                $guestItem->update([
                    'user_id' => $user->id,
                    'session_id' => null,
                ]);
            }
        }
    }
}
