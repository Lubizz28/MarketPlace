@extends('layouts.dashboard')

@section('title', 'Manajemen Stok & Inventori — Admin Panel')

@section('content')
<div class="space-y-6" x-data="{
    adjustModalOpen: false,
    selectedVariant: null,
    adjustActionUrl: '',
    openAdjust(variant, url) {
        this.selectedVariant = variant;
        this.adjustActionUrl = url;
        this.adjustModalOpen = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Logistik &amp; Gudang</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Inventori &amp; Stok Varian</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Pantau ketersediaan stok fisik per varian, lakukan penyesuaian/restock, dan periksa buku kas mutasi barang.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-cream-200 text-charcoal-800 rounded-2xl text-xs font-bold transition-all hover:bg-cream-300">
                &larr; Katalog Produk
            </a>
        </div>
    </div>

    <!-- Inventory Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Total Varian Busana</span>
            <p class="text-2xl font-bold font-mono text-charcoal-950 mt-1">{{ $stats['total_variants'] }}</p>
            <span class="text-[10px] text-charcoal-400">SKU terdaftar</span>
        </div>

        <div class="glass-card p-5 rounded-3xl border-2 border-emerald-400">
            <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-900">Total Unit Fisik</span>
            <p class="text-2xl font-bold font-mono text-emerald-950 mt-1">{{ number_format($stats['total_units'], 0, ',', '.') }} pcs</p>
            <span class="text-[10px] text-emerald-800">Siap dikirim di gudang</span>
        </div>

        <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" class="glass-card p-5 rounded-3xl transition-all hover:border-amber-400 {{ request('filter') === 'low_stock' ? 'border-2 border-amber-500 bg-amber-50/50' : '' }}">
            <span class="text-[9px] font-bold uppercase tracking-wider text-amber-800">Stok Menipis (≤ 5)</span>
            <p class="text-2xl font-bold font-mono text-amber-950 mt-1">{{ $stats['low_stock_count'] }}</p>
            <span class="text-[10px] text-amber-700 font-semibold">Perlu restock segera &rarr;</span>
        </a>

        <a href="{{ route('admin.inventory.index', ['filter' => 'out_of_stock']) }}" class="glass-card p-5 rounded-3xl transition-all hover:border-rose-400 {{ request('filter') === 'out_of_stock' ? 'border-2 border-rose-500 bg-rose-50/50' : '' }}">
            <span class="text-[9px] font-bold uppercase tracking-wider text-rose-800">Stok Habis (0)</span>
            <p class="text-2xl font-bold font-mono text-rose-950 mt-1">{{ $stats['out_of_stock_count'] }}</p>
            <span class="text-[10px] text-rose-700 font-semibold">Varian kosong &rarr;</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-card p-4 rounded-3xl">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <div class="flex-1 min-w-[220px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk, varian, atau SKU..."
                    class="w-full bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div class="flex items-center space-x-1.5">
                <a href="{{ route('admin.inventory.index') }}" class="px-3 py-1.5 rounded-xl font-bold {{ !request('filter') ? 'bg-charcoal-950 text-cream-200' : 'bg-cream-100 text-charcoal-700' }}">
                    Semua
                </a>
                <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('filter') === 'low_stock' ? 'bg-charcoal-950 text-cream-200' : 'bg-cream-100 text-charcoal-700' }}">
                    Stok Menipis
                </a>
                <a href="{{ route('admin.inventory.index', ['filter' => 'out_of_stock']) }}" class="px-3 py-1.5 rounded-xl font-bold {{ request('filter') === 'out_of_stock' ? 'bg-charcoal-950 text-cream-200' : 'bg-cream-100 text-charcoal-700' }}">
                    Stok Habis
                </a>
            </div>

            <button type="submit" class="px-4 py-2 bg-charcoal-950 text-cream-200 rounded-xl font-bold">Cari</button>
            @if(request()->hasAny(['q', 'filter']))
                <a href="{{ route('admin.inventory.index') }}" class="px-2 py-2 text-charcoal-400 hover:text-charcoal-800 text-xs">Reset</a>
            @endif
        </form>
    </div>

    <!-- Inventory Matrix Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">SKU Varian</th>
                        <th class="py-3 px-4">Nama Produk &amp; Varian</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-center">Stok Gudang</th>
                        <th class="py-3 px-4 font-mono">Harga Retail / Reseller</th>
                        <th class="py-3 px-4 text-right">Aksi Penyesuaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($variants as $var)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-charcoal-950">
                                {{ $var->sku }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $var->product?->name }}</span>
                                <span class="text-[10px] text-charcoal-500 font-medium">Varian: {{ $var->name }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cream-200 text-charcoal-800">
                                    {{ $var->product?->category?->name ?? 'Kategori' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $var->stock > 5 ? 'bg-emerald-100 text-emerald-950' : ($var->stock > 0 ? 'bg-amber-100 text-amber-950' : 'bg-rose-100 text-rose-950') }}">
                                    {{ $var->stock }} pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px]">
                                <span class="text-charcoal-900 font-bold block">{{ $var->formatted_price }}</span>
                                <span class="text-[10px] text-emerald-800 block">Reseller: {{ $var->formatted_reseller_price }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <button type="button"
                                    @click="openAdjust({{ json_encode($var) }}, '{{ route('admin.inventory.adjust', $var) }}')"
                                    class="px-3 py-1.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-xl text-xs font-bold transition-all shadow-xs">
                                    + Sesuaikan Stok
                                </button>
                                <a href="{{ route('admin.inventory.movements', $var) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Buku Kas Mutasi &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada varian inventori ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $variants->links() }}
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div x-show="adjustModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-charcoal-950/60 backdrop-blur-xs">
        <div class="glass-card max-w-md w-full p-6 sm:p-7 rounded-3xl space-y-5 bg-white shadow-2xl" @click.away="adjustModalOpen = false">
            <div class="flex items-center justify-between border-b border-cream-200 pb-3">
                <h3 class="font-display font-bold text-charcoal-950 text-base">Penyesuaian Stok Fisik</h3>
                <button type="button" @click="adjustModalOpen = false" class="text-charcoal-400 hover:text-charcoal-900 font-bold">&times;</button>
            </div>

            <template x-if="selectedVariant">
                <form method="POST" :action="adjustActionUrl" class="space-y-4">
                    @csrf
                    
                    <div class="p-3 bg-cream-50 rounded-2xl border border-cream-200 text-xs">
                        <span class="font-bold text-charcoal-950 block" x-text="selectedVariant.product.name"></span>
                        <span class="text-charcoal-500 font-mono text-[11px]" x-text="'Varian: ' + selectedVariant.name + ' (' + selectedVariant.sku + ')'"></span>
                        <div class="mt-2 text-[11px] font-bold text-emerald-900">
                            Stok Saat Ini: <span class="font-mono" x-text="selectedVariant.stock + ' pcs'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Tipe Mutasi Stok <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" required class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950">
                            <option value="restock">Restock Masuk (+) (Pembelian Vendor)</option>
                            <option value="adjustment">Koreksi / Penyesuaian Manual (+ / -)</option>
                            <option value="return">Retur Barang Masuk (+)</option>
                            <option value="sale">Penjualan Toko Fisik / Event (-)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Jumlah Perubahan (Quantity) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="quantity" required placeholder="Contoh: 20 atau -5" class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Catatan / Keterangan Audit <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="notes" required placeholder="Contoh: Restock batch produksi PO #289" class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs">
                    </div>

                    <div class="pt-3 flex justify-end space-x-2">
                        <button type="button" @click="adjustModalOpen = false" class="px-4 py-2 bg-cream-200 text-charcoal-800 rounded-xl text-xs font-bold">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-charcoal-950 text-cream-200 rounded-xl text-xs font-bold">Simpan Mutasi</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection
