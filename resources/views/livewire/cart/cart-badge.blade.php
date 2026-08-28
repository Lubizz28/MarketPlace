<a href="{{ route('cart.index') }}" class="relative p-2.5 text-charcoal-700 hover:text-charcoal-950 hover:bg-cream-200/60 rounded-full transition-smooth group" aria-label="Keranjang Belanja">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
    </svg>
    @if($count > 0)
        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-charcoal-950 text-cream-200 text-[10px] font-bold flex items-center justify-center border border-cream-300 shadow-xs animate-scale">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</a>
