@extends('layouts.app')

@section('title', 'Pesanan #' . $order->order_number . ' — Status & Faktur')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Status Banner -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl border-2 border-cream-300 shadow-xl space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-cream-200">
            <div>
                <div class="flex items-center space-x-3">
                    <span class="text-xs uppercase tracking-widest font-mono text-charcoal-400">FAKTUR PESANAN</span>
                    <span class="px-3 py-0.5 rounded-full text-xs font-bold border {{ $order->status->badgeClasses() }}">
                        {{ $order->status->label() }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1 font-mono">
                    #{{ $order->order_number }}
                </h1>
                <p class="text-xs text-charcoal-500 font-light mt-1">
                    Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>

            <div class="text-right sm:text-right space-y-2">
                <div>
                    <span class="text-xs uppercase tracking-wider text-charcoal-500 font-bold block">Total Pembayaran</span>
                    <span class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono">
                        {{ $order->formatted_grand_total }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank"
                        class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-cream-100 text-charcoal-900 border border-cream-300 text-[11px] font-bold transition-smooth shadow-xs">
                        <svg class="w-3.5 h-3.5 text-charcoal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015.75 1.5h-7.5A2.25 2.25 0 006 3.75v3.206"/></svg>
                        <span>Cetak Faktur</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5-Step Order Progress Timeline -->
        <div class="py-4">
            <div class="grid grid-cols-5 gap-2 text-center text-xs">
                @php
                    $steps = [
                        ['status' => 'pending_payment', 'label' => 'Menunggu Pembayaran', 'active' => true],
                        ['status' => 'paid', 'label' => 'Dibayar', 'active' => $order->status->isPaid()],
                        ['status' => 'processing', 'label' => 'Diproses', 'active' => in_array($order->status->value, ['processing', 'shipped', 'delivered', 'completed'])],
                        ['status' => 'shipped', 'label' => 'Dikirim', 'active' => in_array($order->status->value, ['shipped', 'delivered', 'completed'])],
                        ['status' => 'completed', 'label' => 'Selesai', 'active' => $order->status->value === 'completed'],
                    ];
                @endphp

                @foreach($steps as $idx => $step)
                    <div class="flex flex-col items-center space-y-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $step['active'] ? 'bg-charcoal-950 text-cream-200 shadow-md ring-2 ring-cream-300' : 'bg-cream-200 text-charcoal-400' }}">
                            {{ $idx + 1 }}
                        </div>
                        <span class="text-[11px] font-medium {{ $step['active'] ? 'text-charcoal-950 font-bold' : 'text-charcoal-400' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Box based on Status -->
        @if($order->isPendingPayment())
            <div class="p-6 rounded-2xl bg-amber-50/80 border border-amber-200 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-display font-bold text-amber-950 text-base">Selesaikan Pembayaran Anda</h3>
                        <p class="text-xs text-amber-800 font-light mt-0.5">
                            Batas waktu pembayaran: <b class="font-mono">{{ $order->expires_at?->translatedFormat('d F Y, H:i') ?? '24 Jam' }} WIB</b>
                        </p>
                    </div>

                    <div class="flex items-center space-x-3">
                        @if($order->payment?->snap_token)
                            <button type="button" id="pay-button"
                                class="px-6 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl border border-cream-400/30 transition-smooth">
                                Bayar Sekarang via Snap &rarr;
                            </button>
                        @endif

                        <form method="POST" action="{{ route('orders.cancel', $order->order_number) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            <button type="submit" class="px-4 py-3 bg-white hover:bg-rose-50 text-rose-700 font-bold rounded-2xl text-xs border border-rose-200 transition-smooth">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                </div>

                @if($order->payment?->payment_method->value === 'manual_transfer')
                    <div class="pt-3 border-t border-amber-200 text-xs text-amber-900 space-y-1 font-mono">
                        <p><b>Rekening Transfer Bank:</b></p>
                        <p>Bank BCA: <b>8820-192-881</b> (a.n MedinaStyle Haute Modestie)</p>
                        <p>Bank Mandiri: <b>137-00-192881-2</b> (a.n MedinaStyle)</p>
                    </div>
                @endif
            </div>
        @elseif($order->isPaid())
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center space-x-3 text-emerald-900 text-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Pembayaran berhasil diverifikasi pada <b>{{ $order->paid_at?->translatedFormat('d F Y, H:i') }} WIB</b>. Pesanan Anda segera disiapkan.</span>
            </div>
        @endif

    </div>

    <!-- Invoice Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        
        <!-- Shipping Address Card -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm uppercase tracking-wider border-b border-cream-200 pb-2">
                Alamat Tujuan Pengiriman
            </h3>
            <div class="space-y-1 text-charcoal-700">
                <p class="font-bold text-charcoal-950 text-sm">{{ $order->address?->recipient_name }}</p>
                <p class="font-mono">{{ $order->address?->phone }}</p>
                <p class="leading-relaxed">{{ $order->address?->address_line }}</p>
                <p>{{ $order->address?->subdistrict_name ? $order->address->subdistrict_name . ', ' : '' }}{{ $order->address?->city_name }}, {{ $order->address?->province_name }} {{ $order->address?->postal_code }}</p>
                @if($order->address?->notes)
                    <p class="text-charcoal-500 italic mt-2">Catatan: {{ $order->address->notes }}</p>
                @endif
            </div>
        </div>

        <!-- Shipment Info Card -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm uppercase tracking-wider border-b border-cream-200 pb-2">
                Informasi Ekspedisi
            </h3>
            <div class="space-y-2 text-charcoal-700">
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Kurir &amp; Layanan:</span>
                    <span class="font-bold uppercase">{{ $order->shipment?->courier_name }} ({{ $order->shipment?->service_name }})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Estimasi Tiba:</span>
                    <span>{{ $order->shipment?->etd_days ? $order->shipment->etd_days . ' Hari Kerja' : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Nomor Resi (Waybill):</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->shipment?->tracking_number ?? 'Menunggu Pengiriman' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-charcoal-500">Metode Pembayaran:</span>
                    <span class="font-bold uppercase">{{ $order->payment?->payment_method->label() ?? 'Online' }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Order Items Table Card -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <h3 class="font-display font-bold text-charcoal-950 text-base pb-3 border-b border-cream-200">
            Rincian Item Pesanan
        </h3>

        <div class="divide-y divide-cream-200">
            @foreach($order->items as $item)
                <div class="py-4 flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-16 rounded-2xl bg-cream-100 overflow-hidden shrink-0">
                            <img src="{{ $item->variant?->product?->thumbnail_url ?? asset('images/placeholder.jpg') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="text-xs space-y-0.5">
                            <h4 class="font-bold text-charcoal-950 text-sm">{{ $item->product_name }}</h4>
                            <p class="text-charcoal-500">Varian: {{ $item->variant_name }} &bull; SKU: <span class="font-mono">{{ $item->sku }}</span></p>
                            <p class="text-charcoal-600 font-mono">{{ $item->quantity }} x {{ $item->formatted_price }}</p>
                        </div>
                    </div>

                    <div class="text-right font-mono font-bold text-charcoal-950 text-sm">
                        {{ $item->formatted_subtotal }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Financial Summary Lines -->
        <div class="pt-4 border-t border-cream-200 space-y-2 text-xs text-charcoal-600 max-w-xs ml-auto">
            <div class="flex justify-between">
                <span>Subtotal Produk</span>
                <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_subtotal }}</span>
            </div>
            <div class="flex justify-between">
                <span>Ongkos Kirim</span>
                <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_shipping_cost }}</span>
            </div>
            @if($order->coupon_discount > 0)
                <div class="flex justify-between text-emerald-700 font-medium">
                    <span>Diskon Kupon ({{ $order->coupon_code }})</span>
                    <span class="font-mono">- {{ $order->formatted_coupon_discount }}</span>
                </div>
            @endif
            @if($order->points_discount > 0)
                <div class="flex justify-between text-amber-700 font-medium">
                    <span>Diskon Poin ({{ $order->points_redeemed }} Poin)</span>
                    <span class="font-mono">- {{ $order->formatted_points_discount }}</span>
                </div>
            @endif
            @if($order->discount_amount > 0 && $order->coupon_discount == 0 && $order->points_discount == 0)
                <div class="flex justify-between text-emerald-700 font-medium">
                    <span>Potongan Diskon</span>
                    <span class="font-mono">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="pt-3 border-t border-cream-300 flex justify-between text-sm font-bold text-charcoal-950">
                <span>Total Akhir</span>
                <span class="font-mono text-base">{{ $order->formatted_grand_total }}</span>
            </div>
        </div>

    </div>

</div>

<!-- Midtrans Snap JS Script -->
@if($order->isPendingPayment() && $order->payment?->snap_token)
    @php
        $isProduction = config('services.midtrans.is_production', false);
        $snapJsUrl = $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key', 'SB-Mid-client-TESTKEY123456');
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.getElementById('pay-button')?.addEventListener('click', function () {
            if (window.snap) {
                window.snap.pay('{{ $order->payment->snap_token }}', {
                    onSuccess: function(result) {
                        window.location.reload();
                    },
                    onPending: function(result) {
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal, silakan coba metode pembayaran lain.');
                    },
                    onClose: function() {
                        console.log('Customer closed the payment popup without finishing.');
                    }
                });
            } else {
                alert('Snap payment gateway sedang dimuat. Silakan tunggu beberapa saat.');
            }
        });
    </script>
@endif
@endsection
