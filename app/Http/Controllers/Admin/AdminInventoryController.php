<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Enums\InventoryMovementType;
use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    public function __construct(
        protected RecordInventoryMovementAction $recordInventoryMovementAction
    ) {}

    /**
     * Display stock inventory matrix across all variants.
     */
    public function index(Request $request): View
    {
        $query = ProductVariant::with(['product.category'])->latest();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->input('filter') === 'low_stock') {
            $query->where('stock', '<=', 5)->where('stock', '>', 0);
        } elseif ($request->input('filter') === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        $variants = $query->paginate(15)->withQueryString();

        $stats = [
            'total_variants' => ProductVariant::count(),
            'low_stock_count' => ProductVariant::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock_count' => ProductVariant::where('stock', '<=', 0)->count(),
            'total_units' => (int) ProductVariant::sum('stock'),
        ];

        return view('admin.inventory.index', compact('variants', 'stats'));
    }

    /**
     * Perform atomic stock adjustment on a variant.
     */
    public function adjustStock(Request $request, ProductVariant $variant): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:restock,adjustment,return,sale',
            'quantity' => 'required|integer',
            'notes' => 'required|string|max:255',
        ]);

        $movementType = InventoryMovementType::from($validated['type']);
        $quantity = (int) $validated['quantity'];

        try {
            $this->recordInventoryMovementAction->execute(
                variant: $variant,
                type: $movementType,
                quantity: $quantity,
                userId: auth()->id(),
                notes: $validated['notes'],
                referenceType: 'manual_adjustment'
            );

            return back()->with('success', "Stok untuk varian {$variant->sku} berhasil disesuaikan.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View audit ledger history of stock movements for a specific variant.
     */
    public function movements(ProductVariant $variant): View
    {
        $variant->load(['product.category']);
        $movements = InventoryMovement::where('product_variant_id', $variant->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.inventory.movements', compact('variant', 'movements'));
    }
}
