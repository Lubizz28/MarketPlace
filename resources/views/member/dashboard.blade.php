@extends('layouts.dashboard')

@section('title', 'Dashboard Member')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-800 rounded-3xl p-6 sm:p-8 text-white shadow-card relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <span class="inline-block text-xs font-semibold px-3 py-1 bg-emerald-700/80 rounded-full text-emerald-100 backdrop-blur">
                Status: {{ $user->role->label() }}
            </span>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight">Assalamu'alaikum, {{ $user->name }}!</h1>
            <p class="text-emerald-100/90 text-sm max-w-xl">Selamat datang di dashboard belanja Anda. Kelola profil, pantau status pesanan, dan gunakan alamat favorit untuk checkout cepat.</p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-48 h-48 rounded-full bg-emerald-700/30 blur-2xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Total Pesanan</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">0</p>
            <span class="text-xs text-stone-400">Belum ada transaksi</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Poin Loyalitas</span>
            <p class="text-2xl font-bold text-emerald-700 mt-2">0 Poin</p>
            <span class="text-xs text-stone-400">Tukar saat checkout</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Voucher Saya</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">0</p>
            <span class="text-xs text-stone-400">Voucher aktif</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Buku Alamat</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">{{ $user->addresses->count() }}</p>
            <span class="text-xs text-stone-400">Alamat tersimpan</span>
        </div>
    </div>

    <!-- Profile & Address Quick Access -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Summary Card -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <h2 class="font-bold text-stone-900 text-base">Informasi Akun</h2>
                <a href="{{ route('member.profile') }}" class="text-xs font-semibold text-emerald-800 hover:underline">Edit Profil</a>
            </div>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-stone-500 block">Nama Lengkap</span>
                    <p class="font-medium text-stone-800">{{ $user->name }}</p>
                </div>
                <div>
                    <span class="text-xs text-stone-500 block">Alamat Email</span>
                    <p class="font-medium text-stone-800">{{ $user->email }}</p>
                </div>
                <div>
                    <span class="text-xs text-stone-500 block">Nomor WhatsApp</span>
                    <p class="font-medium text-stone-800">{{ $user->phone ?? 'Belum diatur' }}</p>
                </div>
            </div>
        </div>

        <!-- Primary Address Summary Card -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                <h2 class="font-bold text-stone-900 text-base">Alamat Pengiriman Utama</h2>
                <a href="{{ route('member.addresses.index') }}" class="text-xs font-semibold text-emerald-800 hover:underline">Kelola Alamat</a>
            </div>
            @if($primaryAddress = $user->primaryAddress)
                <div class="space-y-2 text-sm">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-stone-900">{{ $primaryAddress->recipient_name }}</span>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-semibold rounded-md">Utama</span>
                    </div>
                    <p class="text-xs text-stone-500">{{ $primaryAddress->phone }}</p>
                    <p class="text-stone-700 text-xs leading-relaxed">{{ $primaryAddress->full_address }}</p>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-xs text-stone-500 mb-3">Anda belum menambahkan alamat pengiriman utama.</p>
                    <a href="{{ route('member.addresses.index') }}" class="inline-block px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-800 font-semibold text-xs rounded-xl transition-colors">
                        + Tambah Alamat Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
