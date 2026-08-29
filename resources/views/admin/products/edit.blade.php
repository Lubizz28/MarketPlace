@extends('layouts.dashboard')

@section('title', 'Edit Produk — ' . $product->name)

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.products.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Produk</a>
    </div>

    <div>
        <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Edit Katalog</span>
        <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1">Perbarui Produk</h1>
        <p class="text-xs text-charcoal-500 font-light mt-1">{{ $product->name }} (SKU: {{ $product->sku }})</p>
    </div>

    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Nama Produk Busana <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
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
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Berat Paket (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams) }}" required min="1"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none">
                </div>
            </div>

            <!-- Price Tiering Grid -->
            <div class="p-4 rounded-2xl bg-cream-50 border border-cream-200 space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-charcoal-800 block">Struktur Harga Bertingkat:</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-charcoal-600 mb-1">Harga Retail (Rp) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price', (int) $product->getMinPriceFor('retail')) }}" required
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-cream-900 mb-1">Harga Member (Rp)</label>
                        <input type="number" name="member_price" value="{{ old('member_price', (int) $product->getMinPriceFor('member')) }}"
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-emerald-900 mb-1">Harga Reseller (Rp)</label>
                        <input type="number" name="reseller_price" value="{{ old('reseller_price', (int) $product->getMinPriceFor('reseller')) }}"
                            class="w-full bg-white border border-cream-300 rounded-xl py-2 px-3 text-xs font-mono">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Deskripsi Lengkap &amp; Material Kain
                </label>
                <textarea name="description" rows="4" class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3.5 text-xs text-charcoal-950 focus:outline-none shadow-xs">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                    class="rounded text-charcoal-900 focus:ring-cream-400">
                <label for="is_active" class="text-xs font-semibold text-charcoal-800">Produk Aktif &amp; Ditampilkan di Katalog Publik</label>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-cream-200 text-charcoal-800 rounded-2xl text-xs font-bold">Batal</a>
                <button type="submit" class="px-7 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
