@extends('layouts.dashboard')

@section('title', 'Dashboard Member')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Card -->
    <div class="bg-emerald-luxury rounded-3xl p-7 sm:p-10 text-white shadow-luxury-lg relative overflow-hidden border border-gold-500/20">
        <div class="absolute inset-0 bg-islamic-pattern opacity-30 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[10px] uppercase tracking-widest font-bold px-3 py-1 bg-gold-gradient text-emerald-950 rounded-full shadow-xs">
                <span>✦</span><span>Member Eksklusif</span>
            </span>
            <h1 class="text-2xl sm:text-4xl font-serif font-bold tracking-tight text-white">Assalamu'alaikum, {{ $user->name }}!</h1>
            <p class="text-sand-300 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Selamat datang di portal belanja pribadi Anda. Nikmati kemudahan bertransaksi, klaim poin reward loyalitas, dan atur preferensi pengiriman Anda.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-gold-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-sand-400">Total Pesanan</span>
                <span class="text-base">📦</span>
            </div>
            <p class="text-2xl font-serif font-bold text-sand-900 mt-2">0</p>
            <span class="text-[11px] text-sand-400">Riwayat belanja Anda</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-gold-500/30 shadow-luxury ring-1 ring-gold-500/10">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-gold-700">Poin Loyalitas</span>
                <span class="text-base">💎</span>
            </div>
            <p class="text-2xl font-serif font-bold text-gold-700 mt-2">0 Poin</p>
            <span class="text-[11px] text-sand-400">Tukar saat checkout</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-sand-400">Kupon Aktif</span>
                <span class="text-base">🎟️</span>
            </div>
            <p class="text-2xl font-serif font-bold text-sand-900 mt-2">0 Kupon</p>
            <span class="text-[11px] text-sand-400">Diskon khusus member</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-sand-400">Buku Alamat</span>
                <span class="text-base">📍</span>
            </div>
            <p class="text-2xl font-serif font-bold text-sand-900 mt-2">{{ $user->addresses->count() }}</p>
            <span class="text-[11px] text-sand-400">Tujuan tersimpan</span>
        </div>
    </div>

    <!-- Profile & Primary Address Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Summary Card -->
        <div class="bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-sand-100">
                <h2 class="font-serif font-bold text-sand-900 text-base">Informasi Personal</h2>
                <a href="{{ route('member.profile') }}" class="text-xs font-bold text-gold-700 hover:text-gold-900 hover:underline">Edit Profil &rarr;</a>
            </div>
            <div class="space-y-3.5 text-xs">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-sand-400 font-bold block">Nama Lengkap</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5">{{ $user->name }}</p>
                </div>
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-sand-400 font-bold block">Alamat Email</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5">{{ $user->email }}</p>
                </div>
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-sand-400 font-bold block">Nomor WhatsApp</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5">{{ $user->phone ?? 'Belum diatur' }}</p>
                </div>
            </div>
        </div>

        <!-- Primary Address Summary Card -->
        <div class="bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-sand-100">
                <h2 class="font-serif font-bold text-sand-900 text-base">Alamat Pengiriman Utama</h2>
                <a href="{{ route('member.addresses.index') }}" class="text-xs font-bold text-gold-700 hover:text-gold-900 hover:underline">Buku Alamat &rarr;</a>
            </div>
            @if($primaryAddress = $user->primaryAddress)
                <div class="space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-sand-900 text-sm">{{ $primaryAddress->recipient_name }}</span>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-900 text-[10px] font-bold uppercase tracking-wider rounded-full">Utama</span>
                    </div>
                    <p class="text-sand-500">{{ $primaryAddress->phone }}</p>
                    <p class="text-sand-700 text-xs leading-relaxed pt-1">{{ $primaryAddress->full_address }}</p>
                </div>
            @else
                <div class="text-center py-6 space-y-3">
                    <p class="text-xs text-sand-500">Anda belum menyimpan alamat pengiriman utama.</p>
                    <a href="{{ route('member.addresses.index') }}" class="inline-block px-5 py-2.5 bg-sand-100 hover:bg-sand-200 text-sand-800 font-bold text-xs rounded-2xl uppercase tracking-wider transition-colors">
                        + Tambah Alamat Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
