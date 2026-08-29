@extends('layouts.dashboard')

@section('title', 'Tambah Halaman Statis Baru')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.cms.pages.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Halaman</a>
    </div>

    <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
        <div>
            <h1 class="text-xl font-display font-bold text-charcoal-950">Tambah Halaman Statis Baru</h1>
            <p class="text-xs text-charcoal-500 font-light">Buat halaman informatif baru dengan URL ramah SEO.</p>
        </div>

        <form action="{{ route('admin.cms.pages.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Judul Halaman <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Panduan Ukuran &amp; Size Chart"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Isi Konten Halaman (HTML / Teks) <span class="text-rose-500">*</span>
                </label>
                <textarea name="content" rows="10" required placeholder="Tuliskan isi informasi lengkap di sini..."
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 leading-relaxed">{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Meta Title (SEO)
                    </label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Judul untuk mesin pencari Google"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Meta Description (SEO)
                    </label>
                    <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Deskripsi ringkas untuk snippet Google"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
                </div>
            </div>

            <div class="flex items-center pt-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-cream-300 text-charcoal-950 shadow-sm focus:ring-cream-500">
                    <span class="ml-2 text-xs font-bold text-charcoal-800">Publikasikan Halaman Langsung</span>
                </label>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.cms.pages.index') }}" class="px-5 py-2.5 rounded-2xl border border-cream-300 text-xs font-bold text-charcoal-700 hover:bg-cream-100/60 transition">
                    Batal
                </a>
                <button type="submit" class="btn-gold px-6 py-2.5 rounded-2xl text-xs font-bold shadow-md">
                    Simpan Halaman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
