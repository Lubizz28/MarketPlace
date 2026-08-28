@extends('layouts.dashboard')

@section('title', 'Dashboard Member')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Card -->
    <div class="bg-emerald-luxury rounded-3xl p-7 sm:p-10 text-white shadow-luxury-lg relative overflow-hidden border border-gold-500/20">
        <div class="absolute inset-0 bg-islamic-pattern opacity-30 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-gold-gradient text-emerald-950 rounded-full shadow-xs">
                <span>✦</span><span>Member Eksklusif</span>
            </span>
            <h1 class="text-2xl sm:text-4xl font-display font-bold tracking-tight text-white">Assalamu'alaikum, {{ $user->name }}!</h1>
            <p class="text-sand-300 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Selamat datang di portal belanja pribadi Anda. Nikmati kemudahan bertransaksi, klaim poin reward loyalitas, dan atur preferensi pengiriman Anda.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-gold-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid with Custom SVG Icons -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-sand-400">Total Pesanan</span>
                <div class="w-8 h-8 rounded-xl bg-sand-100 flex items-center justify-center text-sand-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-sand-900 mt-2">0</p>
            <span class="text-[11px] text-sand-400 font-light">Riwayat belanja Anda</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-gold-500/40 shadow-luxury ring-1 ring-gold-500/20">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-gold-700">Poin Loyalitas</span>
                <div class="w-8 h-8 rounded-xl bg-gold-50 border border-gold-300 flex items-center justify-center text-gold-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-gold-700 mt-2">0 Poin</p>
            <span class="text-[11px] text-gold-800/80 font-light">Tukar saat checkout</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-sand-400">Kupon Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-sand-100 flex items-center justify-center text-sand-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-sand-900 mt-2">0 Kupon</p>
            <span class="text-[11px] text-sand-400 font-light">Diskon khusus member</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-5 sm:p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-sand-400">Buku Alamat</span>
                <div class="w-8 h-8 rounded-xl bg-sand-100 flex items-center justify-center text-sand-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-sand-900 mt-2">{{ $user->addresses->count() }}</p>
            <span class="text-[11px] text-sand-400 font-light">Tujuan tersimpan</span>
        </div>
    </div>

    <!-- Profile & Primary Address Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Summary Card -->
        <div class="bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-sand-100">
                <h2 class="font-display font-bold text-sand-900 text-base">Informasi Personal</h2>
                <a href="{{ route('member.profile') }}" class="text-xs font-bold text-gold-700 hover:text-gold-900 hover:underline">Edit Profil &rarr;</a>
            </div>
            <div class="space-y-3.5 text-xs">
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-sand-400 font-bold block">Nama Lengkap</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5">{{ $user->name }}</p>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-sand-400 font-bold block">Alamat Email</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5">{{ $user->email }}</p>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-sand-400 font-bold block">Nomor WhatsApp</span>
                    <p class="font-semibold text-sand-800 text-sm mt-0.5 font-mono">{{ $user->phone ?? 'Belum diatur' }}</p>
                </div>
            </div>
        </div>

        <!-- Primary Address Summary Card -->
        <div class="bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-sand-100">
                <h2 class="font-display font-bold text-sand-900 text-base">Alamat Pengiriman Utama</h2>
                <a href="{{ route('member.addresses.index') }}" class="text-xs font-bold text-gold-700 hover:text-gold-900 hover:underline">Buku Alamat &rarr;</a>
            </div>
            @if($primaryAddress = $user->primaryAddress)
                <div class="space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-sand-900 text-sm">{{ $primaryAddress->recipient_name }}</span>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-900 text-[9px] font-bold uppercase tracking-wider rounded-full">Utama</span>
                    </div>
                    <p class="text-sand-500 font-mono">{{ $primaryAddress->phone }}</p>
                    <p class="text-sand-700 text-xs leading-relaxed pt-1 font-light">{{ $primaryAddress->full_address }}</p>
                </div>
            @else
                <div class="text-center py-6 space-y-3">
                    <p class="text-xs text-sand-500 font-light">Anda belum menyimpan alamat pengiriman utama.</p>
                    <a href="{{ route('member.addresses.index') }}" class="inline-block px-5 py-2.5 bg-sand-100 hover:bg-sand-200 text-sand-800 font-bold text-xs rounded-2xl uppercase tracking-wider transition-colors">
                        + Tambah Alamat Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
