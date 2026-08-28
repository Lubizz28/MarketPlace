<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function __construct(
        protected PricingService $pricingService
    ) {}

    /**
     * Homepage with featured collections and categories.
     */
    public function home(): View
    {
        $categories = Category::roots()->active()->withCount('products')->get();
        $brands = Brand::active()->take(6)->get();

        $featuredProducts = Product::featured()
            ->with(['category', 'brand', 'primaryImage', 'variants.prices'])
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::active()
            ->with(['category', 'brand', 'primaryImage', 'variants.prices'])
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('categories', 'brands', 'featuredProducts', 'newArrivals'));
    }

    /**
     * Catalog listing with search, filters, and sorting.
     */
    public function catalog(Request $request): View
    {
        $categories = Category::roots()->active()->with('children')->get();
        $brands = Brand::active()->get();

        $query = Product::active()
            ->with(['category', 'brand', 'primaryImage', 'variants.prices']);

        // Search query
        if ($request->filled('q')) {
            $query->search($request->input('q'));
        }

        // Category filter
        if ($request->filled('category')) {
            $query->filterByCategory($request->input('category'));
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->filterByBrand($request->input('brand'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        $query->sortedBy($sort);

        $products = $query->paginate(12)->withQueryString();

        return view('storefront.catalog', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentCategory' => $request->input('category'),
            'currentBrand' => $request->input('brand'),
            'currentSort' => $sort,
            'searchQuery' => $request->input('q'),
        ]);
    }

    /**
     * Category specific listing.
     */
    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $products = Product::active()
            ->filterByCategory($slug)
            ->with(['category', 'brand', 'primaryImage', 'variants.prices'])
            ->latest()
            ->paginate(12);

        $subcategories = $category->children()->active()->get();

        return view('storefront.category', compact('category', 'products', 'subcategories'));
    }

    /**
     * Product details view with variants, pricing tier, and related items.
     */
    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'category',
                'brand',
                'images',
                'variants' => fn ($q) => $q->where('is_active', true)->with('prices'),
            ])
            ->firstOrFail();

        // Increment view count asynchronously
        $product->increment('view_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand', 'primaryImage', 'variants.prices'])
            ->take(4)
            ->get();

        $user = auth()->user();
        $customerType = $this->pricingService->getCustomerType($user);
        $isWishlisted = $user ? $user->wishlists()->where('product_id', $product->id)->exists() : false;

        return view('storefront.show', compact('product', 'relatedProducts', 'customerType', 'isWishlisted'));
    }
}
