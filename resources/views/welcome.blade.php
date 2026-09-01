@extends('layouts.app')

@section('title', 'Koleksi Busana Muslim & Modest Fashion Syar\'i')
@section('meta_description', 'Sulastika Jaya - Butik busana muslim syari, gamis anggun, abaya modern, pashmina voal ultrafine, dan koko kurta eksklusif dengan harga retail, member point reward, dan peluang kemitraan reseller.')
@section('meta_keywords', 'busana muslim, gamis syari, abaya butik, koko kurta, hijab voal, reseller fashion, member rewards, sulastika jaya')
@section('canonical_url', url('/'))
@section('og_type', 'website')
@section('og_image', asset('images/icons/icon.svg'))

@section('schema')
@php
    $welcomeSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Sulastika Jaya — Koleksi Busana Muslim & Modest Fashion Syar\'i',
        'url' => url('/'),
        'description' => 'Butik busana muslim terkurasi dengan standar jahitan butik berkelas, sistem reward loyalitas, dan program kemitraan reseller berkah.',
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => url('/'),
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($welcomeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="space-y-12 sm:space-y-20"
    x-data="{
        flashTimer: { hours: '03', minutes: '45', seconds: '20' },
        resellerMonthlySales: 15,
        avgProductPrice: 350000,
        commissionRate: 0.15,
        init() {
            // Flash sale countdown timer
            let secondsLeft = 3 * 3600 + 45 * 60 + 20;
            setInterval(() => {
                if (secondsLeft > 0) {
                    secondsLeft--;
                    const h = Math.floor(secondsLeft / 3600);
                    const m = Math.floor((secondsLeft % 3600) / 60);
                    const s = secondsLeft % 60;
                    this.flashTimer.hours = String(h).padStart(2, '0');
                    this.flashTimer.minutes = String(m).padStart(2, '0');
                    this.flashTimer.seconds = String(s).padStart(2, '0');
                }
            }, 1000);

            // Trigger analytics view_item_list
            const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
            if (analytics) {
                analytics.viewItemList([
                    @foreach($featuredProducts as $fp)
                        {
                            id: '{{ $fp->id }}',
                            sku: '{{ $fp->sku }}',
                            name: @js($fp->name),
                            price: {{ $fp->getMinPriceFor('retail') }},
                            category: @js($fp->category->name),
                            brand: @js($fp->brand?->name ?? 'Sulastika Jaya')
                        },
                    @endforeach
                ], 'Homepage Featured Spotlight');
            }
        },
        calculateCommission() {
            return this.resellerMonthlySales * this.avgProductPrice * this.commissionRate;
        },
        formatRupiah(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
        },
        trackPromo(name, slot) {
            const analytics = window.SulastikaAnalytics || window.MedinaAnalytics;
            if (analytics) {
                analytics.selectPromotion(name, name, slot);
            }
        }
    }">

    <!-- Hero Haute Couture Banner in Royal Emerald with Gold Specular Glow -->
    <section class="relative bg-emerald-luxury text-white rounded-3xl sm:rounded-[2.5rem] mx-3 sm:mx-6 lg:mx-8 px-5 sm:px-12 lg:px-14 py-12 sm:py-20 shadow-2xl overflow-hidden border border-gold-400/30">
        <!-- Subtle Natural Lattice Pattern -->
        <div class="absolute inset-0 bg-sulastika-pattern opacity-25 pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-5 sm:space-y-6">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 border border-gold-400/30 text-gold-300 text-xs font-medium backdrop-blur-md shadow-xs">
                <svg class="w-3.5 h-3.5 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
                <span class="tracking-[0.2em] uppercase text-[10px] sm:text-xs">Sulastika Jaya &bull; Edisi Syar'i 2026</span>
                <svg class="w-3.5 h-3.5 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-display font-bold tracking-tight leading-[1.15] text-white">
                Keanggunan Syar'i dalam Sentuhan <span class="text-gold-gradient italic font-serif font-normal">Sulastika Jaya</span>.
            </h1>

            <p class="text-cream-200/90 text-xs sm:text-sm leading-relaxed max-w-xl font-light">
                Koleksi gamis sutra jacquard, abaya elegan, pashmina voal premium, dan koko kurta modern. Diciptakan dari material terbaik dengan jahitan butik presisi dan kenyamanan berbusana syari.
            </p>

            <!-- Active Promotional Voucher Callout Badge -->
            <div class="inline-flex items-center space-x-2.5 p-2.5 sm:p-3 bg-emerald-900/90 border border-gold-400/40 rounded-2xl backdrop-blur-md text-xs">
                <span class="px-2 py-0.5 bg-gold-400 text-emerald-950 font-bold rounded-lg text-[10px] tracking-wider uppercase">Voucher Berkah</span>
                <span class="text-gold-100 text-[11px]">Gunakan kode <b class="font-mono text-gold-300">SULASTIKA</b> untuk potongan belanja Rp 50.000!</span>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <a href="{{ route('catalog') }}" @click="trackPromo('Hero_Jelajahi_Katalog', 'hero_main')" class="px-6 sm:px-8 py-3.5 bg-gold-btn text-emerald-950 font-bold rounded-2xl text-center shadow-lg hover:opacity-95 transition-smooth text-xs uppercase tracking-wider">
                    Jelajahi Katalog Lengkap
                </a>
                <a href="{{ route('register', ['type' => 'reseller']) }}" @click="trackPromo('Hero_Gabung_Reseller', 'hero_secondary')" class="px-6 sm:px-8 py-3.5 bg-white/10 hover:bg-white/15 text-gold-200 font-bold rounded-2xl text-center border border-gold-400/30 transition-smooth text-xs uppercase tracking-wider backdrop-blur-md flex items-center justify-center space-x-2">
                    <span>Gabung Reseller</span>
                    <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Ambient Specular Glow Orbs -->
        <div class="absolute -right-16 -top-16 w-96 h-96 rounded-full bg-gold-400/15 blur-[120px] pointer-events-none"></div>
        <div class="absolute right-12 bottom-0 w-80 h-80 rounded-full bg-emerald-700/30 blur-[100px] pointer-events-none"></div>
    </section>

    <!-- FLASH SALE & CAMPAIGN SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-6 border-2 border-gold-300/80 bg-linear-to-r from-gold-50/70 via-white/90 to-emerald-50/50 shadow-lg space-y-4 relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center text-lg font-bold shadow-xs shrink-0">
                        ⚡
                    </div>
                    <div>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-rose-600 text-[9px] sm:text-[10px] uppercase tracking-[0.2em] font-bold">Penawaran Kilat</span>
                            <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                        </div>
                        <h2 class="text-base sm:text-xl font-display font-bold text-charcoal-950">Flash Sale Spesial Hari Ini</h2>
                    </div>
                </div>

                <!-- Interactive Real-time Countdown Timer -->
                <div class="flex items-center space-x-1.5 self-start sm:self-auto">
                    <span class="text-[11px] text-charcoal-600 font-medium mr-1">Berakhir:</span>
                    <div class="flex items-center space-x-1 font-mono">
                        <div class="bg-emerald-950 text-gold-300 px-2 py-1 rounded-lg text-center shadow-xs">
                            <span class="text-xs sm:text-sm font-bold" x-text="flashTimer.hours"></span>
                        </div>
                        <span class="text-emerald-950 font-bold">:</span>
                        <div class="bg-emerald-950 text-gold-300 px-2 py-1 rounded-lg text-center shadow-xs">
                            <span class="text-xs sm:text-sm font-bold" x-text="flashTimer.minutes"></span>
                        </div>
                        <span class="text-emerald-950 font-bold">:</span>
                        <div class="bg-emerald-950 text-gold-300 px-2 py-1 rounded-lg text-center shadow-xs">
                            <span class="text-xs sm:text-sm font-bold" x-text="flashTimer.seconds"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Sale Products Grid (Compact & Professional Proportions) -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4.5 pt-1">
                @foreach($featuredProducts->take(4) as $idx => $fprod)
                    <div class="glass-card rounded-2xl p-3 bg-white/95 border border-cream-300 product-card-hover flex flex-col justify-between space-y-2.5 relative group">
                        <div class="luxury-image-container aspect-luxury-portrait rounded-xl">
                            <img src="{{ $fprod->thumbnail_url }}" alt="{{ $fprod->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 px-2 py-0.5 bg-rose-600 text-white text-[9px] font-bold uppercase tracking-wider rounded-md shadow-xs">
                                Hemat 35%
                            </span>
                            <span class="absolute top-2 right-2 gold-badge text-[8px] font-bold uppercase tracking-wider rounded-md px-1.5 py-0.5">
                                Limited
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div>
                                <span class="text-[9px] uppercase tracking-wider text-emerald-800/80 font-bold block">{{ $fprod->category->name }}</span>
                                <a href="{{ route('product.show', $fprod->slug) }}" class="block font-display font-bold text-xs sm:text-[13px] text-charcoal-950 hover:text-emerald-800 transition-colors line-clamp-1 leading-snug mt-0.5">
                                    {{ $fprod->name }}
                                </a>
                            </div>

                            <div class="flex items-baseline space-x-1.5 flex-wrap">
                                <span class="text-xs sm:text-sm font-bold font-mono text-emerald-950">
                                    Rp {{ number_format($fprod->getMinPriceFor('retail') * 0.65, 0, ',', '.') }}
                                </span>
                                <span class="text-[9px] text-charcoal-400 line-through font-mono">
                                    Rp {{ number_format($fprod->getMinPriceFor('retail'), 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Stock Claimed Progress Bar -->
                            <div class="space-y-1">
                                <div class="w-full bg-cream-200 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-rose-500 to-rose-600 h-full rounded-full" style="width: {{ 70 + ($idx * 6) }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[8px] sm:text-[9px] text-charcoal-500 font-medium">
                                    <span>Klaim {{ 70 + ($idx * 6) }}%</span>
                                    <span class="text-rose-700 font-bold">Sisa {{ 5 - $idx }} pcs</span>
                                </div>
                            </div>

                            <a href="{{ route('product.show', $fprod->slug) }}" class="block w-full py-2 bg-emerald-950 hover:bg-emerald-900 text-gold-200 text-center rounded-xl text-[10px] font-bold uppercase tracking-wider transition-smooth shadow-xs">
                                Beli Sekarang &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Curated Categories Grid -->
    <section id="koleksi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
            <div>
                <span class="text-emerald-800 text-[10px] uppercase tracking-[0.2em] font-bold">Koleksi Terkurasi</span>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-0.5">Kategori Pilihan Sulastika</h2>
            </div>
            <a href="{{ route('catalog') }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('catalog', ['category' => $cat->slug]) }}" class="group glass-card p-4 rounded-2xl border border-cream-300 hover:border-gold-400 product-card-hover text-center space-y-2.5 relative overflow-hidden block">
                    <div class="w-12 h-12 mx-auto rounded-xl bg-emerald-950/5 border border-gold-400/30 flex items-center justify-center text-emerald-900 group-hover:scale-110 group-hover:bg-emerald-950 group-hover:text-gold-300 transition-smooth shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-18l4 6-1 12H9L8 9l4-6zM8 9H4l2 6h2m8-6h4l-2 6h-2"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-charcoal-950 text-xs sm:text-sm truncate">{{ $cat->name }}</h4>
                        <p class="text-[10px] text-charcoal-500 font-light">{{ $cat->products_count ?? 0 }} Koleksi</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Main Products Spotlight with Pagination (Compact & Perfectly Proportioned) -->
    @php
        $displayProducts = isset($products) ? $products : $featuredProducts;
    @endphp
    @if(isset($displayProducts) && $displayProducts->isNotEmpty())
        <section id="koleksi-busana" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                <div>
                    <span class="text-emerald-800 text-[10px] uppercase tracking-[0.2em] font-bold">Sorotan Koleksi</span>
                    <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-0.5">Koleksi Busana Muslim Terfavorit</h2>
                </div>
                <a href="{{ route('catalog') }}" class="text-xs font-bold text-emerald-800 hover:text-emerald-950 hover:underline flex items-center space-x-1">
                    <span>Lihat Seluruh Katalog &rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-3 sm:gap-4.5">
                @foreach($displayProducts as $prod)
                    <div class="group glass-card rounded-2xl overflow-hidden flex flex-col justify-between border border-cream-300 hover:border-gold-400 product-card-hover relative">
                        <!-- Luxury Image Container with Portrait Aspect -->
                        <div class="luxury-image-container aspect-luxury-portrait">
                            <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                            <span class="absolute top-2 left-2 gold-badge text-[8px] sm:text-[9px] font-bold uppercase tracking-wider rounded-md px-2 py-0.5">
                                {{ $prod->category->name }}
                            </span>
                        </div>

                        <!-- Compact Card Details -->
                        <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                            <div>
                                @if($prod->brand)
                                    <span class="text-[9px] uppercase tracking-wider text-emerald-800/80 font-bold block">{{ $prod->brand->name }}</span>
                                @endif
                                <a href="{{ route('product.show', $prod->slug) }}" class="block font-display font-bold text-charcoal-950 text-xs sm:text-[13px] hover:text-emerald-800 transition-colors mt-0.5 line-clamp-2 leading-snug">
                                    {{ $prod->name }}
                                </a>
                            </div>

                            <div class="pt-1.5 border-t border-cream-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[8px] uppercase tracking-wider text-charcoal-400 font-medium block">Mulai dari</span>
                                    <p class="text-xs sm:text-sm font-bold text-charcoal-900 font-mono">
                                        Rp {{ number_format($prod->getMinPriceFor(auth()->user()?->role?->value ?? 'retail'), 0, ',', '.') }}
                                    </p>
                                </div>
                                <a href="{{ route('product.show', $prod->slug) }}" class="w-7 h-7 rounded-lg bg-emerald-950 hover:bg-emerald-900 text-gold-200 flex items-center justify-center transition-smooth shadow-xs" title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Landing Page Products Pagination -->
            @if(method_exists($displayProducts, 'links'))
                <div class="pt-4 flex justify-center">
                    {{ $displayProducts->fragment('koleksi-busana')->links() }}
                </div>
            @endif
        </section>
    @endif

    <!-- RESELLER REFERRAL EARNINGS SIMULATOR -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="glass-dark text-white rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-12 border border-gold-400/30 shadow-2xl relative overflow-hidden">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-10 items-center">
                <div class="lg:col-span-6 space-y-4 sm:space-y-5">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 border border-gold-400/30 text-gold-300 text-[10px] font-bold uppercase tracking-wider">
                        <span>💼 Program Kemitraan Reseller Sulastika Jaya</span>
                    </div>
                    <h2 class="text-xl sm:text-3xl font-display font-bold text-white leading-tight">
                        Raih Komisi Melimpah &amp; Harga Grosir Bersama Sulastika Jaya.
                    </h2>
                    <p class="text-xs sm:text-sm text-cream-200/90 leading-relaxed font-light">
                        Cukup bagikan link referral katalog busana muslim Sulastika Jaya ke keluarga dan jejaring Anda. Setiap pesanan berhasil langsung menambah saldo dompet digital Anda secara otomatis.
                    </p>

                    <!-- Interactive Slider Calculator -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/5 border border-gold-400/20 space-y-3 backdrop-blur-md">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-cream-200">Estimasi Penjualan Bulanan:</span>
                            <span class="font-bold font-mono text-gold-300 text-sm" x-text="resellerMonthlySales + ' Pcs'"></span>
                        </div>
                        <input type="range" min="5" max="100" step="5" x-model="resellerMonthlySales"
                            class="w-full h-2 bg-emerald-950 rounded-lg appearance-none cursor-pointer accent-gold-400">
                        <div class="flex items-center justify-between pt-2 border-t border-white/10 text-xs">
                            <span class="text-gold-200">Estimasi Penghasilan Komisi:</span>
                            <span class="text-base sm:text-lg font-bold font-mono text-gold-300" x-text="formatRupiah(calculateCommission())"></span>
                        </div>
                    </div>

                    <div class="pt-1 flex flex-wrap gap-3">
                        <a href="{{ route('register', ['type' => 'reseller']) }}" class="px-6 sm:px-8 py-3 bg-gold-btn text-emerald-950 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-lg hover:opacity-95 transition-smooth">
                            Daftar Mitra Reseller
                        </a>
                        <a href="{{ route('login') }}" class="px-5 sm:px-6 py-3 bg-white/10 text-gold-200 font-bold rounded-2xl text-xs uppercase tracking-widest hover:bg-white/15 transition-smooth border border-gold-400/30">
                            Masuk Portal
                        </a>
                    </div>
                </div>

                <!-- Reseller Benefits Showcase Cards -->
                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="p-4 rounded-2xl bg-white/5 border border-gold-400/20 space-y-1.5 backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-white/10 text-gold-300 flex items-center justify-center text-sm font-bold">15%</div>
                        <h4 class="font-display font-bold text-xs text-white">Komisi Transparan</h4>
                        <p class="text-[11px] text-cream-200/80 leading-relaxed font-light">Komisi otomatis dihitung per transaksi dan dicatat dalam buku besar ledger aman.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-gold-400/20 space-y-1.5 backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-white/10 text-gold-300 flex items-center justify-center text-sm font-bold">⚡</div>
                        <h4 class="font-display font-bold text-xs text-white">Pencairan Dana Cepat</h4>
                        <p class="text-[11px] text-cream-200/80 leading-relaxed font-light">Tarik saldo dompet ke rekening bank BCA, Mandiri, BRI, BNI secara transparan.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-gold-400/20 space-y-1.5 backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-white/10 text-gold-300 flex items-center justify-center text-sm font-bold">🔗</div>
                        <h4 class="font-display font-bold text-xs text-white">Link Referral Otomatis</h4>
                        <p class="text-[11px] text-cream-200/80 leading-relaxed font-light">Setiap produk memiliki tautan unik reseller yang terintegrasi pelacakan order.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/5 border border-gold-400/20 space-y-1.5 backdrop-blur-md">
                        <div class="w-8 h-8 rounded-xl bg-white/10 text-gold-300 flex items-center justify-center text-sm font-bold">💎</div>
                        <h4 class="font-display font-bold text-xs text-white">Koleksi Busana Butik</h4>
                        <p class="text-[11px] text-cream-200/80 leading-relaxed font-light">Akses katalog foto produk syar'i resolusi tinggi siap dipromosikan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MEMBER LOYALTY TIERS & REWARDS PRESENTATION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
            <div>
                <span class="text-emerald-800 text-[10px] uppercase tracking-[0.2em] font-bold">Program Loyalitas Sulastika</span>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-0.5">Tingkatan Keanggotaan &amp; Reward Poin</h2>
            </div>
            <p class="text-xs text-charcoal-500 max-w-md font-light">Kumpulkan poin di setiap pesanan dan nikmati hak istimewa di setiap kenaikan tier.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Bronze Tier -->
            <div class="glass-card p-5 rounded-2xl border border-cream-300 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold font-display uppercase tracking-wider text-charcoal-900">Bronze Member</span>
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[9px] font-bold rounded-full font-mono">Tier Awal</span>
                </div>
                <div class="text-xl font-bold font-mono text-emerald-950">1x Poin</div>
                <ul class="space-y-1.5 text-xs text-charcoal-600 font-light border-t border-cream-200 pt-2.5">
                    <li class="flex items-center space-x-1.5"><span>✓ 1% cashback poin</span></li>
                    <li class="flex items-center space-x-1.5"><span>✓ Voucher selamat datang</span></li>
                </ul>
            </div>

            <!-- Silver Tier -->
            <div class="glass-card p-5 rounded-2xl border border-cream-300 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold font-display uppercase tracking-wider text-charcoal-900">Silver Member</span>
                    <span class="px-2 py-0.5 bg-slate-200 text-slate-800 text-[9px] font-bold rounded-full font-mono">Total > 1 Jt</span>
                </div>
                <div class="text-xl font-bold font-mono text-emerald-950">1.25x Poin</div>
                <ul class="space-y-1.5 text-xs text-charcoal-600 font-light border-t border-cream-200 pt-2.5">
                    <li class="flex items-center space-x-1.5"><span>✓ 1.25% cashback poin</span></li>
                    <li class="flex items-center space-x-1.5"><span>✓ Subsidi ongkir bulanan</span></li>
                </ul>
            </div>

            <!-- Gold Tier -->
            <div class="glass-card p-5 rounded-2xl border-2 border-gold-400 bg-gold-50/40 shadow-sm space-y-3 relative">
                <div class="absolute -top-2.5 right-3 bg-emerald-950 text-gold-300 px-2.5 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider">Favorit</div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold font-display uppercase tracking-wider text-charcoal-900">Gold Member</span>
                    <span class="px-2 py-0.5 bg-gold-200 text-gold-950 text-[9px] font-bold rounded-full font-mono">Total > 3 Jt</span>
                </div>
                <div class="text-xl font-bold font-mono text-emerald-950">1.5x Poin</div>
                <ul class="space-y-1.5 text-xs text-charcoal-800 font-medium border-t border-cream-200 pt-2.5">
                    <li class="flex items-center space-x-1.5"><span>✦ 1.5% cashback poin</span></li>
                    <li class="flex items-center space-x-1.5"><span>✦ Gratis ongkir berkala</span></li>
                </ul>
            </div>

            <!-- Platinum Tier -->
            <div class="glass-dark text-white p-5 rounded-2xl border border-gold-400/40 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold font-display uppercase tracking-wider text-white">Platinum VIP</span>
                    <span class="px-2 py-0.5 bg-gold-400 text-emerald-950 text-[9px] font-bold rounded-full font-mono">Total > 7 Jt</span>
                </div>
                <div class="text-xl font-bold font-mono text-gold-300">2x Poin</div>
                <ul class="space-y-1.5 text-xs text-cream-200 font-light border-t border-emerald-900 pt-2.5">
                    <li class="flex items-center space-x-1.5"><span>✦ 2.0% cashback poin ganda</span></li>
                    <li class="flex items-center space-x-1.5"><span>✦ Bebas ongkir tanpa batas</span></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Trust & Quality Pillars -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-2xl sm:rounded-3xl p-6 sm:p-10 border border-cream-200/90 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-start space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-950/5 border border-gold-400/30 flex items-center justify-center text-emerald-900 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                </div>
                <div class="space-y-0.5">
                    <h4 class="font-display font-bold text-charcoal-950 text-xs sm:text-sm">Standar Butik Jahitan Rapi</h4>
                    <p class="text-[11px] text-charcoal-500 leading-relaxed font-light">Pola presisi dan material kain adem berkualitas.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-950/5 border border-gold-400/30 flex items-center justify-center text-emerald-900 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="space-y-0.5">
                    <h4 class="font-display font-bold text-charcoal-950 text-xs sm:text-sm">Jaminan 100% Syar'i</h4>
                    <p class="text-[11px] text-charcoal-500 leading-relaxed font-light">Koleksi busana mematuhi standar busana syariat yang anggun.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-950/5 border border-gold-400/30 flex items-center justify-center text-emerald-900 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.948c0-.621-.504-1.125-1.125-1.125H4.125C3.504 5.5 3 6.004 3 6.625v8.25c0 .621.504 1.125 1.125 1.125h1.5"/></svg>
                </div>
                <div class="space-y-0.5">
                    <h4 class="font-display font-bold text-charcoal-950 text-xs sm:text-sm">Pengiriman Kilat Aman</h4>
                    <p class="text-[11px] text-charcoal-500 leading-relaxed font-light">Ekspedisi ke seluruh Indonesia dengan pelacakan resi otomatis.</p>
                </div>
            </div>

            <div class="flex items-start space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-950/5 border border-gold-400/30 flex items-center justify-center text-emerald-900 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3.84-3.091c-.422-.016-.84-.038-1.258-.066a9.92 9.92 0 01-3.652-.962m10.25-7.528A9.972 9.972 0 0012 5.25c-5.523 0-10 4.477-10 10 0 1.688.42 3.278 1.16 4.673l-1.16 4.327 4.545-1.136A9.957 9.957 0 0012 25.25c5.523 0 10-4.477 10-10 0-1.848-.5-3.578-1.375-5.064"/></svg>
                </div>
                <div class="space-y-0.5">
                    <h4 class="font-display font-bold text-charcoal-950 text-xs sm:text-sm">Layanan Pelanggan Ramah</h4>
                    <p class="text-[11px] text-charcoal-500 leading-relaxed font-light">Konsultasi ukuran &amp; rekomendasi busana siap melayani Anda.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

