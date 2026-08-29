@extends('layouts.app')

@section('title', 'Blog & Panduan Busana Muslimah Syar\'i — MedinaStyle')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center max-w-2xl mx-auto space-y-3">
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-cream-800">Inspirasi &amp; Edukasi Syar'i</span>
        <h1 class="text-3xl sm:text-4xl font-display font-bold text-charcoal-950">Jurnal &amp; Artikel Fashion</h1>
        <p class="text-xs text-charcoal-500 leading-relaxed">Temukan inspirasi busana muslimah, tren gamis terkini, tips pemilihan bahan hijab, serta panduan styling syariat elegan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
            <div class="glass-card rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col group border border-cream-200/80">
                <div class="relative h-52 overflow-hidden bg-cream-100">
                    @if($post->thumbnail_path)
                        <img src="{{ $post->thumbnail_path }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-charcoal-950/80 backdrop-blur-md text-cream-200">
                            {{ $post->published_at ? $post->published_at->format('d M Y') : 'Baru' }}
                        </span>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <h3 class="font-display font-bold text-lg text-charcoal-950 group-hover:text-cream-900 transition-colors line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-charcoal-600 leading-relaxed line-clamp-3">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-cream-100 flex items-center justify-between text-[11px] text-charcoal-500">
                        <span class="font-medium">Oleh: {{ $post->author?->name ?? 'Tim Medina' }}</span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="font-bold text-cream-900 hover:text-charcoal-950">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-charcoal-400 font-light italic">
                Belum ada artikel yang diterbitkan saat ini.
            </div>
        @endforelse
    </div>

    <div class="pt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
