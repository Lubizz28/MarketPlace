@extends('layouts.dashboard')

@section('title', 'Edit Artikel — ' . $post->title)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.cms.posts.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Artikel</a>
    </div>

    <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
        <div>
            <h1 class="text-xl font-display font-bold text-charcoal-950">Edit Artikel Blog</h1>
            <p class="text-xs text-charcoal-500 font-light">Perbarui isi konten, gambar sampul, dan status publikasi artikel.</p>
        </div>

        <form action="{{ route('admin.cms.posts.update', $post) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Judul Artikel <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    URL Thumbnail Gambar Sampul (800x500 px)
                </label>
                <input type="url" name="thumbnail_path" value="{{ old('thumbnail_path', $post->thumbnail_path) }}"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Kutipan Ringkas (Excerpt)
                </label>
                <textarea name="excerpt" rows="2"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Badan Konten Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" rows="12" required
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 leading-relaxed">{{ old('body', $post->body) }}</textarea>
            </div>

            <div class="flex items-center pt-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} class="rounded border-cream-300 text-charcoal-950 shadow-sm focus:ring-cream-500">
                    <span class="ml-2 text-xs font-bold text-charcoal-800">Artikel Published</span>
                </label>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.cms.posts.index') }}" class="px-5 py-2.5 rounded-2xl border border-cream-300 text-xs font-bold text-charcoal-700 hover:bg-cream-100/60 transition">
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
