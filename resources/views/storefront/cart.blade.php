@extends('layouts.app')

@section('title', 'Keranjang Belanja Eksklusif')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 pt-4">

    <!-- Cart Header -->
    <div class="flex items-center justify-between pb-6 border-b border-cream-200/90">
        <div>
            <span class="text-cream-700 text-[10px] uppercase tracking-[0.25em] font-bold">Ringkasan Belanja</span>
            <h1 class="text-2xl sm:text-4xl font-display font-bold text-charcoal-950 mt-1">Keranjang Belanja</h1>
            <p class="text-xs text-charcoal-500 mt-1 font-light">{{ $cartTotals['total_items'] }} busana siap diproses.</p>
        </div>
        <a href="{{ route('catalog') }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 hover:underline">
            &larr; Lanjut Belanja
        </a>
    </div>

    @if($cartTotals['is_empty'])
        <!-- Empty State -->
        <div class="glass-card rounded-3xl p-16 text-center space-y-4 max-w-md mx-auto">
            <div class="w-20 h-20 rounded-3xl bg-cream-100/80 flex items-center justify-center mx-auto text-charcoal-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <h3 class="font-display font-bold text-charcoal-950 text-lg">Keranjang Belanja Anda Kosong</h3>
            <p class="text-xs text-charcoal-500 font-light">Belum ada busana yang ditambahkan ke keranjang belanja Anda.</p>
            <a href="{{ route('catalog') }}" class="inline-block px-8 py-3.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl transition-smooth">
                Mulai Belanja Sekarang
            </a>
        </div>
    @else
        <!-- Cart Grid: Items on Left (8 Cols), Order Summary on Right (4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Items Table/Card List -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($cartTotals['items'] as $item)
                    <div class="glass-card p-5 sm:p-6 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 border border-cream-200/90 shadow-sm">
                        <!-- Product Info -->
                        <div class="flex items-center space-x-4 min-w-0">
                            <img src="{{ $item->variant->product->thumbnail_url }}" alt="{{ $item->variant->product->name }}" class="w-20 h-24 object-cover rounded-2xl border border-cream-200 shrink-0">
                            <div class="space-y-1 min-w-0">
                                <span class="text-[9px] uppercase tracking-wider text-charcoal-400 font-bold block">{{ $item->variant->product->category->name }}</span>
                                <h4 class="font-display font-bold text-charcoal-950 text-sm truncate">{{ $item->variant->product->name }}</h4>
                                <p class="text-xs text-charcoal-500 font-medium">Varian: {{ $item->variant->name }}</p>
                                <p class="text-xs font-mono font-bold text-charcoal-900 pt-0.5">{{ $item->formatted_unit_price }} / pcs</p>
                            </div>
                        </div>

                        <!-- Stepper & Subtotal -->
                        <div class="flex items-center justify-between sm:justify-end sm:space-x-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-cream-100">
                            <!-- Stepper Form -->
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center space-x-2 bg-cream-100/90 rounded-2xl p-1 border border-cream-300">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="w-7 h-7 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-white text-xs font-bold transition-smooth">-</button>
                                <span class="w-8 text-center text-xs font-bold font-mono">{{ $item->quantity }}</span>
                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" :disabled="{{ $item->quantity >= $item->available_stock ? 'true' : 'false' }}" class="w-7 h-7 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-white text-xs font-bold transition-smooth disabled:opacity-30">+</button>
                            </form>

                            <!-- Line Total -->
                            <div class="text-right min-w-28">
                                <span class="text-sm sm:text-base font-bold font-mono text-charcoal-950 block">{{ $item->formatted_line_total }}</span>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-rose-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Summary Box -->
            <div class="lg:col-span-4 glass-card p-6 sm:p-8 rounded-3xl space-y-6 border-2 border-cream-300 shadow-xl sticky top-28">
                <h3 class="font-display font-bold text-charcoal-950 text-base pb-3 border-b border-cream-200">Ringkasan Pesanan</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-charcoal-600">
                        <span>Total Kuantitas</span>
                        <span class="font-mono font-medium">{{ $cartTotals['total_items'] }} item</span>
                    </div>
                    <div class="flex justify-between text-charcoal-600">
                        <span>Estimasi Total Berat</span>
                        <span class="font-mono font-medium">{{ $cartTotals['formatted_weight_kg'] }}</span>
                    </div>
                    <div class="flex justify-between text-charcoal-600">
                        <span>Ongkos Kirim</span>
                        <span class="text-charcoal-400 font-light italic">Dihitung di langkah checkout</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-charcoal-950 pt-3 border-t border-cream-200">
                        <span>Subtotal Belanja</span>
                        <span class="text-lg font-mono text-charcoal-950">{{ $cartTotals['formatted_subtotal'] }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="alert('Fitur Checkout & Integrasi Pembayaran Otomatis akan diproses pada Phase 4!');"
                        class="w-full py-4 px-6 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl shadow-xl border border-cream-400/30 hover:border-cream-300 transition-smooth text-xs uppercase tracking-widest flex items-center justify-center space-x-2">
                        <span>Lanjut ke Pembayaran &rarr;</span>
                    </button>
                </div>

                <div class="pt-2 text-[11px] text-charcoal-500 font-light space-y-1.5 border-t border-cream-100">
                    <p class="flex items-center space-x-1.5 text-cream-800 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Transaksi Terenkripsi &amp; Aman</span>
                    </p>
                    <p>&bull; Layanan Pengiriman Express se-Indonesia.</p>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
