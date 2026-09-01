@extends('layouts.app')

@section('title', $product->name . ' — Sulastika Jaya')
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 155))
@section('meta_keywords', $product->name . ', ' . $product->category->name . ', busana muslim, sulastika jaya')
@section('canonical_url', route('product.show', $product->slug))
@section('og_type', 'product')
@section('og_image', $product->thumbnail_url)

@section('extra_og')
    <meta property="product:price:amount" content="{{ $product->getMinPriceFor('retail') }}">
    <meta property="product:price:currency" content="IDR">
    <meta property="product:brand" content="{{ $product->brand?->name ?? 'Sulastika Jaya' }}">
    <meta property="product:availability" content="{{ $product->total_stock > 0 ? 'in stock' : 'out of stock' }}">
    <meta property="product:retailer_item_id" content="{{ $product->sku }}">
@endsection

@section('schema')
@php
    $productSchema = [
        '@context' => 'https://schema.org/',
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
                        'name' => $product->category->name,
                        'item' => route('catalog', ['category' => $product->category->slug]),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $product->name,
                        'item' => route('product.show', $product->slug),
                    ],
                ],
            ],
            [
                '@type' => 'Product',
                'name' => $product->name,
                'image' => [
                    $product->thumbnail_url,
                ],
                'description' => Str::limit(strip_tags($product->short_description ?? $product->description), 200),
                'sku' => $product->sku,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $product->brand?->name ?? 'Sulastika Jaya',
                ],
                'category' => $product->category->name,
                'offers' => [
                    '@type' => 'AggregateOffer',
                    'url' => route('product.show', $product->slug),
                    'priceCurrency' => 'IDR',
                    'lowPrice' => (string) $product->getMinPriceFor('retail'),
                    'highPrice' => (string) $product->getMaxPriceFor('retail'),
                    'offerCount' => (string) $product->variants->count(),
                    'availability' => $product->total_stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10 pt-3"
    x-data="{
        selectedVariant: {{ $product->variants->first()?->id ?? 'null' }},
        selectedVariantName: '{{ $product->variants->first()?->name ?? '' }}',
        selectedStock: {{ $product->variants->first()?->stock ?? 0 }},
        retailPrice: {{ $product->variants->first()?->getPriceFor('retail') ?? 0 }},
        memberPrice: {{ $product->variants->first()?->getPriceFor('member') ?? 0 }},
        resellerPrice: {{ $product->variants->first()?->getPriceFor('reseller') ?? 0 }},
        activeImage: '{{ $product->thumbnail_url }}',
        quantity: 1,
        activeTab: 'desc',
        referralCopied: false,
        flashTimer: { hours: '04', minutes: '28', seconds: '50' },
        variants: @js($product->variants->map(fn ($v) => [
            'id' => $v->id,
            'name' => $v->name,
            'color' => $v->color_name,
            'size' => $v->size,
            'stock' => $v->stock,
            'retail_price' => $v->getPriceFor('retail'),
            'member_price' => $v->getPriceFor('member'),
            'reseller_price' => $v->getPriceFor('reseller'),
        ])),
        init() {
            this.startFlashCountdown();
            const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
            if (analytics) {
                analytics.viewItem({
                    id: '{{ $product->id }}',
                    sku: '{{ $product->sku }}',
                    name: @js($product->name),
                    category: @js($product->category->name),
                    brand: @js($product->brand?->name ?? 'Sulastika Jaya'),
                    price: this.retailPrice,
                    variant: this.selectedVariantName
                });
            }
        },
        startFlashCountdown() {
            let totalSeconds = 4 * 3600 + 28 * 60 + 50;
            setInterval(() => {
                if (totalSeconds > 0) {
                    totalSeconds--;
                    const h = Math.floor(totalSeconds / 3600);
                    const m = Math.floor((totalSeconds % 3600) / 60);
                    const s = totalSeconds % 60;
                    this.flashTimer.hours = String(h).padStart(2, '0');
                    this.flashTimer.minutes = String(m).padStart(2, '0');
                    this.flashTimer.seconds = String(s).padStart(2, '0');
                }
            }, 1000);
        },
        selectVariant(v) {
            this.selectedVariant = v.id;
            this.selectedVariantName = v.name;
            this.selectedStock = v.stock;
            this.retailPrice = v.retail_price;
            this.memberPrice = v.member_price;
            this.resellerPrice = v.reseller_price;
            if (this.quantity > v.stock) {
                this.quantity = Math.max(1, v.stock);
            }
        },
        copyReferral(url, code) {
            navigator.clipboard.writeText(url).then(() => {
                this.referralCopied = true;
                const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
                if (analytics) {
                    analytics.shareReferral(code, url, 'clipboard');
                }
                setTimeout(() => { this.referralCopied = false; }, 3000);
            });
        },
        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        },
        handleAddToCart() {
            const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
            if (analytics) {
                analytics.addToCart({
                    id: '{{ $product->id }}',
                    sku: '{{ $product->sku }}',
                    name: @js($product->name),
                    price: this.retailPrice,
                    variant: this.selectedVariantName
                }, this.quantity);
            }
        }
    }">

    <!-- Product Breadcrumbs -->
    <nav class="flex items-center space-x-2 text-xs text-charcoal-500 font-light" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-emerald-950">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-emerald-950">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-charcoal-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start">
        
        <!-- Left: Image Gallery (5 Cols - Compact & Professional Proportions) -->
        <div class="lg:col-span-5 space-y-3">
            <div class="luxury-image-container aspect-luxury-portrait max-h-[480px] sm:max-h-[520px] rounded-2xl shadow-xl border border-cream-300 relative group">
                <img :src="activeImage" alt="{{ $product->name }}" fetchpriority="high" decoding="async" class="w-full h-full object-cover">
                <span class="absolute top-3 left-3 gold-badge text-[9px] font-bold uppercase tracking-wider rounded-md px-2.5 py-1">
                    {{ $product->category->name }}
                </span>
                <span class="absolute top-3 right-3 px-2.5 py-1 bg-rose-600/95 backdrop-blur-md text-white text-[9px] font-bold uppercase tracking-wider rounded-md shadow-xs flex items-center space-x-1">
                    <span>⚡ FLASH SALE</span>
                </span>
            </div>

            <!-- Thumbnail Selector (Portrait Proportions) -->
            @if($product->images->count() > 1)
                <div class="flex items-center space-x-2.5 overflow-x-auto pb-1 scrollbar-none">
                    @foreach($product->images as $img)
                        <button type="button" @click="activeImage = '{{ $img->image_path }}'"
                            class="w-14 h-18 rounded-xl overflow-hidden border-2 transition-smooth shrink-0 bg-cream-100/80"
                            :class="activeImage === '{{ $img->image_path }}' ? 'border-emerald-950 ring-2 ring-gold-400 shadow-xs' : 'border-transparent opacity-70 hover:opacity-100'">
                            <img src="{{ $img->image_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Value Proposition Badges -->
            <div class="grid grid-cols-3 gap-2 pt-2">
                <div class="glass-card p-2 rounded-xl text-center border border-cream-200">
                    <span class="text-emerald-800 text-xs block">✨</span>
                    <span class="text-[9px] font-bold text-charcoal-900 block leading-tight mt-0.5">100% Original</span>
                    <span class="text-[8px] text-charcoal-500 block">Standar Butik</span>
                </div>
                <div class="glass-card p-2 rounded-xl text-center border border-cream-200">
                    <span class="text-emerald-800 text-xs block">🛡️</span>
                    <span class="text-[9px] font-bold text-charcoal-900 block leading-tight mt-0.5">Garansi 7 Hari</span>
                    <span class="text-[8px] text-charcoal-500 block">Tukar Ukuran</span>
                </div>
                <div class="glass-card p-2 rounded-xl text-center border border-cream-200">
                    <span class="text-emerald-800 text-xs block">🚚</span>
                    <span class="text-[9px] font-bold text-charcoal-900 block leading-tight mt-0.5">Kirim Kilat</span>
                    <span class="text-[8px] text-charcoal-500 block">Se-Nusantara</span>
                </div>
            </div>

            <!-- Referral Share Box for Resellers -->
            @if(auth()->check() && auth()->user()->isReseller())
                @php
                    $refCode = auth()->user()->resellerProfile?->code ?? ('RES-' . auth()->user()->id);
                    $refUrl = route('product.show', ['slug' => $product->slug, 'ref' => $refCode]);
                @endphp
                <div class="glass-card p-4 rounded-2xl border border-gold-400/40 bg-gold-50/40 space-y-2.5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-900 text-gold-300 flex items-center justify-center text-[10px] font-bold">💼</span>
                            <span class="text-xs font-bold text-emerald-950 uppercase tracking-wider">Link Referral Reseller</span>
                        </div>
                        <span class="text-[10px] font-bold text-gold-900 bg-gold-200/80 px-2 py-0.5 rounded-full font-mono">Kode: {{ $refCode }}</span>
                    </div>
                    <p class="text-[11px] text-emerald-950 leading-relaxed font-light">
                        Bagikan link ini ke pembeli Anda. Dapatkan komisi instan otomatis masuk ke dompet saldo saat pesanan selesai.
                    </p>
                    <div class="flex items-center space-x-2">
                        <input type="text" readonly value="{{ $refUrl }}" class="w-full text-[11px] bg-white border border-gold-300 rounded-xl px-2.5 py-1.5 text-charcoal-700 font-mono select-all">
                        <button type="button" @click="copyReferral('{{ $refUrl }}', '{{ $refCode }}')"
                            class="px-3.5 py-1.5 bg-emerald-950 hover:bg-emerald-900 text-gold-200 text-xs font-bold rounded-xl transition-smooth shrink-0 shadow-xs">
                            <span x-text="referralCopied ? 'Tersalin! ✓' : 'Salin'"></span>
                        </button>
                    </div>
                </div>
            @else
                <div class="glass-card p-3.5 rounded-2xl border border-cream-300 bg-cream-100/40 flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-800 block">Kemitraan Reseller</span>
                        <h4 class="text-xs font-bold text-charcoal-950">Ingin Komisi Dari Produk Ini?</h4>
                        <p class="text-[10px] text-charcoal-500 font-light">Dapatkan link referral &amp; harga grosir dengan bergabung Reseller Sulastika.</p>
                    </div>
                    <a href="{{ route('register', ['type' => 'reseller']) }}" class="px-3.5 py-2 bg-emerald-950 hover:bg-emerald-900 text-gold-200 font-bold rounded-xl text-[10px] uppercase tracking-wider transition-smooth shrink-0 border border-gold-400/30">
                        Gabung
                    </a>
                </div>
            @endif
        </div>

        <!-- Right: Product Info & Buy Action (7 Cols) -->
        <div class="lg:col-span-7 space-y-4 sm:space-y-5">
            
            <!-- Category & Title -->
            <div class="space-y-1.5">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">{{ $product->category->name }}</span>
                    @if($product->brand)
                        <span class="text-xs text-charcoal-400">&bull;</span>
                        <span class="text-xs font-medium text-charcoal-600">{{ $product->brand->name }}</span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-display font-bold text-charcoal-950 leading-tight">
                    {{ $product->name }}
                </h1>
                <p class="text-[11px] text-charcoal-500 font-mono">SKU: {{ $product->sku }}</p>
            </div>

            <!-- Campaign & Flash Sale Countdown Banner -->
            <div class="bg-linear-to-r from-emerald-950 to-emerald-900 text-white p-3 sm:p-3.5 rounded-2xl border border-gold-400/30 shadow-md flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center text-white shadow-xs shrink-0">
                        <span class="text-sm">⚡</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-gold-300 uppercase tracking-widest block">Flash Sale Sulastika</span>
                        <p class="text-[11px] text-cream-100 font-medium">Penawaran Terbatas Hari Ini</p>
                    </div>
                </div>

                <div class="flex items-center space-x-1 font-mono text-xs">
                    <div class="bg-white/10 px-1.5 py-0.5 rounded-lg text-center">
                        <span class="font-bold text-gold-300 text-xs" x-text="flashTimer.hours"></span>
                        <span class="text-[7px] block text-cream-300">JAM</span>
                    </div>
                    <span class="font-bold text-gold-400">:</span>
                    <div class="bg-white/10 px-1.5 py-0.5 rounded-lg text-center">
                        <span class="font-bold text-gold-300 text-xs" x-text="flashTimer.minutes"></span>
                        <span class="text-[7px] block text-cream-300">MNT</span>
                    </div>
                    <span class="font-bold text-gold-400">:</span>
                    <div class="bg-white/10 px-1.5 py-0.5 rounded-lg text-center">
                        <span class="font-bold text-gold-300 text-xs" x-text="flashTimer.seconds"></span>
                        <span class="text-[7px] block text-cream-300">DTK</span>
                    </div>
                </div>
            </div>

            <!-- Multi-Tier Pricing Display Card in Frosted Glass -->
            <div class="glass-card p-4 sm:p-5 rounded-2xl space-y-2.5 border-2 border-gold-300/80 shadow-xs">
                <div class="flex items-baseline space-x-2.5 flex-wrap gap-y-1">
                    @if(auth()->check() && auth()->user()->isReseller())
                        <span class="text-2xl sm:text-3xl font-display font-bold text-emerald-950 font-mono" x-text="formatRupiah(resellerPrice)"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-emerald-100 text-emerald-950 rounded-full border border-emerald-300">Harga Grosir Reseller</span>
                        <span class="text-xs text-charcoal-400 line-through font-mono" x-text="formatRupiah(retailPrice)"></span>
                    @elseif(auth()->check() && auth()->user()->isMember())
                        <span class="text-2xl sm:text-3xl font-display font-bold text-emerald-950 font-mono" x-text="formatRupiah(memberPrice)"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-gold-200 text-gold-950 rounded-full border border-gold-300">Harga Spesial Member</span>
                        <span class="text-xs text-charcoal-400 line-through font-mono" x-text="formatRupiah(retailPrice)"></span>
                    @else
                        <span class="text-2xl sm:text-3xl font-display font-bold text-emerald-950 font-mono" x-text="formatRupiah(retailPrice)"></span>
                    @endif
                </div>

                <!-- Loyalty Rewards Point Presentation -->
                <div class="pt-2 border-t border-cream-200/80 flex items-center justify-between text-xs text-charcoal-700">
                    <div class="flex items-center space-x-1.5">
                        <span class="text-emerald-800 font-bold">✦ Reward Loyalitas:</span>
                        <span>Dapatkan estimasi <b class="text-emerald-950 font-mono font-bold">+{{ number_format($product->getMinPriceFor('retail') * 0.01, 0, ',', '.') }} Poin Sulastika</b></span>
                    </div>
                    <a href="{{ route('member.points.index') }}" class="text-[11px] text-emerald-900 underline font-medium">Pelajari &rarr;</a>
                </div>

                <!-- Privilege Incentive Prompt for Guests -->
                @guest
                    <div class="pt-1.5 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-charcoal-600 gap-1 border-t border-cream-100">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-800 font-bold">✦ Hemat Belanja:</span>
                            <span>Dapatkan diskon member <b class="font-mono text-emerald-950" x-text="formatRupiah(memberPrice)"></b>!</span>
                        </div>
                        <a href="{{ route('register') }}" class="font-bold text-emerald-950 underline shrink-0">Daftar Member &rarr;</a>
                    </div>
                @endguest
            </div>

            <!-- Variant Selector (Clean Compact Pills) -->
            @if($product->variants->isNotEmpty())
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-charcoal-800">
                            Pilih Varian: <span class="font-normal normal-case text-charcoal-600" x-text="selectedVariantName"></span>
                        </label>
                        <span class="text-xs font-medium" :class="selectedStock > 0 ? 'text-emerald-800 font-semibold' : 'text-rose-600 font-bold'">
                            <span x-text="selectedStock > 0 ? 'Tersedia: ' + selectedStock + ' pcs' : 'Stok Habis'"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <template x-for="v in variants" :key="v.id">
                            <button type="button" @click="selectVariant(v)"
                                :disabled="v.stock <= 0"
                                :class="{
                                    'bg-emerald-950 text-gold-200 border-gold-400 ring-2 ring-gold-400 shadow-sm': selectedVariant === v.id,
                                    'bg-white/90 border-cream-300 text-charcoal-800 hover:border-gold-400': selectedVariant !== v.id && v.stock > 0,
                                    'bg-cream-100/40 border-cream-200 text-charcoal-300 cursor-not-allowed line-through': v.stock <= 0
                                }"
                                class="p-2.5 rounded-xl border text-left transition-smooth flex flex-col justify-between space-y-0.5">
                                <span class="font-semibold text-xs truncate" x-text="v.name"></span>
                                <span class="text-[10px] font-mono opacity-80" x-text="formatRupiah(v.retail_price)"></span>
                            </button>
                        </template>
                    </div>
                </div>
            @endif

            <!-- Quantity & Actions (Desktop Form) -->
            <form method="POST" action="{{ route('cart.add') }}" @submit="handleAddToCart" class="space-y-3 pt-1">
                @csrf
                <input type="hidden" name="variant_id" :value="selectedVariant">
                <input type="hidden" name="quantity" :value="quantity">

                <div class="flex items-center space-x-3">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center space-x-2 bg-white/95 border border-cream-300 rounded-xl p-1 shadow-xs">
                        <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="w-8 h-8 rounded-lg flex items-center justify-center text-charcoal-700 hover:bg-cream-100 font-bold text-sm transition-smooth">-</button>
                        <span class="w-7 text-center text-xs font-bold font-mono text-charcoal-950" x-text="quantity"></span>
                        <button type="button" @click="quantity = Math.min(selectedStock, quantity + 1)" :disabled="quantity >= selectedStock" class="w-8 h-8 rounded-lg flex items-center justify-center text-charcoal-700 hover:bg-cream-100 font-bold text-sm transition-smooth disabled:opacity-30">+</button>
                    </div>

                    <!-- Add to Cart CTA Button -->
                    <button type="submit" :disabled="selectedStock <= 0"
                        class="flex-1 py-3 px-6 bg-emerald-950 hover:bg-emerald-900 text-gold-200 font-bold rounded-xl text-xs uppercase tracking-wider shadow-md border border-gold-400/40 transition-smooth hover:border-gold-300 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span x-text="selectedStock > 0 ? '+ Tambah ke Keranjang' : 'Stok Habis'"></span>
                    </button>
                </div>
            </form>

            <!-- Product Details Tabs -->
            <div class="pt-4 border-t border-cream-200/90 space-y-3">
                <div class="flex items-center space-x-5 border-b border-cream-200 pb-2 text-xs font-bold uppercase tracking-wider">
                    <button type="button" @click="activeTab = 'desc'" :class="activeTab === 'desc' ? 'text-emerald-950 border-b-2 border-emerald-950 pb-2' : 'text-charcoal-400 hover:text-charcoal-700'">Deskripsi</button>
                    <button type="button" @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'text-emerald-950 border-b-2 border-emerald-950 pb-2' : 'text-charcoal-400 hover:text-charcoal-700'">Garansi &amp; Pengiriman</button>
                </div>

                <div x-show="activeTab === 'desc'" class="text-xs text-charcoal-600 leading-relaxed font-light space-y-2">
                    <p>{{ $product->description ?? $product->short_description }}</p>
                    <div class="pt-1 grid grid-cols-2 gap-2 text-[11px] text-charcoal-700 font-medium">
                        <div>&bull; Berat: {{ $product->weight_grams }} gram</div>
                        <div>&bull; Kategori: {{ $product->category->name }}</div>
                    </div>
                </div>

                <div x-show="activeTab === 'shipping'" x-cloak class="text-xs text-charcoal-600 leading-relaxed font-light space-y-1.5">
                    <p>&bull; <b>Garansi Keaslian:</b> Produk 100% original berstandar butik syar'i Sulastika Jaya.</p>
                    <p>&bull; <b>Pengiriman:</b> Dukungan ekspedisi kilat terpercaya ke seluruh pelosok Nusantara.</p>
                    <p>&bull; <b>Retur 7 Hari:</b> Garansi penukaran ukuran jika terjadi ketidaksesuaian size chart.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Products (Compact & Proportional Grid) -->
    @if($relatedProducts->isNotEmpty())
        <div class="pt-8 border-t border-cream-200/90 space-y-4">
            <h2 class="text-lg sm:text-xl font-display font-bold text-charcoal-950">Koleksi Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($relatedProducts as $rel)
                    <div class="glass-card p-3 rounded-2xl space-y-2 hover:border-gold-400 product-card-hover group">
                        <div class="luxury-image-container aspect-luxury-portrait rounded-xl">
                            <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-display font-bold text-xs text-charcoal-950 truncate">{{ $rel->name }}</h4>
                        <p class="text-xs font-mono font-bold text-charcoal-900">Rp {{ number_format($rel->getMinPriceFor('retail'), 0, ',', '.') }}</p>
                        <a href="{{ route('product.show', $rel->slug) }}" class="block py-1.5 text-center bg-cream-200/80 hover:bg-cream-300 text-charcoal-900 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-smooth">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
