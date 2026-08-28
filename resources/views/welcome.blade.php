@extends('layouts.app')

@section('title', 'Koleksi Busana Muslim & Modest Fashion Haute Couture')

@section('content')
<div class="space-y-16 sm:space-y-24">
    <!-- Hero Haute Couture Banner in Velvet Charcoal with Cream Specular Glow -->
    <section class="relative bg-charcoal-luxury text-white rounded-[2.5rem] mx-4 sm:mx-6 lg:mx-8 px-6 sm:px-14 py-16 sm:py-28 shadow-2xl overflow-hidden border border-cream-400/20">
        <!-- Subtle Natural Linen Lattice Pattern -->
        <div class="absolute inset-0 bg-cream-pattern opacity-20 pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-7">
            <div class="inline-flex items-center space-x-2.5 px-4 py-1.5 rounded-full bg-white/10 border border-cream-300/30 text-cream-300 text-xs font-medium backdrop-blur-md shadow-xs">
                <svg class="w-3.5 h-3.5 text-cream-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
                <span class="tracking-[0.2em] uppercase text-[10px] sm:text-xs">Edisi Mahakarya 2026</span>
                <svg class="w-3.5 h-3.5 text-cream-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-display font-bold tracking-tight leading-[1.12] text-white">
                Keanggunan Syar'i dalam Sentuhan <span class="text-cream-gradient italic font-serif font-normal">Haute Couture</span>.
            </h1>

            <p class="text-cream-200/90 text-xs sm:text-sm leading-relaxed max-w-xl font-light">
                Koleksi gamis sutra jacquard, abaya bordir tangan, pashmina voal premium, dan koko kurta modern. Diciptakan dari material terbaik dengan potongan yang menjunjung tinggi kesopanan dan kenyamanan berbusana.
            </p>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-3">
                <a href="#koleksi" class="px-8 py-4 bg-cream-btn text-charcoal-950 font-bold rounded-2xl text-center shadow-lg hover:opacity-95 transition-smooth text-xs uppercase tracking-widest">
                    Jelajahi Koleksi
                </a>
                <a href="{{ route('register', ['type' => 'reseller']) }}" class="px-8 py-4 bg-white/10 hover:bg-white/15 text-cream-200 font-bold rounded-2xl text-center border border-cream-400/30 transition-smooth text-xs uppercase tracking-widest backdrop-blur-md flex items-center justify-center space-x-2">
                    <span>Gabung Reseller</span>
                    <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Ambient Specular Glow Orbs -->
        <div class="absolute -right-16 -top-16 w-96 h-96 rounded-full bg-cream-400/10 blur-[130px] pointer-events-none"></div>
        <div class="absolute right-12 bottom-0 w-80 h-80 rounded-full bg-charcoal-700/30 blur-[110px] pointer-events-none"></div>
    </section>

    <!-- Curated Categories Grid in Frosted Cream Glass Cards -->
    <section id="koleksi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-cream-700 text-[10px] uppercase tracking-[0.25em] font-bold">Koleksi Terkurasi</span>
                <h2 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1">Busana Pilihan Terbaik</h2>
            </div>
            <p class="text-xs text-charcoal-500 max-w-md font-light">Karakter bahan adem, tidak terawang, dan dirancang khusus untuk memenuhi standar busana muslim kontemporer.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            <!-- Category 1: Gamis & Abaya -->
            <div class="group glass-card p-6 sm:p-8 rounded-3xl hover:border-cream-400 hover:shadow-xl transition-smooth text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 group-hover:scale-110 group-hover:bg-charcoal-950 group-hover:text-cream-300 transition-smooth shadow-xs">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m0-18l4 6-1 12H9L8 9l4-6zM8 9H4l2 6h2m8-6h4l-2 6h-2"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-bold text-charcoal-950 text-sm sm:text-base">Gamis &amp; Abaya</h4>
                    <p class="text-[11px] text-charcoal-500 mt-1 font-light">Silk, Jacquard &amp; Ceruty</p>
                </div>
                <div class="pt-1">
                    <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-cream-800 uppercase tracking-widest group-hover:underline">
                        <span>Lihat Varian</span>
                        <svg class="w-3 h-3 text-cream-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                    </span>
                </div>
            </div>

            <!-- Category 2: Hijab & Khimar -->
            <div class="group glass-card p-6 sm:p-8 rounded-3xl hover:border-cream-400 hover:shadow-xl transition-smooth text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 group-hover:scale-110 group-hover:bg-charcoal-950 group-hover:text-cream-300 transition-smooth shadow-xs">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a6 6 0 00-6 6v3c0 4.418 2.686 8 6 8s6-3.582 6-8V9a6 6 0 00-6-6zm0 5a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-bold text-charcoal-950 text-sm sm:text-base">Hijab &amp; Khimar</h4>
                    <p class="text-[11px] text-charcoal-500 mt-1 font-light">Voal Ultrafine &amp; Silk Pashmina</p>
                </div>
                <div class="pt-1">
                    <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-cream-800 uppercase tracking-widest group-hover:underline">
                        <span>Lihat Varian</span>
                        <svg class="w-3 h-3 text-cream-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                    </span>
                </div>
            </div>

            <!-- Category 3: Baju Koko & Kurta -->
            <div class="group glass-card p-6 sm:p-8 rounded-3xl hover:border-cream-400 hover:shadow-xl transition-smooth text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 group-hover:scale-110 group-hover:bg-charcoal-950 group-hover:text-cream-300 transition-smooth shadow-xs">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3l3 2 3-2 3 3-2 3v12H8V9L6 6l3-3zm3 4v6m-1-4h2"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-bold text-charcoal-950 text-sm sm:text-base">Baju Koko &amp; Kurta</h4>
                    <p class="text-[11px] text-charcoal-500 mt-1 font-light">Katun Toyobo &amp; Linen Mewah</p>
                </div>
                <div class="pt-1">
                    <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-cream-800 uppercase tracking-widest group-hover:underline">
                        <span>Lihat Varian</span>
                        <svg class="w-3 h-3 text-cream-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                    </span>
                </div>
            </div>

            <!-- Category 4: Mukena Sutra -->
            <div class="group glass-card p-6 sm:p-8 rounded-3xl hover:border-cream-400 hover:shadow-xl transition-smooth text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 group-hover:scale-110 group-hover:bg-charcoal-950 group-hover:text-cream-300 transition-smooth shadow-xs">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l2.4 4.8 5.3.8-3.8 3.7.9 5.3L12 16.1l-4.8 2.5.9-5.3-3.8-3.7 5.3-.8L12 2zM12 18v4m-6-2h12"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-bold text-charcoal-950 text-sm sm:text-base">Mukena Sutra</h4>
                    <p class="text-[11px] text-charcoal-500 mt-1 font-light">Renda Giper &amp; Bordir Halus</p>
                </div>
                <div class="pt-1">
                    <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-cream-800 uppercase tracking-widest group-hover:underline">
                        <span>Lihat Varian</span>
                        <svg class="w-3 h-3 text-cream-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Tier Privileges Section in Frosted Glass -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-cream-700 text-[10px] uppercase tracking-[0.25em] font-bold">Akses &amp; Hak Istimewa</span>
            <h2 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Pilihan Tepat untuk Setiap Kebutuhan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-stretch">
            <!-- Customer Retail Card -->
            <div class="glass-card p-8 rounded-3xl flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-cream-100/90 border border-cream-300 flex items-center justify-center text-charcoal-950">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-charcoal-950">Pembeli Retail (Guest)</h3>
                    <p class="text-xs text-charcoal-600 leading-relaxed font-light">Pilihan tepat untuk belanja cepat tanpa wajib mendaftar. Pilihan ekspedisi resmi dan metode pembayaran QRIS/VA otomatis.</p>
                    <ul class="space-y-2.5 text-xs text-charcoal-700 pt-3 border-t border-cream-200/80 font-light">
                        <li class="flex items-center space-x-2.5">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                            <span>Checkout instan tanpa registrasi</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                            <span>Lacak resi pengiriman otomatis</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✓</span>
                            <span>Jaminan keaslian 100% original</span>
                        </li>
                    </ul>
                </div>
                <a href="#koleksi" class="w-full py-3.5 text-center bg-cream-200/80 hover:bg-cream-300 text-charcoal-900 font-bold rounded-2xl text-xs uppercase tracking-widest transition-smooth">
                    Belanja Sekarang
                </a>
            </div>

            <!-- Member Privilege Card in Frosted Glass & Cream Border -->
            <div class="glass-card p-8 rounded-3xl border-2 border-cream-400/80 shadow-2xl flex flex-col justify-between space-y-6 relative overflow-hidden ring-1 ring-cream-400/30">
                <div class="absolute top-0 right-0 bg-charcoal-950 text-cream-300 text-[9px] font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-bl-2xl shadow-xs border-b border-l border-cream-400/40">
                    Rekomendasi
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-cream-100 border border-cream-300 text-cream-800 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-charcoal-950">Member Eksklusif</h3>
                    <p class="text-xs text-charcoal-600 leading-relaxed font-light">Nikmati reward poin pada setiap transaksi, voucher diskon hari besar, dan kemudahan multi-alamat pengiriman.</p>
                    <ul class="space-y-2.5 text-xs text-charcoal-800 pt-3 border-t border-cream-200/80">
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Poin reward tukar potongan belanja</span>
                        </li>
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Voucher diskon khusus anggota</span>
                        </li>
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-cream-200 text-charcoal-900 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Buku alamat &amp; pelacakan order terpadu</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3.5 text-center bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl border border-cream-400/40 transition-smooth">
                    Daftar Member Gratis
                </a>
            </div>

            <!-- Reseller Partner Card in Deep Charcoal Glass -->
            <div class="glass-dark text-white p-8 rounded-3xl border border-cream-400/30 shadow-2xl flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-cream-400/30 text-cream-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-white">Mitra Reseller &amp; Grosir</h3>
                    <p class="text-xs text-cream-200/90 leading-relaxed font-light">Akses tier harga grosir terendah, komisi referral otomatis, dompet saldo digital (*wallet*), dan materi promosi butik.</p>
                    <ul class="space-y-2.5 text-xs text-cream-300 pt-3 border-t border-charcoal-700/60">
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-white/10 text-cream-300 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Harga khusus reseller (diskon s/d 30%)</span>
                        </li>
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-white/10 text-cream-300 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Komisi referral otomatis masuk dompet</span>
                        </li>
                        <li class="flex items-center space-x-2.5 font-medium">
                            <span class="w-4 h-4 rounded-full bg-white/10 text-cream-300 flex items-center justify-center text-[10px] font-bold shrink-0">✦</span>
                            <span>Penarikan dana (withdrawal) instan</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('register', ['type' => 'reseller']) }}" class="w-full py-3.5 text-center bg-cream-btn text-charcoal-950 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-lg hover:opacity-95 transition-smooth">
                    Gabung Mitra Reseller
                </a>
            </div>
        </div>
    </section>

    <!-- Trust & Quality Pillars in Frosted Glass -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-[2.5rem] p-8 sm:p-14 border border-cream-200/90 shadow-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-display font-bold text-charcoal-950 text-sm">Standar Butik Mewah</h4>
                    <p class="text-xs text-charcoal-500 leading-relaxed font-light">Jahitan rapi halus, pola presisi, dan material tidak menerawang.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-display font-bold text-charcoal-950 text-sm">Jaminan 100% Syar'i</h4>
                    <p class="text-xs text-charcoal-500 leading-relaxed font-light">Koleksi pakaian mematuhi kaidah busana syariat yang anggun.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.948c0-.621-.504-1.125-1.125-1.125H4.125C3.504 5.5 3 6.004 3 6.625v8.25c0 .621.504 1.125 1.125 1.125h1.5"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-display font-bold text-charcoal-950 text-sm">Ekspedisi Kilat Aman</h4>
                    <p class="text-xs text-charcoal-500 leading-relaxed font-light">Pengiriman ke seluruh Indonesia dengan proteksi asuransi.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-charcoal-950/5 border border-cream-300 flex items-center justify-center text-charcoal-900 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3.84-3.091c-.422-.016-.84-.038-1.258-.066a9.92 9.92 0 01-3.652-.962m10.25-7.528A9.972 9.972 0 0012 5.25c-5.523 0-10 4.477-10 10 0 1.688.42 3.278 1.16 4.673l-1.16 4.327 4.545-1.136A9.957 9.957 0 0012 25.25c5.523 0 10-4.477 10-10 0-1.848-.5-3.578-1.375-5.064"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-display font-bold text-charcoal-950 text-sm">Layanan Konsultasi VIP</h4>
                    <p class="text-xs text-charcoal-500 leading-relaxed font-light">Bantuan ukuran dan rekomendasi fashion siap melayani Anda.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
