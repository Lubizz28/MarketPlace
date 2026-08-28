@extends('layouts.app')

@section('title', 'Katalog Busana Muslim Eksklusif')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 pt-4">

    <!-- Page Header & Search Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-cream-200/90">
        <div>
            <span class="text-cream-700 text-[10px] uppercase tracking-[0.25em] font-bold">Koleksi Busana</span>
            <h1 class="text-2xl sm:text-4xl font-display font-bold text-charcoal-950 mt-1">
                @if($searchQuery)
                    Hasil Pencarian: "{{ $searchQuery }}"
                @elseif($currentCategory)
                    Koleksi {{ ucwords(str_replace('-', ' ', $currentCategory)) }}
                @else
                    Semua Koleksi Busana Muslim
                @endif
            </h1>
            <p class="text-xs text-charcoal-500 mt-1 font-light">Menampilkan {{ $products->total() }} busana syar'i mahakarya berkelas.</p>
        </div>

        <!-- Sorting & Quick Filter Form -->
        <form method="GET" action="{{ route('catalog') }}" class="flex flex-wrap items-center gap-3">
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
            <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white/80 backdrop-blur-md border border-cream-300 rounded-2xl py-2 px-4 text-xs font-semibold text-charcoal-900 focus:outline-none focus:ring-2 focus:ring-charcoal-800 shadow-xs">
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
            class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-smooth shadow-xs {{ empty($currentCategory) ? 'bg-charcoal-950 text-cream-200' : 'bg-white/70 border border-cream-300 text-charcoal-700 hover:bg-white hover:border-cream-400' }}">
            Semua Kategori
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-smooth shadow-xs {{ $currentCategory === $cat->slug ? 'bg-charcoal-950 text-cream-200' : 'bg-white/70 border border-cream-300 text-charcoal-700 hover:bg-white hover:border-cream-400' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($products as $product)
            @php
                $user = auth()->user();
                $minPrice = $product->getMinPriceFor($user ? $user->role->value : 'retail');
                $isWishlisted = $user ? $user->wishlists->contains('product_id', $product->id) : false;
            @endphp
            <div class="group glass-card rounded-3xl overflow-hidden flex flex-col justify-between hover:border-cream-400 hover:shadow-xl transition-smooth relative">
                <!-- Thumbnail & Badges -->
                <div class="relative aspect-3/4 overflow-hidden bg-cream-100/60">
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-smooth">
                    
                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 px-3 py-1 bg-charcoal-950/80 backdrop-blur-md text-cream-200 text-[9px] font-bold uppercase tracking-wider rounded-full border border-cream-400/30">
                        {{ $product->category->name }}
                    </span>

                    <!-- Wishlist Quick Toggle -->
                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="absolute top-3 right-3">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-full bg-white/85 backdrop-blur-md flex items-center justify-center text-charcoal-700 hover:text-rose-600 shadow-sm transition-smooth border border-cream-200" title="Simpan ke Wishlist">
                            <svg class="w-4 h-4 {{ $isWishlisted ? 'fill-rose-600 text-rose-600' : 'fill-none' }}" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Product Info -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        @if($product->brand)
                            <span class="text-[10px] uppercase tracking-wider text-charcoal-400 font-bold block">{{ $product->brand->name }}</span>
                        @endif
                        <a href="{{ route('product.show', $product->slug) }}" class="block font-display font-bold text-charcoal-950 text-sm hover:text-cream-800 transition-colors mt-0.5 line-clamp-2 leading-snug">
                            {{ $product->name }}
                        </a>
                    </div>

                    <div class="pt-2 border-t border-cream-100 flex items-center justify-between">
                        <div>
                            <span class="text-[9px] uppercase tracking-wider text-charcoal-400 font-medium block">Mulai dari</span>
                            <p class="text-sm sm:text-base font-bold text-charcoal-900 font-mono">
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('product.show', $product->slug) }}" class="w-8 h-8 rounded-xl bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 flex items-center justify-center transition-smooth shadow-xs" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center space-y-4 glass-card rounded-3xl p-10">
                <div class="w-16 h-16 rounded-2xl bg-cream-100 flex items-center justify-center mx-auto text-charcoal-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Tidak Ada Produk yang Cocok</h3>
                <p class="text-xs text-charcoal-500 font-light max-w-sm mx-auto">Coba ubah kata kunci pencarian Anda atau pilih filter kategori yang lain.</p>
                <a href="{{ route('catalog') }}" class="inline-block px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-md">
                    Reset Filter &amp; Pencarian
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $products->links() }}
    </div>

</div>
@endsection
