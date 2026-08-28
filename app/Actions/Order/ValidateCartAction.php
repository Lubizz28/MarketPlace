<?php

namespace App\Actions\Order;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ValidateCartAction
{
    public function __construct(
        protected PricingService $pricingService
    ) {}

    /**
     * Validate cart items, stock availability, and compute totals.
     *
     * @param Collection<int, CartItem> $cartItems
     * @param User|null $user
     * @return array{
     *     items: array<int, array{variant: ProductVariant, quantity: int, price: int, subtotal: int, weight_grams: int}>,
     *     subtotal: int,
     *     total_weight: int,
     *     total_quantity: int
     * }
     * @throws ValidationException
     */
    public function execute(Collection $cartItems, ?User $user = null): array
    {
        if ($cartItems->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.',
            ]);
        }

        $validatedItems = [];
        $totalSubtotal = 0;
        $totalWeight = 0;
        $totalQuantity = 0;

        foreach ($cartItems as $item) {
            /** @var ProductVariant|null $variant */
            $variant = ProductVariant::with(['product', 'prices'])->lockForUpdate()->find($item->product_variant_id);

            if (!$variant || !$variant->is_active || !$variant->product || !$variant->product->is_active) {
                throw ValidationException::withMessages([
                    'cart' => "Produk '{$item->variant?->product?->name}' sudah tidak tersedia.",
                ]);
            }

            if ($item->quantity > $variant->stock) {
                throw ValidationException::withMessages([
                    'cart' => "Stok untuk varian '{$variant->product->name} - {$variant->name}' tidak mencukupi (sisa: {$variant->stock} pcs).",
                ]);
            }

            $price = $this->pricingService->getVariantPrice($variant, $user);
            $itemSubtotal = $price * $item->quantity;
            $itemWeight = ($variant->weight_grams ?: $variant->product->weight_grams ?: 200) * $item->quantity;

            $validatedItems[] = [
                'variant' => $variant,
                'quantity' => $item->quantity,
                'price' => $price,
                'subtotal' => $itemSubtotal,
                'weight_grams' => $itemWeight,
            ];

            $totalSubtotal += $itemSubtotal;
            $totalWeight += $itemWeight;
            $totalQuantity += $item->quantity;
        }

        return [
            'items' => $validatedItems,
            'subtotal' => $totalSubtotal,
            'total_weight' => max(1000, $totalWeight), // minimum 1 kg for courier computation
            'total_quantity' => $totalQuantity,
        ];
    }
}
