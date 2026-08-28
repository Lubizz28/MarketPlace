<div>
    <!-- Backdrop & Slide-Over Drawer -->
    <div x-show="$wire.isOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-charcoal-950/60 backdrop-blur-sm transition-opacity" @click="$wire.closeDrawer()"></div>

        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <div class="pointer-events-auto w-screen max-w-md bg-white/95 backdrop-blur-2xl border-l border-cream-200/90 shadow-2xl flex flex-col justify-between">
                
                <!-- Drawer Header -->
                <div class="p-6 border-b border-cream-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-charcoal-950 text-cream-300 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-charcoal-950 text-base">Keranjang Belanja</h2>
                            <p class="text-[11px] text-charcoal-500">{{ $cartTotals['total_items'] }} item terpilih</p>
                        </div>
                    </div>
                    <button type="button" @click="$wire.closeDrawer()" class="p-2 text-charcoal-400 hover:text-charcoal-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Drawer Content / Items List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @forelse($cartTotals['items'] as $item)
                        <div class="glass-card p-4 rounded-2xl flex space-x-3.5 relative">
                            <!-- Thumbnail -->
                            <img src="{{ $item->variant->product->thumbnail_url }}" alt="{{ $item->variant->product->name }}" class="w-16 h-20 object-cover rounded-xl border border-cream-200 shrink-0">

                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-semibold text-charcoal-950 text-xs truncate leading-snug">{{ $item->variant->product->name }}</h4>
                                    <p class="text-[11px] text-charcoal-500 mt-0.5">{{ $item->variant->name }}</p>
                                    <p class="text-xs font-bold text-charcoal-900 mt-1 font-mono">{{ $item->formatted_unit_price }}</p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-cream-100">
                                    <div class="flex items-center space-x-2 bg-cream-100/80 rounded-lg p-0.5 border border-cream-200">
                                        <button type="button" wire:click="decrementQuantity({{ $item->id }}, {{ $item->quantity }})" class="w-6 h-6 rounded flex items-center justify-center text-charcoal-700 hover:bg-white text-xs font-bold">-</button>
                                        <span class="w-6 text-center text-xs font-bold font-mono">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="incrementQuantity({{ $item->id }}, {{ $item->quantity }})" class="w-6 h-6 rounded flex items-center justify-center text-charcoal-700 hover:bg-white text-xs font-bold">+</button>
                                    </div>
                                    <button type="button" wire:click="removeItem({{ $item->id }})" class="text-[11px] text-rose-600 hover:underline">Hapus</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center space-y-3">
                            <div class="w-16 h-16 rounded-2xl bg-cream-100/90 flex items-center justify-center mx-auto text-charcoal-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <h3 class="font-display font-bold text-charcoal-950 text-sm">Keranjang Anda Masih Kosong</h3>
                            <p class="text-xs text-charcoal-500 font-light max-w-xs mx-auto">Jelajahi koleksi busana muslim terbaru dan tambahkan produk pilihan Anda.</p>
                            <a href="{{ route('catalog') }}" @click="$wire.closeDrawer()" class="inline-block mt-2 px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-md">
                                Belanja Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Drawer Footer & Checkout Action -->
                @if(! $cartTotals['is_empty'])
                    <div class="p-6 border-t border-cream-200 bg-cream-50/80 space-y-4">
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between text-charcoal-600">
                                <span>Total Item</span>
                                <span class="font-mono">{{ $cartTotals['total_items'] }} pcs ({{ $cartTotals['formatted_weight_kg'] }})</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-charcoal-950 pt-1 border-t border-cream-200">
                                <span>Subtotal</span>
                                <span class="text-base font-mono">{{ $cartTotals['formatted_subtotal'] }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('cart.index') }}" @click="$wire.closeDrawer()" class="py-3.5 text-center bg-white border border-cream-300 text-charcoal-800 font-bold rounded-2xl text-xs uppercase tracking-wider hover:bg-cream-100 transition-smooth">
                                Lihat Keranjang
                            </a>
                            <a href="{{ route('cart.index') }}" class="py-3.5 text-center bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-wider shadow-lg transition-smooth">
                                Checkout &rarr;
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
