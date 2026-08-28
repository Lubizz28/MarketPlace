<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Order\FulfillOrderShipmentAction;
use App\Actions\Order\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected UpdateOrderStatusAction $updateOrderStatusAction,
        protected FulfillOrderShipmentAction $fulfillOrderShipmentAction
    ) {}

    /**
     * Admin list all orders with filters & statistics.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['items', 'payment', 'shipment'])->latest();

        // Filter by Order Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by Payment Status
        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        // Search by order number, customer name, email, phone
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Statistics counters
        $counts = [
            'all' => Order::count(),
            'pending_payment' => Order::where('status', OrderStatus::PENDING_PAYMENT)->count(),
            'paid' => Order::where('status', OrderStatus::PAID)->count(),
            'processing' => Order::where('status', OrderStatus::PROCESSING)->count(),
            'shipped' => Order::where('status', OrderStatus::SHIPPED)->count(),
            'completed' => Order::where('status', OrderStatus::COMPLETED)->count(),
            'cancelled' => Order::where('status', OrderStatus::CANCELLED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    /**
     * Admin view single order details, shipment workstation & transaction logs.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.variant.product', 'address', 'shipment', 'payment.transactions' => function ($q) {
                $q->latest();
            }])
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status from admin.
     */
    public function updateStatus(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_column(OrderStatus::cases(), 'value')),
            'reason' => 'nullable|string|max:255',
        ]);

        $targetStatus = OrderStatus::from($request->input('status'));

        try {
            $this->updateOrderStatusAction->execute(
                order: $order,
                targetStatus: $targetStatus,
                actor: auth()->user(),
                reason: $request->input('reason')
            );

            return redirect()->route('admin.orders.show', $order->order_number)
                ->with('success', "Status pesanan #{$order->order_number} berhasil diperbarui menjadi {$targetStatus->label()}.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Fulfill shipment with tracking waybill number.
     */
    public function fulfillShipment(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            $this->fulfillOrderShipmentAction->execute(
                order: $order,
                trackingNumber: $request->input('tracking_number'),
                notes: $request->input('notes'),
                actor: auth()->user()
            );

            return redirect()->route('admin.orders.show', $order->order_number)
                ->with('success', "Pesanan #{$order->order_number} berhasil diproses pengiriman dengan nomor resi {$request->input('tracking_number')}.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
