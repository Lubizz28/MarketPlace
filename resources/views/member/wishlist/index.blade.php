@extends('layouts.dashboard')

@section('title', 'Wishlist & Produk Favorit')

@section('content')
<div class="space-y-6">
    <div class="glass-card p-6 sm:p-8 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-cream-700 text-[10px] uppercase tracking-[0.2em] font-bold">Koleksi Favorit</span>
            <h1 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-0.5">Wishlist Anda</h1>
            <p class="text-xs text-charcoal-500 mt-1 font-light">Daftar busana impian yang Anda simpan untuk dibeli nanti.</p>
        </div>
        <a href="{{ route('catalog') }}" class="px-6 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl transition-smooth shrink-0 text-center">
            + Jelajahi Busana Lain
        </a>
    </div>

    <!-- Wishlist Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($wishlists as $item)
            @php $product = $item->product; @endphp
            <div class="glass-card p-4 rounded-3xl space-y-3 flex flex-col justify-between hover:border-cream-400 transition-smooth group">
                <div class="space-y-3">
                    <div class="aspect-3/4 rounded-2xl overflow-hidden bg-cream-100 relative">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-smooth">
                        <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 bg-charcoal-950/80 backdrop-blur-md text-cream-200 text-[9px] font-bold rounded-full">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div>
                        <h4 class="font-display font-bold text-xs text-charcoal-950 line-clamp-2 leading-snug">{{ $product->name }}</h4>
                        <p class="text-xs font-mono font-bold text-charcoal-900 mt-1">Rp {{ number_format($product->getMinPriceFor(auth()->user()->role->value), 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-cream-100">
                    <a href="{{ route('product.show', $product->slug) }}" class="block py-2 text-center bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-smooth">
                        Lihat Produk
                    </a>
                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                        @csrf
                        <button type="submit" class="w-full py-1.5 text-center text-rose-600 hover:text-rose-800 text-[10px] font-medium">
                            Hapus dari Wishlist
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center space-y-4 glass-card rounded-3xl p-10">
                <div class="w-16 h-16 rounded-2xl bg-cream-100 flex items-center justify-center mx-auto text-charcoal-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Wishlist Anda Masih Kosong</h3>
                <p class="text-xs text-charcoal-500 font-light max-w-sm mx-auto">Klik ikon hati pada produk yang Anda sukai untuk menyimpannya di sini.</p>
                <a href="{{ route('catalog') }}" class="inline-block px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-md">
                    Jelajahi Koleksi
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($wishlists->hasPages())
        <div class="pt-4">
            {{ $wishlists->links() }}
        </div>
    @endif
</div>
@endsection
