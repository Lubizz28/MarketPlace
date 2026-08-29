@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title . ' — MedinaStyle')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="text-center space-y-3">
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-cream-800">Informasi &amp; Panduan</span>
        <h1 class="text-3xl sm:text-4xl font-display font-bold text-charcoal-950">{{ $page->title }}</h1>
        <p class="text-xs text-charcoal-400 font-mono">Terakhir diperbarui: {{ $page->updated_at->format('d F Y') }}</p>
    </div>

    <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-xl border border-cream-200/80">
        <div class="prose prose-sm max-w-none text-charcoal-800 leading-relaxed space-y-4">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</div>
@endsection
