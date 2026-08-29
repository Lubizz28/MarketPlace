@extends('layouts.app')

@section('title', $post->title . ' — Blog MedinaStyle')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="flex items-center space-x-2">
        <a href="{{ route('blog.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar Artikel</a>
    </div>

    <div class="space-y-4">
        <div class="flex items-center space-x-3 text-xs text-charcoal-500 font-mono">
            <span>{{ $post->published_at ? $post->published_at->format('d F Y') : '-' }}</span>
            <span>•</span>
            <span>Oleh {{ $post->author?->name ?? 'Admin' }}</span>
            <span>•</span>
            <span>Dibaca {{ $post->view_count }}x</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-bold text-charcoal-950 leading-tight">
            {{ $post->title }}
        </h1>
    </div>

    @if($post->thumbnail_path)
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-cream-200">
            <img src="{{ $post->thumbnail_path }}" alt="{{ $post->title }}" class="w-full max-h-[480px] object-cover">
        </div>
    @endif

    <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-xl border border-cream-200/80">
        <div class="prose prose-sm sm:prose-base max-w-none text-charcoal-800 leading-relaxed space-y-4">
            {!! nl2br(e($post->body)) !!}
        </div>
    </div>

    @if($relatedPosts->isNotEmpty())
        <div class="pt-10 border-t border-cream-200 space-y-6">
            <h3 class="font-display font-bold text-xl text-charcoal-950">Artikel Terkait Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rel)
                    <div class="glass-card rounded-2xl overflow-hidden p-4 space-y-2 group border border-cream-200/70">
                        <h4 class="font-display font-bold text-xs text-charcoal-950 group-hover:text-cream-900 line-clamp-2">
                            <a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a>
                        </h4>
                        <p class="text-[10px] text-charcoal-500 line-clamp-2">{{ $rel->excerpt }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
