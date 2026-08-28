@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name . ' — MedinaStyle')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 pt-4">

    <!-- Category Header Banner -->
    <div class="bg-charcoal-luxury text-white rounded-3xl p-8 sm:p-12 relative overflow-hidden border border-cream-400/20 shadow-xl">
        <div class="absolute inset-0 bg-cream-pattern opacity-20 pointer-events-none"></div>
        <div class="relative z-10 space-y-2 max-w-2xl">
            <span class="text-cream-300 text-[10px] uppercase tracking-[0.25em] font-bold">Koleksi Kategori</span>
            <h1 class="text-3xl sm:text-4xl font-display font-bold text-white">{{ $category->name }}</h1>
            <p class="text-xs sm:text-sm text-cream-200/80 font-light leading-relaxed">
                {{ $category->description ?? 'Jelajahi beragam pilihan busana syar\'i berstandar butik dengan bahan berkualitas tinggi dan jahitan presisi.' }}
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Products Grid -->
    <div class="space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-cream-200">
            <p class="text-xs text-charcoal-600 font-medium">Menampilkan {{ $products->total() }} produk</p>
            <a href="{{ route('catalog') }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 hover:underline">&larr; Semua Kategori</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($products as $product)
                <div class="group glass-card rounded-3xl overflow-hidden flex flex-col justify-between hover:border-cream-400 hover:shadow-xl transition-smooth relative">
                    <div class="relative aspect-3/4 overflow-hidden bg-cream-100/60">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-smooth">
                    </div>

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
                                    Rp {{ number_format($product->getMinPriceFor(auth()->user()?->role?->value ?? 'retail'), 0, ',', '.') }}
                                </p>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="w-8 h-8 rounded-xl bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 flex items-center justify-center transition-smooth shadow-xs" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center space-y-3 glass-card rounded-3xl p-10">
                    <h3 class="font-display font-bold text-charcoal-950 text-base">Belum Ada Produk di Kategori Ini</h3>
                    <p class="text-xs text-charcoal-500 font-light">Koleksi baru akan segera hadir.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
