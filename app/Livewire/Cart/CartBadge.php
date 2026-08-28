<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartBadge extends Component
{
    public int $count = 0;

    public function mount(CartService $cartService): void
    {
        $this->updateCount($cartService);
    }

    #[On('cart-updated')]
    public function refreshCount(): void
    {
        $cartService = app(CartService::class);
        $this->updateCount($cartService);
    }

    protected function updateCount(CartService $cartService): void
    {
        $totals = $cartService->getCartTotals(auth()->user());
        $this->count = $totals['total_items'] ?? 0;
    }

    public function render()
    {
        return view('livewire.cart.cart-badge');
    }
}
