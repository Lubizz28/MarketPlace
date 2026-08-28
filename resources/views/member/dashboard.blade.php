@extends('layouts.dashboard')

@section('title', 'Dashboard Member')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Card in Charcoal & Cream Glass -->
    <div class="bg-charcoal-luxury rounded-3xl p-7 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-cream-400/20">
        <div class="absolute inset-0 bg-cream-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-cream-btn text-charcoal-950 rounded-full shadow-xs">
                <span>✦</span><span>Member Eksklusif</span>
            </span>
            <h1 class="text-2xl sm:text-4xl font-display font-bold tracking-tight text-white">Assalamu'alaikum, {{ $user->name }}!</h1>
            <p class="text-cream-200/90 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Selamat datang di portal belanja pribadi Anda. Nikmati kemudahan bertransaksi, klaim poin reward loyalitas, dan atur preferensi pengiriman Anda.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid in Frosted Glass -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Total Pesanan</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">0</p>
            <span class="text-[11px] text-charcoal-400 font-light">Riwayat belanja Anda</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl border-2 border-cream-400/80 shadow-lg">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700">Poin Loyalitas</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 border border-cream-300 flex items-center justify-center text-cream-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-cream-800 mt-2">0 Poin</p>
            <span class="text-[11px] text-cream-800/80 font-light">Tukar saat checkout</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Kupon Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">0 Kupon</p>
            <span class="text-[11px] text-charcoal-400 font-light">Diskon khusus member</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Buku Alamat</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">{{ $user->addresses->count() }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Tujuan tersimpan</span>
        </div>
    </div>

    <!-- Profile & Primary Address Summary in Frosted Glass -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Summary Card -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-cream-200/80">
                <h2 class="font-display font-bold text-charcoal-950 text-base">Informasi Personal</h2>
                <a href="{{ route('member.profile') }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 hover:underline">Edit Profil &rarr;</a>
            </div>
            <div class="space-y-3.5 text-xs">
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-charcoal-400 font-bold block">Nama Lengkap</span>
                    <p class="font-semibold text-charcoal-900 text-sm mt-0.5">{{ $user->name }}</p>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-charcoal-400 font-bold block">Alamat Email</span>
                    <p class="font-semibold text-charcoal-900 text-sm mt-0.5">{{ $user->email }}</p>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] text-charcoal-400 font-bold block">Nomor WhatsApp</span>
                    <p class="font-semibold text-charcoal-900 text-sm mt-0.5 font-mono">{{ $user->phone ?? 'Belum diatur' }}</p>
                </div>
            </div>
        </div>

        <!-- Primary Address Summary Card -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
            <div class="flex items-center justify-between pb-3.5 border-b border-cream-200/80">
                <h2 class="font-display font-bold text-charcoal-950 text-base">Alamat Pengiriman Utama</h2>
                <a href="{{ route('member.addresses.index') }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 hover:underline">Buku Alamat &rarr;</a>
            </div>
            @if($primaryAddress = $user->primaryAddress)
                <div class="space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-charcoal-950 text-sm">{{ $primaryAddress->recipient_name }}</span>
                        <span class="px-2.5 py-0.5 bg-cream-200 text-charcoal-950 text-[9px] font-bold uppercase tracking-wider rounded-full">Utama</span>
                    </div>
                    <p class="text-charcoal-500 font-mono">{{ $primaryAddress->phone }}</p>
                    <p class="text-charcoal-700 text-xs leading-relaxed pt-1 font-light">{{ $primaryAddress->full_address }}</p>
                </div>
            @else
                <div class="text-center py-6 space-y-3">
                    <p class="text-xs text-charcoal-500 font-light">Anda belum menyimpan alamat pengiriman utama.</p>
                    <a href="{{ route('member.addresses.index') }}" class="inline-block px-5 py-2.5 bg-cream-200/80 hover:bg-cream-300 text-charcoal-900 font-bold text-xs rounded-2xl uppercase tracking-wider transition-colors">
                        + Tambah Alamat Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
