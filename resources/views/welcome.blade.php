@extends('layouts.app')

@section('title', 'Koleksi Busana Muslim & Modest Fashion Haute Couture')

@section('content')
<div class="space-y-16 sm:space-y-24">
    <!-- Hero Haute Couture Banner -->
    <section class="relative bg-emerald-luxury text-white rounded-[2.5rem] mx-4 sm:mx-6 lg:mx-8 px-6 sm:px-14 py-16 sm:py-28 shadow-luxury-lg overflow-hidden border border-gold-500/20">
        <!-- Subtle Arabesque Background Pattern -->
        <div class="absolute inset-0 bg-islamic-pattern opacity-40 pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-7">
            <div class="inline-flex items-center space-x-2.5 px-4 py-1.5 rounded-full bg-emerald-900/80 border border-gold-500/40 text-gold-300 text-xs font-semibold backdrop-blur shadow-xs">
                <span class="text-gold-400">✦</span>
                <span class="tracking-widest uppercase text-[10px] sm:text-xs">Edisi Mahakarya 2026</span>
                <span class="text-gold-400">✦</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold tracking-tight leading-[1.15] text-white">
                Keanggunan Syar'i dalam Sentuhan <span class="text-gold-gradient italic font-normal">Haute Couture</span>.
            </h1>

            <p class="text-sand-300 text-sm sm:text-base leading-relaxed max-w-xl font-light">
                Koleksi gamis sutra, abaya bordir tangan, pashmina voal premium, dan koko kurta modern. Diciptakan dari material terbaik dengan potongan yang menjunjung tinggi kesopanan dan kenyamanan.
            </p>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-3">
                <a href="#koleksi" class="px-8 py-4 bg-gold-gradient text-emerald-950 font-bold rounded-2xl text-center shadow-gold hover:opacity-95 transition-all text-xs uppercase tracking-widest">
                    Jelajahi Koleksi
                </a>
                <a href="{{ route('register', ['type' => 'reseller']) }}" class="px-8 py-4 bg-emerald-900/90 hover:bg-emerald-800 text-gold-300 font-bold rounded-2xl text-center border border-gold-500/30 transition-all text-xs uppercase tracking-widest backdrop-blur">
                    Gabung Kemitraan Reseller &rarr;
                </a>
            </div>
        </div>

        <!-- Decorative Ambient Light Orbs -->
        <div class="absolute -right-16 -top-16 w-96 h-96 rounded-full bg-gold-500/15 blur-[120px] pointer-events-none"></div>
        <div class="absolute right-12 bottom-0 w-80 h-80 rounded-full bg-emerald-600/20 blur-[100px] pointer-events-none"></div>
    </section>

    <!-- Curated Categories Grid -->
    <section id="koleksi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-gold-700 text-xs uppercase tracking-[0.2em] font-bold">Koleksi Terkurasi</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-sand-900 mt-1">Busana Pilihan Terbaik</h2>
            </div>
            <p class="text-xs text-sand-500 max-w-md">Karakter bahan adem, tidak terawang, dan dirancang khusus untuk memenuhi standar busana muslim kontemporer.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            <!-- Item 1: Gamis & Abaya -->
            <div class="group bg-white p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury hover:shadow-luxury-lg hover:border-gold-500/40 transition-all text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-sand-100/80 border border-sand-200/60 flex items-center justify-center text-3xl sm:text-4xl group-hover:scale-110 transition-transform shadow-xs">
                    👗
                </div>
                <div>
                    <h4 class="font-serif font-bold text-sand-900 text-base">Gamis & Abaya</h4>
                    <p class="text-xs text-sand-500 mt-1 font-light">Silk, Jacquard & Ceruty</p>
                </div>
                <span class="inline-block text-[11px] font-bold text-gold-700 tracking-wider uppercase group-hover:underline">Lihat Varian &rarr;</span>
            </div>

            <!-- Item 2: Hijab & Khimar -->
            <div class="group bg-white p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury hover:shadow-luxury-lg hover:border-gold-500/40 transition-all text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-sand-100/80 border border-sand-200/60 flex items-center justify-center text-3xl sm:text-4xl group-hover:scale-110 transition-transform shadow-xs">
                    🧕
                </div>
                <div>
                    <h4 class="font-serif font-bold text-sand-900 text-base">Hijab & Khimar</h4>
                    <p class="text-xs text-sand-500 mt-1 font-light">Voal Ultrafine & Silk Pashmina</p>
                </div>
                <span class="inline-block text-[11px] font-bold text-gold-700 tracking-wider uppercase group-hover:underline">Lihat Varian &rarr;</span>
            </div>

            <!-- Item 3: Koko & Kurta -->
            <div class="group bg-white p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury hover:shadow-luxury-lg hover:border-gold-500/40 transition-all text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-sand-100/80 border border-sand-200/60 flex items-center justify-center text-3xl sm:text-4xl group-hover:scale-110 transition-transform shadow-xs">
                    👔
                </div>
                <div>
                    <h4 class="font-serif font-bold text-sand-900 text-base">Baju Koko & Kurta</h4>
                    <p class="text-xs text-sand-500 mt-1 font-light">Katun Toyobo & Linen Mewah</p>
                </div>
                <span class="inline-block text-[11px] font-bold text-gold-700 tracking-wider uppercase group-hover:underline">Lihat Varian &rarr;</span>
            </div>

            <!-- Item 4: Mukena Bordir -->
            <div class="group bg-white p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury hover:shadow-luxury-lg hover:border-gold-500/40 transition-all text-center space-y-4 cursor-pointer relative overflow-hidden">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-sand-100/80 border border-sand-200/60 flex items-center justify-center text-3xl sm:text-4xl group-hover:scale-110 transition-transform shadow-xs">
                    ✨
                </div>
                <div>
                    <h4 class="font-serif font-bold text-sand-900 text-base">Mukena Sutra</h4>
                    <p class="text-xs text-sand-500 mt-1 font-light">Renda Giper & Bordir Halus</p>
                </div>
                <span class="inline-block text-[11px] font-bold text-gold-700 tracking-wider uppercase group-hover:underline">Lihat Varian &rarr;</span>
            </div>
        </div>
    </section>

    <!-- 3 Tier Privileges Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-gold-700 text-xs uppercase tracking-[0.2em] font-bold">Akses & Hak Istimewa</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-sand-900">Pilihan Tepat untuk Setiap Kebutuhan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-stretch">
            <!-- Customer Retail -->
            <div class="bg-white p-8 rounded-3xl border border-sand-200/90 shadow-luxury flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-sand-100 flex items-center justify-center text-2xl">
                        🛍️
                    </div>
                    <h3 class="text-xl font-serif font-bold text-sand-900">Pembeli Retail (Guest)</h3>
                    <p class="text-xs text-sand-600 leading-relaxed">Pilihan tepat untuk belanja cepat tanpa wajib mendaftar. Tersedia beragam opsi kurir instan dan metode pembayaran QRIS/VA.</p>
                    <ul class="space-y-2 text-xs text-sand-700 pt-2 border-t border-sand-100">
                        <li class="flex items-center space-x-2"><span>✓</span><span>Checkout cepat tanpa akun</span></li>
                        <li class="flex items-center space-x-2"><span>✓</span><span>Lacak resi pengiriman otomatis</span></li>
                        <li class="flex items-center space-x-2"><span>✓</span><span>Garansi penukaran produk original</span></li>
                    </ul>
                </div>
                <a href="#koleksi" class="w-full py-3 text-center bg-sand-100 hover:bg-sand-200 text-sand-800 font-bold rounded-2xl text-xs uppercase tracking-wider transition-colors">
                    Belanja Sekarang
                </a>
            </div>

            <!-- Member Privilege -->
            <div class="bg-white p-8 rounded-3xl border-2 border-gold-500/40 shadow-luxury-lg flex flex-col justify-between space-y-6 relative overflow-hidden ring-1 ring-gold-500/20">
                <div class="absolute top-0 right-0 bg-gold-gradient text-emerald-950 text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-bl-2xl shadow-xs">
                    Populer
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-gold-100 text-gold-800 flex items-center justify-center text-2xl font-bold">
                        💎
                    </div>
                    <h3 class="text-xl font-serif font-bold text-sand-900">Member Eksklusif</h3>
                    <p class="text-xs text-sand-600 leading-relaxed">Nikmati reward poin pada setiap transaksi, akses kupon perayaan hari raya, dan kemudahan simpan multi-alamat pengiriman.</p>
                    <ul class="space-y-2 text-xs text-sand-700 pt-2 border-t border-sand-100">
                        <li class="flex items-center space-x-2 text-emerald-900 font-semibold"><span>✦</span><span>Poin loyalitas tukar diskon</span></li>
                        <li class="flex items-center space-x-2 text-emerald-900 font-semibold"><span>✦</span><span>Voucher potongan belanja spesial</span></li>
                        <li class="flex items-center space-x-2 text-emerald-900 font-semibold"><span>✦</span><span>Buku alamat & riwayat transaksi</span></li>
                    </ul>
                </div>
                <a href="{{ route('register') }}" class="w-full py-3 text-center bg-emerald-950 hover:bg-emerald-900 text-gold-300 font-bold rounded-2xl text-xs uppercase tracking-wider shadow-luxury border border-gold-500/30 transition-all">
                    Daftar Member Gratis
                </a>
            </div>

            <!-- Reseller Partner -->
            <div class="bg-emerald-luxury text-white p-8 rounded-3xl border border-gold-500/30 shadow-luxury-lg flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-900/80 border border-gold-500/30 text-gold-400 flex items-center justify-center text-2xl font-bold">
                        💼
                    </div>
                    <h3 class="text-xl font-serif font-bold text-white">Mitra Reseller & Afiliasi</h3>
                    <p class="text-xs text-sand-300 leading-relaxed">Dapatkan tier harga grosir terendah, komisi referral otomatis, dompet saldo (*wallet*), dan materi pemasaran siap sebar.</p>
                    <ul class="space-y-2 text-xs text-gold-300/90 pt-2 border-t border-emerald-900">
                        <li class="flex items-center space-x-2"><span>✦</span><span>Harga khusus reseller (diskon s/d 30%)</span></li>
                        <li class="flex items-center space-x-2"><span>✦</span><span>Komisi referral masuk ke saldo dompet</span></li>
                        <li class="flex items-center space-x-2"><span>✦</span><span>Penarikan dana (withdrawal) instan</span></li>
                    </ul>
                </div>
                <a href="{{ route('register', ['type' => 'reseller']) }}" class="w-full py-3 text-center bg-gold-gradient text-emerald-950 font-bold rounded-2xl text-xs uppercase tracking-wider shadow-gold hover:opacity-95 transition-all">
                    Gabung Mitra Reseller
                </a>
            </div>
        </div>
    </section>

    <!-- Trust & Quality Pillars -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[2.5rem] p-8 sm:p-14 border border-sand-200/90 shadow-luxury grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl shrink-0">
                    🧵
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif font-bold text-sand-900 text-sm">Standar Butik Premium</h4>
                    <p class="text-xs text-sand-500 leading-relaxed">Jahitan rapi, pola presisi, dan material tidak menerawang.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center text-2xl shrink-0">
                    🛡️
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif font-bold text-sand-900 text-sm">Jaminan 100% Halal</h4>
                    <p class="text-xs text-sand-500 leading-relaxed">Koleksi pakaian mematuhi kaidah busana syar'i yang anggun.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl shrink-0">
                    🚚
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif font-bold text-sand-900 text-sm">Ekspedisi Terpercaya</h4>
                    <p class="text-xs text-sand-500 leading-relaxed">Pengiriman kilat ke seluruh Indonesia dengan proteksi asuransi.</p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gold-50 border border-gold-100 flex items-center justify-center text-2xl shrink-0">
                    💎
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif font-bold text-sand-900 text-sm">Layanan CS Ramah</h4>
                    <p class="text-xs text-sand-500 leading-relaxed">Konsultasi ukuran dan panduan berbelanja responsif.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
