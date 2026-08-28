<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $isOpen = false;

    #[On('open-cart-drawer')]
    public function openDrawer(): void
    {
        $this->isOpen = true;
    }

    #[On('cart-updated')]
    public function onCartUpdated(): void
    {
        // Component automatically re-renders
    }

    public function closeDrawer(): void
    {
        $this->isOpen = false;
    }

    public function incrementQuantity(int $cartItemId, int $currentQuantity): void
    {
        $cartService = app(CartService::class);
        $cartService->updateQuantity($cartItemId, $currentQuantity + 1, auth()->user());
        $this->dispatch('cart-updated');
    }

    public function decrementQuantity(int $cartItemId, int $currentQuantity): void
    {
        $cartService = app(CartService::class);
        $cartService->updateQuantity($cartItemId, $currentQuantity - 1, auth()->user());
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId): void
    {
        $cartService = app(CartService::class);
        $cartService->removeItem($cartItemId, auth()->user());
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cartService = app(CartService::class);
        $cartTotals = $cartService->getCartTotals(auth()->user());

        return view('livewire.cart.cart-drawer', [
            'cartTotals' => $cartTotals,
        ]);
    }
}
