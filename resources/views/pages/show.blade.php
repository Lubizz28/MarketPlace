@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title . ' — MedinaStyle')
@section('meta_description', Str::limit(strip_tags($page->meta_description ?? $page->content), 155))
@section('canonical_url', route('pages.show', $page->slug))
@section('og_type', 'article')
@section('og_image', asset('images/icons/icon.svg'))

@section('schema')
@php
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $page->title,
                        'item' => route('pages.show', $page->slug),
                    ],
                ],
            ],
            [
                '@type' => 'WebPage',
                'name' => $page->title,
                'url' => route('pages.show', $page->slug),
                'description' => Str::limit(strip_tags($page->meta_description ?? $page->content), 200),
                'dateModified' => optional($page->updated_at)->toAtomString() ?? now()->toAtomString(),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

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
