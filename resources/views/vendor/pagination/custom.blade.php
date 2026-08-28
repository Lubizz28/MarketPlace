@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between py-4">
        <!-- Mobile Simple Previous/Next -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-xs font-bold text-charcoal-400 bg-cream-100/60 rounded-2xl border border-cream-200 cursor-not-allowed">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-xs font-bold text-charcoal-900 bg-white/80 rounded-2xl border border-cream-300 shadow-xs hover:bg-cream-100 transition-smooth">
                    &larr; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-xs font-bold text-charcoal-900 bg-white/80 rounded-2xl border border-cream-300 shadow-xs hover:bg-cream-100 transition-smooth">
                    Selanjutnya &rarr;
                </a>
            @else
                <span class="px-4 py-2 text-xs font-bold text-charcoal-400 bg-cream-100/60 rounded-2xl border border-cream-200 cursor-not-allowed">
                    Selanjutnya &rarr;
                </span>
            @endif
        </div>

        <!-- Desktop Pagination with Numbers -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-charcoal-500 font-light">
                    Menampilkan <span class="font-bold text-charcoal-900 font-mono">{{ $paginator->firstItem() ?? 0 }}</span> s/d <span class="font-bold text-charcoal-900 font-mono">{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-bold text-charcoal-900 font-mono">{{ $paginator->total() }}</span> produk
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-2xl shadow-xs space-x-1.5 p-1 bg-cream-100/80 backdrop-blur-md border border-cream-200/90">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs text-charcoal-300 cursor-not-allowed" aria-hidden="true">&lsaquo;</span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-charcoal-700 hover:bg-white hover:text-charcoal-950 transition-smooth" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-charcoal-400">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold font-mono bg-charcoal-950 text-cream-200 shadow-sm">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold font-mono text-charcoal-700 hover:bg-white hover:text-charcoal-950 transition-smooth" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-charcoal-700 hover:bg-white hover:text-charcoal-950 transition-smooth" aria-label="{{ __('pagination.next') }}">&rsaquo;</a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs text-charcoal-300 cursor-not-allowed" aria-hidden="true">&rsaquo;</span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
