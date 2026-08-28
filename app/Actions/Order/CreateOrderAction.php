<?php

namespace App\Actions\Order;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Contracts\PaymentGatewayInterface;
use App\Enums\CustomerType;
use App\Enums\InventoryMovementType;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrderAction
{
    public function __construct(
        protected ValidateCartAction $validateCartAction,
        protected RecordInventoryMovementAction $recordInventoryMovementAction,
        protected PricingService $pricingService,
        protected PaymentGatewayInterface $paymentGateway
    ) {}

    /**
     * Create order atomically.
     *
     * @param Collection<int, CartItem> $cartItems
     * @param array{
     *     customer_name: string,
     *     customer_email: string,
     *     customer_phone: string,
     *     notes?: string|null,
     *     shipping_address: array{
     *         recipient_name: string,
     *         phone: string,
     *         province_id?: string|null,
     *         province_name: string,
     *         city_id?: string|null,
     *         city_name: string,
     *         subdistrict_id?: string|null,
     *         subdistrict_name?: string|null,
     *         postal_code: string,
     *         address_line: string,
     *         notes?: string|null
     *     },
     *     shipping_service: array{
     *         courier_code: string,
     *         courier_name: string,
     *         service_name: string,
     *         service_description?: string|null,
     *         etd_days?: string|null,
     *         cost: int
     *     },
     *     payment_method: PaymentMethod|string,
     *     reseller_id?: int|null
     * } $data
     * @param User|null $user
     * @return Order
     */
    public function execute(Collection $cartItems, array $data, ?User $user = null): Order
    {
        return DB::transaction(function () use ($cartItems, $data, $user) {
            // 1. Validate Cart & Stock
            $validated = $this->validateCartAction->execute($cartItems, $user);

            $customerType = $this->pricingService->getCustomerType($user);
            $orderNumber = $this->generateOrderNumber();
            $shippingCost = (int) $data['shipping_service']['cost'];
            $discountAmount = 0; // Coupo/voucher logic will integrate in Phase 5
            $subtotal = $validated['subtotal'];
            $grandTotal = max(0, $subtotal - $discountAmount + $shippingCost);

            // 2. Create Order Header
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user?->id,
                'customer_type' => $customerType,
                'reseller_id' => $data['reseller_id'] ?? null,
                'status' => OrderStatus::PENDING_PAYMENT,
                'payment_status' => PaymentStatus::UNPAID,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'notes' => $data['notes'] ?? null,
                'expires_at' => now()->addHours(24),
            ]);

            // 3. Create Order Items & Deduct Stock Atomic Ledger
            foreach ($validated['items'] as $item) {
                $variant = $item['variant'];

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,
                    'sku' => $variant->sku,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'weight_grams' => $item['weight_grams'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Record Inventory Movement (SALE)
                $this->recordInventoryMovementAction->execute(
                    variant: $variant,
                    type: InventoryMovementType::SALE,
                    quantity: $item['quantity'],
                    notes: "Pesanan #{$order->order_number}",
                    userId: $user?->id,
                    referenceType: 'order',
                    referenceId: $order->id
                );
            }

            // 4. Create Order Address
            $addr = $data['shipping_address'];
            $order->address()->create([
                'recipient_name' => $addr['recipient_name'],
                'phone' => $addr['phone'],
                'province_id' => $addr['province_id'] ?? null,
                'province_name' => $addr['province_name'],
                'city_id' => $addr['city_id'] ?? null,
                'city_name' => $addr['city_name'],
                'subdistrict_id' => $addr['subdistrict_id'] ?? null,
                'subdistrict_name' => $addr['subdistrict_name'] ?? null,
                'postal_code' => $addr['postal_code'],
                'address_line' => $addr['address_line'],
                'notes' => $addr['notes'] ?? null,
            ]);

            // 5. Create Order Shipment
            $ship = $data['shipping_service'];
            $order->shipment()->create([
                'courier_code' => $ship['courier_code'],
                'courier_name' => $ship['courier_name'],
                'service_name' => $ship['service_name'],
                'service_description' => $ship['service_description'] ?? null,
                'etd_days' => $ship['etd_days'] ?? null,
                'shipping_cost' => $shippingCost,
                'weight_grams' => $validated['total_weight'],
                'status' => 'pending',
            ]);

            // 6. Create Initial Payment Record & Gateway Charge
            $paymentMethod = is_string($data['payment_method'])
                ? PaymentMethod::from($data['payment_method'])
                : $data['payment_method'];

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'payment_gateway' => 'midtrans',
                'amount' => $grandTotal,
                'status' => PaymentStatus::PENDING,
                'expired_at' => now()->addHours(24),
            ]);

            // Request Snap / Charge from Payment Gateway
            $chargeResult = $this->paymentGateway->createCharge($order, $payment);

            if ($chargeResult->success) {
                $payment->update([
                    'transaction_id' => $chargeResult->transactionId,
                    'snap_token' => $chargeResult->snapToken,
                    'payment_url' => $chargeResult->redirectUrl,
                    'payment_payload' => $chargeResult->rawResponse,
                ]);

                $payment->transactions()->create([
                    'gateway_reference' => $chargeResult->transactionId,
                    'event_type' => 'charge_created',
                    'payload_json' => $chargeResult->rawResponse,
                    'status' => 'pending',
                ]);
            }

            // 7. Clear Cart Items for this user or session
            if ($user) {
                CartItem::where('user_id', $user->id)->delete();
            } else {
                $sessionId = session('cart_session_id');
                if ($sessionId) {
                    CartItem::where('session_id', $sessionId)->delete();
                }
            }

            return $order->load(['items', 'address', 'shipment', 'payment']);
        });
    }

    protected function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
