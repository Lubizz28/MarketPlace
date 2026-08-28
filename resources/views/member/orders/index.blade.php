@extends('layouts.dashboard')

@section('title', 'Riwayat Pesanan Saya')
@section('page-title', 'Riwayat Pesanan')

@section('content')
<div class="space-y-6">

    <!-- Header Box -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-2">
        <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950">Daftar Transaksi &amp; Pesanan</h2>
        <p class="text-xs text-charcoal-500 font-light">Lacak status proses, resi pengiriman kurir, dan faktur resmi seluruh pembelian Anda.</p>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="glass-card p-6 rounded-3xl border border-cream-200/90 space-y-4 hover:border-cream-400 transition-smooth">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-cream-100">
                    <div class="flex items-center space-x-3">
                        <span class="font-mono font-bold text-charcoal-950 text-sm">#{{ $order->order_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $order->status->badgeClasses() }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                    <span class="text-xs text-charcoal-400 font-light font-mono">
                        {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1 text-xs">
                        <p class="text-charcoal-700 font-medium">
                            {{ $order->items->first()?->product_name }}
                            @if($order->items->count() > 1)
                                <span class="text-charcoal-400">+ {{ $order->items->count() - 1 }} produk lainnya</span>
                            @endif
                        </p>
                        <p class="text-charcoal-500 text-[11px]">
                            Kurir: <b class="uppercase">{{ $order->shipment?->courier_code }} {{ $order->shipment?->service_name }}</b>
                            @if($order->shipment?->tracking_number)
                                &bull; Resi: <span class="font-mono text-charcoal-900 font-bold">{{ $order->shipment->tracking_number }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center space-x-4 shrink-0">
                        <div class="text-right">
                            <span class="text-[10px] text-charcoal-400 uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-base font-bold font-mono text-charcoal-950">{{ $order->formatted_grand_total }}</span>
                        </div>
                        <a href="{{ route('orders.show', $order->order_number) }}"
                            class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold uppercase tracking-wider transition-smooth shadow-sm">
                            Detail &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass-card p-12 rounded-3xl text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-cream-100 flex items-center justify-center mx-auto text-charcoal-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs text-charcoal-500 font-light max-w-sm mx-auto">Anda belum memiliki transaksi pesanan. Mulai belanja busana muslim pilihan Anda sekarang.</p>
                <a href="{{ route('catalog') }}" class="inline-block mt-2 px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-md">
                    Mulai Belanja &rarr;
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
