@extends('layouts.dashboard')

@section('title', 'Edit Banner — ' . $banner->title)

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.cms.banners.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Banner</a>
    </div>

    <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
        <div>
            <h1 class="text-xl font-display font-bold text-charcoal-950">Edit Hero Banner</h1>
            <p class="text-xs text-charcoal-500 font-light">Perbarui visual dan tautan banner halaman utama.</p>
        </div>

        <form action="{{ route('admin.cms.banners.update', $banner) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Judul Utama Banner <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Subtitle / Keterangan Singkat
                </label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    URL Gambar Banner (1200x500 px) <span class="text-rose-500">*</span>
                </label>
                <input type="url" name="image_path" value="{{ old('image_path', $banner->image_path) }}" required
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none focus:border-cream-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Teks Tombol CTA
                    </label>
                    <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Target Link Tombol
                    </label>
                    <input type="text" name="button_url" value="{{ old('button_url', $banner->button_url) }}"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none focus:border-cream-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Urutan Tampil (Sort Order)
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none focus:border-cream-500">
                </div>

                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} class="rounded border-cream-300 text-charcoal-950 shadow-sm focus:ring-cream-500">
                        <span class="ml-2 text-xs font-bold text-charcoal-800">Banner Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.cms.banners.index') }}" class="px-5 py-2.5 rounded-2xl border border-cream-300 text-xs font-bold text-charcoal-700 hover:bg-cream-100/60 transition">
                    Batal
                </a>
                <button type="submit" class="btn-gold px-6 py-2.5 rounded-2xl text-xs font-bold shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
