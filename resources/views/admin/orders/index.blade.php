@extends('layouts.dashboard')

@section('title', 'Kelola Pesanan — Admin Panel')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Stats -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950">Manajemen Pesanan &amp; Lifecycle</h2>
                <p class="text-xs text-charcoal-500 font-light mt-0.5">Kelola verifikasi pembayaran, proses penyiapan produk, dan pengiriman resi kurir.</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2.5 bg-white/80 hover:bg-cream-100 border border-cream-300 text-charcoal-800 rounded-2xl text-xs font-bold transition-smooth shadow-xs">
                    Audit Webhook Pembayaran &rarr;
                </a>
            </div>
        </div>

        <!-- Status Counters Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-2">
            <a href="{{ route('admin.orders.index') }}" class="p-3.5 rounded-2xl border transition-smooth {{ !request('status') ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-white/60 text-charcoal-700 border-cream-200 hover:border-cream-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Semua Pesanan</span>
                <span class="text-xl font-bold font-mono">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="p-3.5 rounded-2xl border transition-smooth {{ request('status') === 'pending_payment' ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-amber-50/60 text-amber-900 border-amber-200/60 hover:border-amber-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Menunggu Bayar</span>
                <span class="text-xl font-bold font-mono">{{ $counts['pending_payment'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="p-3.5 rounded-2xl border transition-smooth {{ request('status') === 'paid' ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-emerald-50/60 text-emerald-900 border-emerald-200/60 hover:border-emerald-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Dibayar (Siap Proses)</span>
                <span class="text-xl font-bold font-mono">{{ $counts['paid'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-3.5 rounded-2xl border transition-smooth {{ request('status') === 'processing' ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-indigo-50/60 text-indigo-900 border-indigo-200/60 hover:border-indigo-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Sedang Diproses</span>
                <span class="text-xl font-bold font-mono">{{ $counts['processing'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="p-3.5 rounded-2xl border transition-smooth {{ request('status') === 'shipped' ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-purple-50/60 text-purple-900 border-purple-200/60 hover:border-purple-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Dalam Pengiriman</span>
                <span class="text-xl font-bold font-mono">{{ $counts['shipped'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-3.5 rounded-2xl border transition-smooth {{ request('status') === 'completed' ? 'bg-charcoal-950 text-cream-300 border-charcoal-950 shadow-md' : 'bg-emerald-50/60 text-emerald-900 border-emerald-200/60 hover:border-emerald-300' }}">
                <span class="text-[10px] uppercase tracking-wider block opacity-75 font-semibold">Selesai</span>
                <span class="text-xl font-bold font-mono">{{ $counts['completed'] }}</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-card p-4 sm:p-5 rounded-3xl">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor pesanan, nama pelanggan, email, nomor telepon..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl text-xs focus:ring-2 focus:ring-charcoal-950">
                <svg class="w-4 h-4 text-charcoal-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <select name="payment_status" class="px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl text-xs focus:ring-2 focus:ring-charcoal-950">
                <option value="">-- Status Pembayaran --</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar (Unpaid)</option>
                <option value="settlement" {{ request('payment_status') === 'settlement' ? 'selected' : '' }}>Lunas (Settlement)</option>
                <option value="expired" {{ request('payment_status') === 'expired' ? 'selected' : '' }}>Kedaluwarsa (Expired)</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-wider shadow-sm transition-smooth">
                Filter
            </button>
            @if(request()->hasAny(['q', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-cream-200 text-charcoal-700 font-bold rounded-2xl text-xs text-center flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="glass-card rounded-3xl overflow-hidden shadow-lg border border-cream-200/90">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/90 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-4 px-5">Nomor Pesanan &amp; Tanggal</th>
                        <th class="py-4 px-5">Pelanggan</th>
                        <th class="py-4 px-5">Ekspedisi &amp; Resi</th>
                        <th class="py-4 px-5 text-right">Total Tagihan</th>
                        <th class="py-4 px-5 text-center">Status Pesanan</th>
                        <th class="py-4 px-5 text-center">Pembayaran</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-cream-50/70 transition-smooth">
                            <td class="py-4 px-5 space-y-0.5">
                                <a href="{{ route('admin.orders.show', $order->order_number) }}" class="font-mono font-bold text-charcoal-950 text-sm hover:underline">
                                    #{{ $order->order_number }}
                                </a>
                                <span class="text-[11px] text-charcoal-400 block font-mono">
                                    {{ $order->created_at->format('d/m/Y H:i') }} WIB
                                </span>
                            </td>
                            <td class="py-4 px-5 space-y-0.5">
                                <span class="font-bold text-charcoal-900 block">{{ $order->customer_name }}</span>
                                <span class="text-[11px] text-charcoal-500 font-mono">{{ $order->customer_phone }}</span>
                                <span class="inline-block px-2 py-0.5 rounded-md bg-cream-200 text-[10px] font-bold text-charcoal-700 uppercase">
                                    {{ $order->customer_type->value }}
                                </span>
                            </td>
                            <td class="py-4 px-5 space-y-0.5">
                                <span class="font-bold uppercase text-charcoal-900 block">{{ $order->shipment?->courier_code }} {{ $order->shipment?->service_name }}</span>
                                @if($order->shipment?->tracking_number)
                                    <span class="font-mono text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px] font-bold">
                                        {{ $order->shipment->tracking_number }}
                                    </span>
                                @else
                                    <span class="text-charcoal-400 italic text-[11px]">Belum ada resi</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right font-mono font-bold text-charcoal-950 text-sm">
                                {{ $order->formatted_grand_total }}
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->status->badgeClasses() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase font-mono {{ $order->payment_status->badgeClasses() }}">
                                    {{ $order->payment_status->value }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('admin.orders.show', $order->order_number) }}"
                                    class="px-3.5 py-1.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-xl text-xs font-bold transition-smooth shadow-xs">
                                    Kelola &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-charcoal-400">
                                Tidak ada data pesanan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-cream-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
