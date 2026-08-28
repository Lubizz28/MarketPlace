<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Resmi #{{ $order->order_number }} — MedinaStyle</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .invoice-container { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-cream-100/60 font-sans text-charcoal-900 antialiased p-4 sm:p-8 min-h-screen">

    <!-- Print Action Header -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs font-semibold text-charcoal-600 hover:text-charcoal-950 flex items-center space-x-1">
            <span>&larr; Kembali ke Pelacakan Pesanan</span>
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-smooth flex items-center space-x-2">
            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015.75 1.5h-7.5A2.25 2.25 0 006 3.75v3.206"/></svg>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Invoice Sheet -->
    <div class="invoice-container max-w-4xl mx-auto bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-cream-300 space-y-8">
        
        <!-- Header: Brand & Invoice Meta -->
        <div class="flex flex-col sm:flex-row justify-between items-start pb-8 border-b-2 border-charcoal-950 gap-6">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 rounded-2xl bg-charcoal-950 text-cream-300 font-display font-bold text-xl flex items-center justify-center">M</div>
                    <div>
                        <span class="text-xl font-display font-bold tracking-wider text-charcoal-950">MEDINASTYLE</span>
                        <span class="block text-[10px] uppercase tracking-widest text-charcoal-500 font-medium">Haute Modestie &amp; Islamic Luxury</span>
                    </div>
                </div>
                <p class="text-xs text-charcoal-500 leading-relaxed">
                    PT Medina Kreasi Busana Indonesia<br>
                    Sudirman Central Business District (SCBD), Jakarta 12190<br>
                    NPWP: 01.882.910.4-012.000 &bull; info@medinastyle.com
                </p>
            </div>

            <div class="text-left sm:text-right space-y-1">
                <span class="px-3 py-1 bg-charcoal-950 text-cream-300 text-xs font-bold uppercase tracking-widest rounded-lg inline-block">FAKTUR RESMI</span>
                <p class="text-lg font-bold font-mono text-charcoal-950">#{{ $order->order_number }}</p>
                <p class="text-xs text-charcoal-500">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }} WIB</p>
                <p class="text-xs font-bold text-charcoal-700">Status: <span class="uppercase font-mono">{{ $order->status->label() }}</span></p>
            </div>
        </div>

        <!-- Billed To & Shipped To Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs py-2">
            <div class="space-y-1.5">
                <span class="font-bold text-[11px] uppercase tracking-wider text-charcoal-400">Data Pemesan (Billed To):</span>
                <p class="font-bold text-charcoal-950 text-sm">{{ $order->customer_name }}</p>
                <p class="text-charcoal-600">{{ $order->customer_email }}</p>
                <p class="font-mono text-charcoal-600">{{ $order->customer_phone }}</p>
                <p class="text-charcoal-500 text-[11px] mt-1">Tipe Pelanggan: <b class="uppercase">{{ $order->customer_type->value }}</b></p>
            </div>

            <div class="space-y-1.5">
                <span class="font-bold text-[11px] uppercase tracking-wider text-charcoal-400">Alamat Pengiriman (Shipped To):</span>
                <p class="font-bold text-charcoal-950 text-sm">{{ $order->address?->recipient_name }}</p>
                <p class="font-mono text-charcoal-600">{{ $order->address?->phone }}</p>
                <p class="text-charcoal-700 leading-relaxed">{{ $order->address?->address_line }}</p>
                <p class="text-charcoal-600">{{ $order->address?->city_name }}, {{ $order->address?->province_name }} {{ $order->address?->postal_code }}</p>
                <p class="text-charcoal-500 text-[11px] mt-1">Kurir: <b class="uppercase">{{ $order->shipment?->courier_name }} ({{ $order->shipment?->service_name }})</b></p>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b-2 border-charcoal-900 bg-cream-50 text-charcoal-950 uppercase tracking-wider font-bold">
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Deskripsi Produk</th>
                        <th class="py-3 px-3 text-center">SKU</th>
                        <th class="py-3 px-3 text-right">Harga Satuan</th>
                        <th class="py-3 px-3 text-center">Qty</th>
                        <th class="py-3 px-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td class="py-3 px-3 font-mono text-charcoal-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <span class="font-bold text-charcoal-950 block">{{ $item->product_name }}</span>
                                <span class="text-[11px] text-charcoal-500">Varian: {{ $item->variant_name }}</span>
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-charcoal-500">{{ $item->sku }}</td>
                            <td class="py-3 px-3 text-right font-mono">{{ $item->formatted_price }}</td>
                            <td class="py-3 px-3 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-charcoal-950">{{ $item->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Calculation Block -->
        <div class="flex justify-between items-start pt-4 border-t border-cream-300">
            <div class="text-xs text-charcoal-500 space-y-1 max-w-xs">
                <p class="font-bold text-charcoal-900">Metode Pembayaran:</p>
                <p class="uppercase font-mono font-semibold">{{ $order->payment?->payment_method->label() ?? 'Online Gateway' }}</p>
                <p class="text-[11px] mt-2">Status Pembayaran: <b class="uppercase font-mono text-charcoal-950">{{ $order->payment_status->value }}</b></p>
                @if($order->paid_at)
                    <p class="text-[11px]">Terverifikasi: {{ $order->paid_at->format('d/m/Y H:i') }} WIB</p>
                @endif
            </div>

            <div class="w-72 space-y-2 text-xs">
                <div class="flex justify-between text-charcoal-600">
                    <span>Subtotal Produk</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="flex justify-between text-charcoal-600">
                    <span>Biaya Ongkos Kirim</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $order->formatted_shipping_cost }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Diskon Pembelian</span>
                        <span class="font-mono font-bold">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="pt-3 border-t-2 border-charcoal-950 flex justify-between text-sm font-bold text-charcoal-950">
                    <span>Total Tagihan</span>
                    <span class="font-mono text-base">{{ $order->formatted_grand_total }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Seal & Sign -->
        <div class="pt-8 border-t border-cream-200 flex justify-between items-end text-xs text-charcoal-500">
            <div>
                <p class="font-bold text-charcoal-800">Catatan Penting:</p>
                <p class="text-[11px] leading-relaxed">
                    Faktur ini adalah bukti transaksi sah yang diterbitkan oleh sistem MedinaStyle.<br>
                    Barang yang telah dikirim dilindungi oleh jaminan keaslian dan asuransi kurir resmi.
                </p>
            </div>
            <div class="text-center space-y-1">
                <div class="w-24 h-12 mx-auto border-b border-charcoal-300 flex items-center justify-center text-[10px] italic text-charcoal-400">
                    [E-Signature Stamp]
                </div>
                <span class="font-bold text-charcoal-900 block">MedinaStyle Authorized</span>
            </div>
        </div>

    </div>

</body>
</html>
