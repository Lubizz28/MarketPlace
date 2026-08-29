@extends('layouts.dashboard')

@section('title', 'Katalog Produk — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Manajemen Katalog</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Daftar Produk Busana</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Kelola katalog busana muslimah, struktur harga bertingkat (Retail/Member/Reseller), dan varian produk.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2.5 bg-cream-200 text-charcoal-800 rounded-2xl text-xs font-bold transition-all hover:bg-cream-300">
                📦 Kelola Stok Inventori
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold transition-all shadow-md">
                + Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="glass-card p-4 rounded-3xl">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau SKU..."
                    class="w-full bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <select name="category_id" class="bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-charcoal-950 text-cream-200 rounded-xl font-bold">Filter</button>
            @if(request()->hasAny(['q', 'category_id']))
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-charcoal-500 hover:text-charcoal-950 text-xs">Reset</a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga Retail / Member / Reseller</th>
                        <th class="py-3 px-4 text-center">Total Varian</th>
                        <th class="py-3 px-4 text-center">Total Stok</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($products as $prod)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block text-sm">{{ $prod->name }}</span>
                                <span class="text-[10px] text-charcoal-400 font-mono">SKU: {{ $prod->sku }} • {{ $prod->weight_grams }}g</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cream-200 text-charcoal-800">
                                    {{ $prod->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px]">
                                <span class="text-charcoal-900 font-bold block">Rp {{ number_format($prod->getMinPriceFor('retail'), 0, ',', '.') }}</span>
                                <span class="text-[10px] text-cream-900 block">Member: Rp {{ number_format($prod->getMinPriceFor('member'), 0, ',', '.') }}</span>
                                <span class="text-[10px] text-emerald-800 block">Reseller: Rp {{ number_format($prod->getMinPriceFor('reseller'), 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                {{ $prod->variants->count() }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                @php $stockSum = $prod->variants->sum('stock'); @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] {{ $stockSum > 5 ? 'bg-emerald-100 text-emerald-900' : ($stockSum > 0 ? 'bg-amber-100 text-amber-900' : 'bg-rose-100 text-rose-900') }}">
                                    {{ $stockSum }} pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $prod->is_active ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                                    {{ $prod->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $prod) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.toggle', $prod) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-charcoal-400 hover:text-charcoal-800">
                                        {{ $prod->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
