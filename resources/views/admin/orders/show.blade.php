@extends('layouts.dashboard')

@section('title', 'Detail Pesanan #' . $order->order_number . ' — Admin Panel')
@section('page-title', 'Detail & Pemrosesan Pesanan')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-cream-200">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-semibold">&larr; Kembali ke Daftar Pesanan</a>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $order->status->badgeClasses() }}">
                        {{ $order->status->label() }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase font-mono {{ $order->payment_status->badgeClasses() }}">
                        Bayar: {{ $order->payment_status->value }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1 font-mono">
                    #{{ $order->order_number }}
                </h1>
                <p class="text-xs text-charcoal-500 font-light mt-0.5">
                    Dibuat: {{ $order->created_at->format('d F Y, H:i') }} WIB &bull; Tipe Pelanggan: <b class="uppercase">{{ $order->customer_type->value }}</b>
                </p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank"
                    class="px-4 py-2.5 bg-white hover:bg-cream-100 border border-cream-300 text-charcoal-900 font-bold rounded-2xl text-xs flex items-center space-x-2 shadow-xs transition-smooth">
                    <svg class="w-4 h-4 text-charcoal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015.75 1.5h-7.5A2.25 2.25 0 006 3.75v3.206"/></svg>
                    <span>Cetak Faktur</span>
                </a>
            </div>
        </div>

        <!-- Fulfillment & Status Controls Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            
            <!-- Resi Input Form -->
            <div class="p-5 rounded-2xl bg-white/70 border border-cream-200 space-y-3">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Input Resi &amp; Proses Pengiriman</h3>
                <form method="POST" action="{{ route('admin.orders.shipment', $order->order_number) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-charcoal-700 mb-1">Nomor Resi Ekspedisi (Waybill) *</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->shipment?->tracking_number ?? '') }}" required placeholder="Contoh: JNE018281928"
                            class="w-full px-3.5 py-2.5 bg-white border border-cream-300 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-charcoal-950">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-charcoal-700 mb-1">Catatan Pengiriman (Opsional)</label>
                        <input type="text" name="notes" value="{{ old('notes', $order->shipment?->notes ?? '') }}" placeholder="Contoh: Diserahkan ke kurir jemput"
                            class="w-full px-3.5 py-2.5 bg-white border border-cream-300 rounded-xl text-xs focus:ring-2 focus:ring-charcoal-950">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-xl text-xs uppercase tracking-wider shadow-sm transition-smooth">
                        Simpan Resi &amp; Tandai Dikirim &rarr;
                    </button>
                </form>
            </div>

            <!-- Status Transition Form -->
            <div class="p-5 rounded-2xl bg-white/70 border border-cream-200 space-y-3">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Perbarui Status Lifecycle Pesanan</h3>
                <form method="POST" action="{{ route('admin.orders.status', $order->order_number) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-charcoal-700 mb-1">Ubah Status Menjadi *</label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-white border border-cream-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-charcoal-950">
                            @foreach(\App\Enums\OrderStatus::cases() as $st)
                                <option value="{{ $st->value }}" {{ $order->status === $st ? 'selected' : '' }}>
                                    {{ $st->label() }} ({{ $st->value }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-charcoal-700 mb-1">Alasan / Catatan Perubahan (Opsional)</label>
                        <input type="text" name="reason" placeholder="Contoh: Pembayaran transfer diverifikasi manual"
                            class="w-full px-3.5 py-2.5 bg-white border border-cream-300 rounded-xl text-xs focus:ring-2 focus:ring-charcoal-950">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-cream-300 hover:bg-cream-400 text-charcoal-950 font-bold rounded-xl text-xs uppercase tracking-wider shadow-sm transition-smooth">
                        Perbarui Status &rarr;
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        
        <!-- Customer & Delivery Address -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm uppercase tracking-wider border-b border-cream-200 pb-2">
                Tujuan Pengiriman &amp; Kontak
            </h3>
            <div class="space-y-1 text-charcoal-700">
                <p class="font-bold text-charcoal-950 text-sm">{{ $order->address?->recipient_name }}</p>
                <p class="font-mono text-charcoal-600">{{ $order->address?->phone }}</p>
                <p class="leading-relaxed">{{ $order->address?->address_line }}</p>
                <p>{{ $order->address?->subdistrict_name ? $order->address->subdistrict_name . ', ' : '' }}{{ $order->address?->city_name }}, {{ $order->address?->province_name }} {{ $order->address?->postal_code }}</p>
                @if($order->notes)
                    <p class="p-2.5 rounded-xl bg-cream-100 text-charcoal-700 text-[11px] mt-2">
                        <b>Catatan Pembeli:</b> {{ $order->notes }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Shipment & Payment Details -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm uppercase tracking-wider border-b border-cream-200 pb-2">
                Detail Ekspedisi &amp; Pembayaran
            </h3>
            <div class="space-y-2 text-charcoal-700">
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Ekspedisi Kurir:</span>
                    <span class="font-bold uppercase">{{ $order->shipment?->courier_name }} ({{ $order->shipment?->service_name }})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Nomor Resi:</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->shipment?->tracking_number ?? 'Belum Diinput' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Metode Pembayaran:</span>
                    <span class="font-bold uppercase font-mono">{{ $order->payment?->payment_method->label() ?? 'Online' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Waktu Pelunasan:</span>
                    <span class="font-mono">{{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') . ' WIB' : 'Belum Lunas' }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Line Items Table -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-base pb-3 border-b border-cream-200">
            Rincian Item yang Dipesan
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold">
                        <th class="py-3 px-4">Produk &amp; Varian</th>
                        <th class="py-3 px-4 text-center">SKU</th>
                        <th class="py-3 px-4 text-right">Harga</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $item->product_name }}</span>
                                <span class="text-[11px] text-charcoal-500">{{ $item->variant_name }}</span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-charcoal-500">{{ $item->sku }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ $item->formatted_price }}</td>
                            <td class="py-3 px-4 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-charcoal-950">{{ $item->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-cream-200 flex justify-end">
            <div class="w-72 space-y-1.5 text-xs text-charcoal-600">
                <div class="flex justify-between">
                    <span>Subtotal Produk</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Ongkos Kirim</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_shipping_cost }}</span>
                </div>
                @if($order->coupon_discount > 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Diskon Kupon ({{ $order->coupon_code }})</span>
                        <span class="font-mono font-bold">- {{ $order->formatted_coupon_discount }}</span>
                    </div>
                @endif
                @if($order->points_discount > 0)
                    <div class="flex justify-between text-amber-700">
                        <span>Diskon Poin ({{ $order->points_redeemed }} Poin)</span>
                        <span class="font-mono font-bold">- {{ $order->formatted_points_discount }}</span>
                    </div>
                @endif
                @if($order->discount_amount > 0 && $order->coupon_discount == 0 && $order->points_discount == 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Diskon Potongan</span>
                        <span class="font-mono font-bold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="pt-2 border-t border-cream-300 flex justify-between text-sm font-bold text-charcoal-950">
                    <span>Total Tagihan</span>
                    <span class="font-mono text-base">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Transaction Audit Trail Log -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-cream-200">
            <h3 class="font-display font-bold text-charcoal-950 text-base">
                Audit Trail Webhook &amp; Transaksi Pembayaran
            </h3>
            <span class="text-xs text-charcoal-500 font-mono">{{ $order->payment?->transactions->count() ?? 0 }} Transaksi Tercatat</span>
        </div>

        <div class="space-y-3">
            @forelse($order->payment?->transactions ?? [] as $trx)
                <div class="p-4 rounded-2xl bg-white/80 border border-cream-200 space-y-2 text-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-bold text-charcoal-950">{{ $trx->gateway_reference }}</span>
                            <span class="px-2 py-0.5 rounded bg-cream-200 font-mono text-[10px] font-bold uppercase">{{ $trx->event_type }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono bg-charcoal-950 text-cream-300">{{ $trx->status }}</span>
                        </div>
                        <span class="text-charcoal-400 font-mono text-[11px]">{{ $trx->created_at->format('d/m/Y H:i:s') }} WIB</span>
                    </div>

                    @if(!empty($trx->payload_json))
                        <details class="text-[11px] font-mono bg-cream-50 p-3 rounded-xl border border-cream-200">
                            <summary class="cursor-pointer font-bold text-charcoal-700 select-none">Lihat Raw Payload JSON Webhook</summary>
                            <pre class="mt-2 overflow-x-auto text-charcoal-800 text-[10px]">{{ json_encode($trx->payload_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @endif
                </div>
            @empty
                <p class="text-center py-6 text-charcoal-400 text-xs">Belum ada catatan log transaksi webhook untuk pesanan ini.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
