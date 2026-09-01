@extends('layouts.app')

@section('title', 'Katalog Busana Muslim & Abaya Syar\'i — Sulastika Jaya')
@section('meta_description', 'Jelajahi katalog lengkap busana muslim syari, abaya elegan, gamis anggun, koko kurta modern, dan pashmina voal dengan standar butik berkelas di Sulastika Jaya.')
@section('meta_keywords', 'katalog busana muslim, gamis syari, abaya modern, pashmina voal, baju koko, sulastika jaya')
@section('canonical_url', request()->url())
@section('og_type', 'website')
@section('og_image', asset('images/icons/icon.svg'))

@section('schema')
@php
    $itemList = [];
    foreach ($products as $idx => $prod) {
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $idx + 1,
            'url' => route('product.show', $prod->slug),
            'name' => $prod->name,
        ];
    }
    $catalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Katalog Busana Muslim & Abaya Syar\'i — Sulastika Jaya',
        'url' => request()->url(),
        'description' => 'Koleksi busana muslim syari premium terkurasi dengan standar jahitan butik berkelas.',
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $itemList,
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($catalogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
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
                            category: @js($prod->category->name),
                            brand: @js($prod->brand?->name ?? 'Sulastika Jaya')
                        },
                    @endforeach
                ], 'Catalog Search & Filter List');
            }
        }
    }">

    <!-- Page Header & Search Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3 pb-4 border-b border-cream-200/90">
        <div>
            <span class="text-emerald-800 text-[10px] uppercase tracking-[0.2em] font-bold">Koleksi Busana</span>
            <h1 class="text-xl sm:text-3xl font-display font-bold text-charcoal-950 mt-0.5">
                @if($searchQuery)
                    Hasil Pencarian: "{{ $searchQuery }}"
                @elseif($currentCategory)
                    Koleksi {{ ucwords(str_replace('-', ' ', $currentCategory)) }}
                @else
                    Semua Koleksi Busana Muslim
                @endif
            </h1>
            <p class="text-xs text-charcoal-500 mt-0.5 font-light">Menampilkan {{ $products->total() }} busana syar'i mahakarya berkelas.</p>
        </div>

        <!-- Sorting & Quick Filter Form -->
        <form method="GET" action="{{ route('catalog') }}" class="flex flex-wrap items-center gap-2">
            @if($searchQuery)
                <input type="hidden" name="q" value="{{ $searchQuery }}">
            @endif
            @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
            @endif
            @if($currentBrand)
                <input type="hidden" name="brand" value="{{ $currentBrand }}">
            @endif

            <label for="sort" class="text-xs text-charcoal-600 font-medium">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white/90 backdrop-blur-md border border-cream-300 rounded-xl py-1.5 px-3 text-xs font-semibold text-charcoal-900 focus:outline-none focus:ring-2 focus:ring-emerald-800 shadow-xs">
                <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="popular" {{ $currentSort === 'popular' ? 'selected' : '' }}>Paling Populer</option>
                <option value="price_low" {{ $currentSort === 'price_low' ? 'selected' : '' }}>Harga: Terendah &rarr; Tertinggi</option>
                <option value="price_high" {{ $currentSort === 'price_high' ? 'selected' : '' }}>Harga: Tertinggi &rarr; Terendah</option>
            </select>
        </form>
    </div>

    <!-- Category Filter Pills Bar -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-none">
        <a href="{{ route('catalog', array_merge(request()->except('category', 'page'))) }}"
            class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-smooth shadow-xs {{ empty($currentCategory) ? 'bg-emerald-950 text-gold-200 border border-gold-400/40' : 'bg-white/80 border border-cream-300 text-charcoal-700 hover:bg-white hover:border-gold-300' }}">
            Semua Kategori
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-smooth shadow-xs {{ $currentCategory === $cat->slug ? 'bg-emerald-950 text-gold-200 border border-gold-400/40' : 'bg-white/80 border border-cream-300 text-charcoal-700 hover:bg-white hover:border-gold-300' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Product Grid (Compact & Professional Proportions for All Devices) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-3 sm:gap-4.5">
        @forelse($products as $product)
            @php
                $user = auth()->user();
                $minPrice = $product->getMinPriceFor($user ? $user->role->value : 'retail');
                $isWishlisted = $user ? $user->wishlists->contains('product_id', $product->id) : false;
            @endphp
            <div class="group glass-card rounded-2xl overflow-hidden flex flex-col justify-between border border-cream-300 hover:border-gold-400 product-card-hover relative">
                <!-- Luxury Image Container with Portrait Aspect -->
                <div class="luxury-image-container aspect-luxury-portrait">
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    
                    <!-- Category Badge -->
                    <span class="absolute top-2 left-2 gold-badge text-[8px] sm:text-[9px] font-bold uppercase tracking-wider rounded-md px-2 py-0.5">
                        {{ $product->category->name }}
                    </span>

                    <!-- Wishlist Quick Toggle -->
                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="absolute top-2 right-2">
                        @csrf
                        <button type="submit" class="w-7 h-7 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-charcoal-700 hover:text-rose-600 shadow-xs transition-smooth border border-cream-200" title="Simpan ke Wishlist">
                            <svg class="w-3.5 h-3.5 {{ $isWishlisted ? 'fill-rose-600 text-rose-600' : 'fill-none' }}" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Product Info (Compact & Clean) -->
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
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('product.show', $product->slug) }}" class="w-7 h-7 rounded-lg bg-emerald-950 hover:bg-emerald-900 text-gold-200 flex items-center justify-center transition-smooth shadow-xs" title="Lihat Detail">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center space-y-3 glass-card rounded-2xl p-8">
                <div class="w-14 h-14 rounded-2xl bg-cream-100 flex items-center justify-center mx-auto text-charcoal-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Tidak Ada Produk yang Cocok</h3>
                <p class="text-xs text-charcoal-500 font-light max-w-sm mx-auto">Coba ubah kata kunci pencarian Anda atau pilih filter kategori yang lain.</p>
                <a href="{{ route('catalog') }}" class="inline-block px-5 py-2 bg-emerald-950 text-gold-200 font-bold rounded-xl text-xs uppercase tracking-wider shadow-sm">
                    Reset Filter &amp; Pencarian
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $products->links() }}
    </div>

</div>
@endsection
