<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Inventory\RecordInventoryMovementAction;
use App\Enums\CustomerType;
use App\Enums\InventoryMovementType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function __construct(
        protected RecordInventoryMovementAction $recordInventoryMovementAction
    ) {}

    /**
     * Display product catalog list.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'variants.prices'])->latest();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show create product form.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store new product and create initial variants with inventory movements.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'reseller_price' => 'nullable|numeric|min:0',
            'weight_grams' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'sku_prefix' => 'required|string|max:30|unique:products,sku',
            'variant_name' => 'required|string|max:100',
            'initial_stock' => 'required|integer|min:0',
        ]);

        $basePrice = (float) $validated['base_price'];
        $memberPrice = $validated['member_price'] ? (float) $validated['member_price'] : ($basePrice * 0.9);
        $resellerPrice = $validated['reseller_price'] ? (float) $validated['reseller_price'] : ($basePrice * 0.8);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'sku' => strtoupper($validated['sku_prefix']),
            'description' => $validated['description'] ?? '',
            'weight_grams' => $validated['weight_grams'],
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => strtoupper($validated['sku_prefix']) . '-01',
            'name' => $validated['variant_name'],
            'stock' => 0, // will be recorded via action
            'is_active' => true,
        ]);

        $variant->prices()->createMany([
            ['customer_type' => CustomerType::RETAIL, 'price' => $basePrice, 'min_quantity' => 1],
            ['customer_type' => CustomerType::MEMBER, 'price' => $memberPrice, 'min_quantity' => 1],
            ['customer_type' => CustomerType::RESELLER, 'price' => $resellerPrice, 'min_quantity' => 1],
        ]);

        if ($validated['initial_stock'] > 0) {
            $this->recordInventoryMovementAction->execute(
                variant: $variant,
                type: InventoryMovementType::OPENING,
                quantity: $validated['initial_stock'],
                userId: auth()->id(),
                notes: 'Stok awal produk saat pembuatan'
            );
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil ditambahkan ke katalog.");
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product): View
    {
        $product->load(['category', 'variants.prices']);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product details.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'reseller_price' => 'nullable|numeric|min:0',
            'weight_grams' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $product->update([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'weight_grams' => $validated['weight_grams'],
            'description' => $validated['description'] ?? '',
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('base_price')) {
            $basePrice = (float) $validated['base_price'];
            $memberPrice = $validated['member_price'] ? (float) $validated['member_price'] : ($basePrice * 0.9);
            $resellerPrice = $validated['reseller_price'] ? (float) $validated['reseller_price'] : ($basePrice * 0.8);

            foreach ($product->variants as $variant) {
                $variant->prices()->updateOrCreate(
                    ['customer_type' => CustomerType::RETAIL],
                    ['price' => $basePrice, 'min_quantity' => 1]
                );
                $variant->prices()->updateOrCreate(
                    ['customer_type' => CustomerType::MEMBER],
                    ['price' => $memberPrice, 'min_quantity' => 1]
                );
                $variant->prices()->updateOrCreate(
                    ['customer_type' => CustomerType::RESELLER],
                    ['price' => $resellerPrice, 'min_quantity' => 1]
                );
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Informasi produk '{$product->name}' berhasil diperbarui.");
    }

    /**
     * Toggle product active status.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update(['is_active' => !$product->is_active]);

        $statusText = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk '{$product->name}' berhasil {$statusText}.");
    }
}
