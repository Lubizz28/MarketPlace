@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name . ' — Sulastika Jaya')
@section('meta_description', 'Beli koleksi ' . $category->name . ' eksklusif standar butik syari dengan bahan premium terpilih, harga retail & member rewards di Sulastika Jaya.')
@section('meta_keywords', $category->name . ', busana muslim, gamis syari, abaya, sulastika jaya')
@section('canonical_url', route('category.show', $category->slug))
@section('og_type', 'website')
@section('og_image', asset('images/icons/icon.svg'))

@section('schema')
@php
    $catItemList = [];
    foreach ($products as $idx => $prod) {
        $catItemList[] = [
            '@type' => 'ListItem',
            'position' => $idx + 1,
            'url' => route('product.show', $prod->slug),
            'name' => $prod->name,
        ];
    }
    $categorySchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Katalog',
                        'item' => route('catalog'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $category->name,
                        'item' => route('category.show', $category->slug),
                    ],
                ],
            ],
            [
                '@type' => 'CollectionPage',
                'name' => 'Koleksi ' . $category->name . ' — Sulastika Jaya',
                'url' => route('category.show', $category->slug),
                'description' => 'Koleksi busana kategori ' . $category->name . ' berkualitas butik syari.',
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => $catItemList,
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($categorySchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8 pt-3"
    x-data="{
        init() {
            const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
            if (analytics) {
                analytics.viewItemList([
                    @foreach($products as $prod)
                        {
                            id: '{{ $prod->id }}',
                            sku: '{{ $prod->sku }}',
                            name: @js($prod->name),
                            price: {{ $prod->getMinPriceFor('retail') }},
                            category: @js($category->name),
                            brand: @js($prod->brand?->name ?? 'Sulastika Jaya')
                        },
                    @endforeach
                ], 'Category: {{ $category->name }}');
            }
        }
    }">

    <!-- Category Header Banner in Royal Emerald -->
    <div class="bg-emerald-luxury text-white rounded-2xl sm:rounded-3xl p-6 sm:p-10 relative overflow-hidden border border-gold-400/30 shadow-xl">
        <div class="absolute inset-0 bg-sulastika-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-1.5 max-w-2xl">
            <span class="text-gold-300 text-[10px] uppercase tracking-[0.2em] font-bold">Koleksi Kategori</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-white">{{ $category->name }}</h1>
            <p class="text-xs sm:text-sm text-cream-200/90 font-light leading-relaxed">
                {{ $category->description ?? 'Jelajahi beragam pilihan busana syar\'i berstandar butik dengan bahan berkualitas tinggi dan jahitan presisi di Sulastika Jaya.' }}
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-gold-400/15 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Products Grid (Compact Proportions for All Devices) -->
    <div class="space-y-4 sm:space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-cream-200">
            <p class="text-xs text-charcoal-600 font-medium">Menampilkan {{ $products->total() }} produk</p>
            <a href="{{ route('catalog') }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 hover:underline">&larr; Semua Kategori</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-3 sm:gap-4.5">
            @forelse($products as $product)
                <div class="group glass-card rounded-2xl overflow-hidden flex flex-col justify-between border border-cream-300 hover:border-gold-400 product-card-hover relative">
                    <div class="luxury-image-container aspect-luxury-portrait">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>

                    <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            @if($product->brand)
                                <span class="text-[9px] uppercase tracking-wider text-emerald-800/80 font-bold block">{{ $product->brand->name }}</span>
                            @endif
                            <a href="{{ route('product.show', $product->slug) }}" class="block font-display font-bold text-charcoal-950 text-xs sm:text-[13px] hover:text-emerald-800 transition-colors mt-0.5 line-clamp-2 leading-snug">
                                {{ $product->name }}
                            </a>
                        </div>

                        <div class="pt-1.5 border-t border-cream-100 flex items-center justify-between">
                            <div>
                                <span class="text-[8px] uppercase tracking-wider text-charcoal-400 font-medium block">Mulai dari</span>
                                <p class="text-xs sm:text-sm font-bold text-charcoal-900 font-mono">
                                    Rp {{ number_format($product->getMinPriceFor(auth()->user()?->role?->value ?? 'retail'), 0, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="w-7 h-7 rounded-lg bg-emerald-950 hover:bg-emerald-900 text-gold-200 flex items-center justify-center transition-smooth shadow-xs" title="Lihat Detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-14 text-center space-y-2.5 glass-card rounded-2xl p-8">
                    <h3 class="font-display font-bold text-charcoal-950 text-sm sm:text-base">Belum Ada Produk di Kategori Ini</h3>
                    <p class="text-xs text-charcoal-500 font-light">Koleksi busana baru akan segera hadir.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-3">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
