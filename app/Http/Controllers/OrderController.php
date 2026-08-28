<?php

namespace App\Http\Controllers;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Enums\InventoryMovementType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected RecordInventoryMovementAction $recordInventoryMovementAction
    ) {}

    /**
     * Display order tracking & payment page.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.variant.product', 'address', 'shipment', 'payment.transactions'])
            ->firstOrFail();

        // Authorization check: if authenticated and order belongs to another user
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('storefront.orders.show', compact('order'));
    }

    /**
     * Member order history.
     */
    public function memberOrders(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items', 'payment', 'shipment'])
            ->latest()
            ->paginate(10);

        return view('member.orders.index', compact('orders'));
    }

    /**
     * Cancel an unpaid pending order.
     */
    public function cancel(string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.variant', 'payment'])
            ->firstOrFail();

        // Authorization check
        if (auth()->check() && $order->user_id && $order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        if (!$order->status->canBeCancelled()) {
            return redirect()->back()->with('error', 'Pesanan ini sudah tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => OrderStatus::CANCELLED,
            'payment_status' => PaymentStatus::EXPIRED,
            'cancelled_at' => now(),
        ]);

        if ($order->payment) {
            $order->payment->update(['status' => PaymentStatus::EXPIRED]);
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->variant) {
                $this->recordInventoryMovementAction->execute(
                    variant: $item->variant,
                    type: InventoryMovementType::RETURN,
                    quantity: $item->quantity,
                    notes: "Pembatalan pesanan #{$order->order_number}",
                    userId: auth()->id(),
                    referenceType: 'order',
                    referenceId: $order->id
                );
            }
        }

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
    }
}
