<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Contracts\ShippingServiceInterface;
use App\Enums\PaymentMethod;
use App\Models\Address;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected PricingService $pricingService,
        protected ShippingServiceInterface $shippingService,
        protected CreateOrderAction $createOrderAction
    ) {}

    /**
     * Show Checkout Page.
     */
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();
        $cartTotals = $this->cartService->getCartTotals($user);

        if ($cartTotals['cart_items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $savedAddresses = $user ? $user->addresses()->latest()->get() : collect();
        $provinces = $this->shippingService->getProvinces();
        $paymentMethods = PaymentMethod::cases();

        return view('storefront.checkout', [
            'cartTotals' => $cartTotals,
            'savedAddresses' => $savedAddresses,
            'provinces' => $provinces,
            'paymentMethods' => $paymentMethods,
            'user' => $user,
        ]);
    }

    /**
     * AJAX endpoint to fetch cities in a province.
     */
    public function getCities(Request $request): JsonResponse
    {
        $provinceId = $request->input('province_id');
        $cities = $provinceId ? $this->shippingService->getCities($provinceId) : [];

        return response()->json([
            'success' => true,
            'cities' => $cities,
        ]);
    }

    /**
     * AJAX endpoint to calculate shipping rates for destination city.
     */
    public function calculateShipping(Request $request): JsonResponse
    {
        $request->validate([
            'city_id' => 'required|string',
        ]);

        $user = auth()->user();
        $cartTotals = $this->cartService->getCartTotals($user);
        $weightGrams = max(1000, $cartTotals['total_weight_grams']);

        $shippingOptions = $this->shippingService->calculateCost(
            destinationCityId: $request->input('city_id'),
            weightGrams: $weightGrams
        );

        return response()->json([
            'success' => true,
            'options' => array_map(fn ($opt) => $opt->toArray(), $shippingOptions),
            'weight_grams' => $weightGrams,
        ]);
    }

    /**
     * Process and place the Order.
     */
    public function process(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $cartTotals = $this->cartService->getCartTotals($user);

        if ($cartTotals['cart_items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $rules = [
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|max:30',
            'notes' => 'nullable|string|max:500',
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:30',
            'province_id' => 'nullable|string',
            'province_name' => 'required|string|max:100',
            'city_id' => 'nullable|string',
            'city_name' => 'required|string|max:100',
            'subdistrict_name' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address_line' => 'required|string|max:500',
            'courier_code' => 'required|string|max:32',
            'courier_name' => 'required|string|max:100',
            'service_name' => 'required|string|max:64',
            'service_description' => 'nullable|string|max:150',
            'etd_days' => 'nullable|string|max:32',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
        ];

        $validated = $request->validate($rules);

        try {
            $order = $this->createOrderAction->execute(
                cartItems: $cartTotals['cart_items'],
                data: [
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'notes' => $validated['notes'] ?? null,
                    'shipping_address' => [
                        'recipient_name' => $validated['recipient_name'],
                        'phone' => $validated['recipient_phone'],
                        'province_id' => $validated['province_id'] ?? null,
                        'province_name' => $validated['province_name'],
                        'city_id' => $validated['city_id'] ?? null,
                        'city_name' => $validated['city_name'],
                        'subdistrict_name' => $validated['subdistrict_name'] ?? null,
                        'postal_code' => $validated['postal_code'],
                        'address_line' => $validated['address_line'],
                    ],
                    'shipping_service' => [
                        'courier_code' => $validated['courier_code'],
                        'courier_name' => $validated['courier_name'],
                        'service_name' => $validated['service_name'],
                        'service_description' => $validated['service_description'] ?? null,
                        'etd_days' => $validated['etd_days'] ?? null,
                        'cost' => (int) $validated['shipping_cost'],
                    ],
                    'payment_method' => $validated['payment_method'],
                    'reseller_id' => session('referral_reseller_id'),
                ],
                user: $user
            );

            return redirect()->route('orders.show', $order->order_number)
                ->with('success', 'Pesanan Anda berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kendala saat memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
