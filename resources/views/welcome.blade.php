@extends('layouts.app')

@section('title', 'Koleksi Busana Muslim & Fashion Syar\'i Premium')

@section('content')
<div class="space-y-12 sm:space-y-16">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-950 via-emerald-900 to-stone-900 text-white rounded-3xl mx-4 sm:mx-6 lg:mx-8 px-6 sm:px-12 py-16 sm:py-24 shadow-card overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-6">
            <span class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 bg-emerald-800/80 border border-emerald-700/50 rounded-full text-emerald-200 backdrop-blur">
                <span>🌸 Koleksi Eksklusif 2026</span>
            </span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold tracking-tight leading-tight">
                Elegan, Anggun, & Sesuai Syariat.
            </h1>
            <p class="text-stone-300 text-sm sm:text-base leading-relaxed">
                Temukan ragam gamis premium, hijab silk modern, koko pria elegan, dan mukena bordir eksklusif. Didesain dengan material adem berkualitas tinggi untuk kenyamanan ibadah dan keseharian.
            </p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <a href="#kategori" class="px-8 py-3.5 bg-white hover:bg-stone-100 text-emerald-950 font-bold rounded-2xl text-center shadow-lg transition-all text-sm">
                    Jelajahi Katalog
                </a>
                <a href="{{ route('register', ['type' => 'reseller']) }}" class="px-8 py-3.5 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-2xl text-center border border-emerald-600/50 transition-all text-sm">
                    Daftar Jadi Reseller &rarr;
                </a>
            </div>
        </div>

        <!-- Decorative Glow Background Elements -->
        <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full bg-emerald-700/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-10 bottom-0 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
    </section>

    <!-- 3 User Role Benefits Card -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Customer Card -->
            <div class="bg-white p-7 rounded-3xl border border-stone-200/80 shadow-soft hover:shadow-card transition-all space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-stone-800 flex items-center justify-center text-2xl font-bold">
                    🛍️
                </div>
                <h3 class="text-lg font-bold text-stone-900">Belanja Retail Cepat</h3>
                <p class="text-xs text-stone-600 leading-relaxed">Nikmati kemudahan checkout instan langsung tanpa repot. Pilihan kurir lengkap dengan resi pelacakan otomatis.</p>
            </div>

            <!-- Member Card -->
            <div class="bg-white p-7 rounded-3xl border border-emerald-500/30 ring-1 ring-emerald-500/20 shadow-soft hover:shadow-card transition-all space-y-4 relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-2xl font-bold">
                    💎
                </div>
                <h3 class="text-lg font-bold text-stone-900">Program Member Setia</h3>
                <p class="text-xs text-stone-600 leading-relaxed">Dapatkan poin belanja pada setiap transaksi, akses voucher eksklusif perayaan hari besar, dan promo flash sale.</p>
            </div>

            <!-- Reseller Card -->
            <div class="bg-gradient-to-br from-emerald-900 to-emerald-950 text-white p-7 rounded-3xl shadow-soft hover:shadow-card transition-all space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-800 text-emerald-200 flex items-center justify-center text-2xl font-bold">
                    💼
                </div>
                <h3 class="text-lg font-bold text-white">Kemitraan Reseller</h3>
                <p class="text-xs text-emerald-100/80 leading-relaxed">Dapatkan tier harga grosir termurah, tautan referral afiliasi, komisi otomatis masuk dompet, dan materi promosi siap pakai.</p>
            </div>
        </div>
    </section>

    <!-- Categories Grid Section -->
    <section id="kategori" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-stone-900">Kategori Busana Pilihan</h2>
                <p class="text-xs text-stone-500 mt-1">Pilihan model syar'i terbaik untuk muslimah dan muslim masa kini.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="group bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft hover:border-emerald-500/40 transition-all text-center space-y-3 cursor-pointer">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-stone-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    👗
                </div>
                <h4 class="font-bold text-stone-900 text-sm">Gamis & Abaya</h4>
                <p class="text-[11px] text-stone-400">Desain flowy & jatuh</p>
            </div>

            <div class="group bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft hover:border-emerald-500/40 transition-all text-center space-y-3 cursor-pointer">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-stone-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    🧕
                </div>
                <h4 class="font-bold text-stone-900 text-sm">Hijab & Pashmina</h4>
                <p class="text-[11px] text-stone-400">Voal, silk, & ceruty</p>
            </div>

            <div class="group bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft hover:border-emerald-500/40 transition-all text-center space-y-3 cursor-pointer">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-stone-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    👔
                </div>
                <h4 class="font-bold text-stone-900 text-sm">Baju Koko & Kurta</h4>
                <p class="text-[11px] text-stone-400">Katun adem elegan</p>
            </div>

            <div class="group bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft hover:border-emerald-500/40 transition-all text-center space-y-3 cursor-pointer">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-stone-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    ✨
                </div>
                <h4 class="font-bold text-stone-900 text-sm">Mukena Premium</h4>
                <p class="text-[11px] text-stone-400">Bordir & renda halus</p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-stone-900 text-white rounded-3xl p-8 sm:p-12 grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-2">
                <span class="text-3xl">🧵</span>
                <h4 class="font-bold text-sm">Bahan Premium & Adem</h4>
                <p class="text-xs text-stone-400">Dipilih khusus agar tidak terawang dan nyaman seharian.</p>
            </div>
            <div class="space-y-2">
                <span class="text-3xl">🚚</span>
                <h4 class="font-bold text-sm">Pengiriman Seluruh RI</h4>
                <p class="text-xs text-stone-400">Bekerjasama dengan kurir resmi terpercaya & cepat.</p>
            </div>
            <div class="space-y-2">
                <span class="text-3xl">🔄</span>
                <h4 class="font-bold text-sm">Garansi 100% Original</h4>
                <p class="text-xs text-stone-400">Jaminan retur jika produk cacat atau tidak sesuai.</p>
            </div>
            <div class="space-y-2">
                <span class="text-3xl">💬</span>
                <h4 class="font-bold text-sm">Layanan CS Responsif</h4>
                <p class="text-xs text-stone-400">Bantuan ramah via WhatsApp setiap hari.</p>
            </div>
        </div>
    </section>
</div>
@endsection
