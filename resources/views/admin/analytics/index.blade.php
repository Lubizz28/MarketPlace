@extends('layouts.dashboard')

@section('title', 'Laporan & Analitik Penjualan — Admin Panel')

@section('content')
<div class="space-y-8" x-data="{
    customFilter: '{{ $period }}' === 'custom',
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Executive Intelligence</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Laporan &amp; Analitik Penjualan</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Pantau performa omzet (GMV), efektivitas promosi, kesehatan inventori, dan kontribusi reseller.</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.analytics.export.orders', ['period' => $period, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white rounded-2xl text-xs font-bold transition-all shadow-md flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                <span>Unduh Laporan CSV</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card p-4 rounded-3xl">
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <span class="font-bold text-charcoal-700 uppercase tracking-wider text-[10px]">Periode:</span>
            
            <a href="{{ route('admin.analytics.index', ['period' => 'today']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'today' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Hari Ini
            </a>
            <a href="{{ route('admin.analytics.index', ['period' => 'yesterday']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'yesterday' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Kemarin
            </a>
            <a href="{{ route('admin.analytics.index', ['period' => 'this_week']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_week' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Minggu Ini
            </a>
            <a href="{{ route('admin.analytics.index', ['period' => 'this_month']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_month' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Bulan Ini
            </a>
            <a href="{{ route('admin.analytics.index', ['period' => 'this_year']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_year' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Tahun Ini
            </a>
            <a href="{{ route('admin.analytics.index', ['period' => 'all']) }}"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ $period === 'all' ? 'bg-charcoal-950 text-cream-300' : 'bg-cream-100 text-charcoal-700 hover:bg-cream-200' }}">
                Semua
            </a>
            <button type="button" @click="customFilter = !customFilter"
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all border border-cream-300 bg-white text-charcoal-800">
                Tanggal Kustom ▾
            </button>

            <div x-show="customFilter" x-cloak class="flex items-center space-x-2 w-full sm:w-auto mt-2 sm:mt-0">
                <input type="hidden" name="period" value="custom">
                <input type="date" name="start_date" value="{{ request('start_date', $analytics['start_date']) }}" class="bg-white border border-cream-300 rounded-xl px-3 py-1 text-xs">
                <span>s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date', $analytics['end_date']) }}" class="bg-white border border-cream-300 rounded-xl px-3 py-1 text-xs">
                <button type="submit" class="px-3.5 py-1.5 bg-charcoal-950 text-cream-200 rounded-xl font-bold">Terapkan</button>
            </div>
        </form>
    </div>

    <!-- Financial KPI Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="glass-card p-5 sm:p-6 rounded-3xl border-2 border-cream-400">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-800">Gross Revenue (GMV)</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $analytics['formatted_gmv'] }}</p>
            <span class="text-[10px] text-emerald-800 font-semibold">Total omzet terbayar</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Subtotal Produk</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $analytics['formatted_subtotal'] }}</p>
            <span class="text-[10px] text-charcoal-400">Di luar ongkir &amp; diskon</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Rata-Rata Order (AOV)</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $analytics['formatted_aov'] }}</p>
            <span class="text-[10px] text-charcoal-400">Nilai transaksi per keranjang</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Total Diskon Diberikan</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $analytics['formatted_discounts'] }}</p>
            <span class="text-[10px] text-amber-700 font-semibold">Kupon &amp; Poin Loyalitas</span>
        </div>
    </div>

    <!-- Order Volume KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="glass-card p-4 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Total Pesanan Masuk</span>
                <p class="text-xl font-bold font-mono text-charcoal-950 mt-0.5">{{ $analytics['total_orders'] }}</p>
            </div>
            <span class="text-xs font-bold text-charcoal-400">📦</span>
        </div>

        <div class="glass-card p-4 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-800">Pesanan Lunas/Selesai</span>
                <p class="text-xl font-bold font-mono text-emerald-950 mt-0.5">{{ $analytics['paid_orders'] }}</p>
            </div>
            <span class="text-xs font-bold text-emerald-600">✓</span>
        </div>

        <div class="glass-card p-4 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Tingkat Penyelesaian</span>
                <p class="text-xl font-bold font-mono text-charcoal-950 mt-0.5">{{ $analytics['fulfillment_rate'] }}%</p>
            </div>
            <span class="text-xs font-bold text-blue-600">⚡</span>
        </div>

        <div class="glass-card p-4 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-rose-800">Pesanan Dibatalkan</span>
                <p class="text-xl font-bold font-mono text-rose-950 mt-0.5">{{ $analytics['cancelled_orders'] }}</p>
            </div>
            <span class="text-xs font-bold text-rose-600">✕</span>
        </div>
    </div>

    <!-- Visual Sales Trend Bar Chart in Pure CSS / Tailwind -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <div class="flex items-center justify-between border-b border-cream-200 pb-3">
            <div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Tren Omzet Penjualan Harian</h3>
                <p class="text-xs text-charcoal-400 font-light">Grafik fluktuasi pendapatan selama periode aktif.</p>
            </div>
            <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                {{ count($analytics['sales_trend']['labels']) }} Titik Data
            </span>
        </div>

        @php
            $maxRev = max(1, max($analytics['sales_trend']['revenue'] ?? [1]));
        @endphp

        <div class="pt-6 pb-2">
            <div class="flex items-end space-x-2 h-44 sm:h-52 overflow-x-auto pb-4">
                @foreach($analytics['sales_trend']['labels'] as $index => $label)
                    @php
                        $rev = $analytics['sales_trend']['revenue'][$index] ?? 0;
                        $ordersCount = $analytics['sales_trend']['orders'][$index] ?? 0;
                        $heightPercent = max(6, round(($rev / $maxRev) * 100));
                    @endphp
                    <div class="flex-1 min-w-[36px] flex flex-col items-center justify-end h-full group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-12 bg-charcoal-950 text-cream-200 text-[10px] py-1 px-2.5 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20 font-mono">
                            {{ $label }}: Rp {{ number_format($rev, 0, ',', '.') }} ({{ $ordersCount }} order)
                        </div>

                        <!-- Bar -->
                        <div class="w-full rounded-t-xl transition-all duration-300 group-hover:opacity-80 {{ $rev > 0 ? 'bg-charcoal-950 border-t-2 border-cream-400' : 'bg-cream-200' }}"
                            style="height: {{ $heightPercent }}%;"></div>

                        <!-- Date Label -->
                        <span class="text-[9px] text-charcoal-400 font-mono mt-2 truncate w-full text-center">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Rankings, Reseller Matrix & Inventory Health Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Selling Products -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Produk Terlaris</h3>
            
            @if($analytics['top_products']->isEmpty())
                <p class="text-xs text-charcoal-400 font-light italic py-4">Belum ada data penjualan pada periode ini.</p>
            @else
                <div class="space-y-3">
                    @foreach($analytics['top_products'] as $idx => $prod)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-2">
                                <span class="w-5 h-5 rounded-full bg-cream-200 text-charcoal-900 font-bold text-[10px] flex items-center justify-center">{{ $idx + 1 }}</span>
                                <span class="font-semibold text-charcoal-900 truncate max-w-[140px]">{{ $prod->product_name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-charcoal-950">{{ $prod->total_qty }} pcs</span>
                                <span class="text-[10px] text-charcoal-400 block font-mono">Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Top Resellers Matrix -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Top Mitra Reseller</h3>

            @if($analytics['top_resellers']->isEmpty())
                <p class="text-xs text-charcoal-400 font-light italic py-4">Belum ada transaksi referral pada periode ini.</p>
            @else
                <div class="space-y-3">
                    @foreach($analytics['top_resellers'] as $idx => $res)
                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <span class="font-semibold text-charcoal-900 block">{{ $res->reseller?->name }}</span>
                                <span class="text-[10px] text-charcoal-400">{{ $res->total_referral_orders }} pesanan referral</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-emerald-800">Rp {{ number_format($res->total_sales_volume, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-charcoal-400 block">Komisi: Rp {{ number_format($res->total_commissions, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Inventory Alerts -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <div class="flex items-center justify-between border-b border-cream-200 pb-2">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Peringatan Stok Rendah</h3>
                <span class="text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                    {{ $analytics['out_of_stock_count'] }} Habis
                </span>
            </div>

            @if($analytics['low_stock_variants']->isEmpty())
                <p class="text-xs text-emerald-800 font-semibold py-4">✓ Seluruh varian inventori dalam kondisi aman.</p>
            @else
                <div class="space-y-2.5 max-h-56 overflow-y-auto pr-1">
                    @foreach($analytics['low_stock_variants'] as $var)
                        <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-rose-50/60 border border-rose-100">
                            <div>
                                <span class="font-semibold text-charcoal-950 block truncate max-w-[150px]">{{ $var->product?->name }}</span>
                                <span class="text-[10px] text-charcoal-500">{{ $var->name }} (SKU: {{ $var->sku }})</span>
                            </div>
                            <span class="font-mono font-bold px-2 py-0.5 rounded-md text-[11px] {{ $var->stock <= 0 ? 'bg-rose-700 text-white' : 'bg-amber-200 text-amber-950' }}">
                                Sisa {{ $var->stock }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
