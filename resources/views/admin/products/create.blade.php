@extends('layouts.dashboard')

@section('title', 'Tambah Produk Baru — Admin Panel')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.products.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Produk</a>
    </div>

    <div>
        <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Katalog Busana</span>
        <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1">Tambah Produk &amp; Varian Baru</h1>
        <p class="text-xs text-charcoal-500 font-light mt-1">Lengkapi informasi dasar produk, penetapan tiering harga (Retail/Member/Reseller), dan stok awal.</p>
    </div>

    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Nama Produk Busana <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Gamis Abaya Silk Khadijah Premium"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                @error('name')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" required class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Prefix SKU <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="sku_prefix" value="{{ old('sku_prefix') }}" required placeholder="Contoh: GMS-SLK"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none uppercase font-mono shadow-xs">
                </div>
            </div>

            <!-- Price Tiering Grid -->
            <div class="p-4 rounded-2xl bg-cream-50 border border-cream-200 space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-charcoal-800 block">Struktur Harga Bertingkat (Pricing Tiering):</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-charcoal-600 mb-1">Harga Retail / Tamu (Rp) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" required placeholder="Contoh: 350000"
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-cream-900 mb-1">Harga Khusus Member (Rp)</label>
                        <input type="number" name="member_price" value="{{ old('member_price') }}" placeholder="Opsional (default: -10%)"
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-emerald-900 mb-1">Harga Grosir Reseller (Rp)</label>
                        <input type="number" name="reseller_price" value="{{ old('reseller_price') }}" placeholder="Opsional (default: -20%)"
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                </div>
            </div>

            <!-- Initial Variant & Weight -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Nama Varian Pertama <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="variant_name" value="{{ old('variant_name', 'M / Hitam Obsidian') }}" required
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Stok Awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="initial_stock" value="{{ old('initial_stock', 50) }}" required min="0"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Berat Paket (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight_grams" value="{{ old('weight_grams', 500) }}" required min="1"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Deskripsi Lengkap &amp; Material Kain
                </label>
                <textarea name="description" rows="4" placeholder="Detail material, size chart, karakteristik kain, petunjuk perawatan..."
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3.5 text-xs text-charcoal-950 focus:outline-none shadow-xs">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-cream-200 text-charcoal-800 rounded-2xl text-xs font-bold">Batal</a>
                <button type="submit" class="px-7 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl transition-all">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
