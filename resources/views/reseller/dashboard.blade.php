@extends('layouts.dashboard')

@section('title', 'Reseller Hub')

@section('content')
<div class="space-y-6">
    <!-- Reseller Banner Header -->
    <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-800 rounded-3xl p-6 sm:p-8 text-white shadow-card relative overflow-hidden">
        <div class="relative z-10 space-y-2">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold px-3 py-1 bg-emerald-700/80 rounded-full text-emerald-100 backdrop-blur">
                    Mitra Bisnis Reseller
                </span>
                @if($user->status->value === 'pending')
                    <span class="text-xs font-bold px-3 py-1 bg-amber-500 text-stone-900 rounded-full">
                        ⏳ Menunggu Persetujuan Admin
                    </span>
                @else
                    <span class="text-xs font-bold px-3 py-1 bg-emerald-400 text-emerald-950 rounded-full">
                        ✓ Terverifikasi Aktif
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight">Portal Reseller: {{ $user->name }}</h1>
            <p class="text-emerald-100/90 text-sm max-w-xl">Akses katalog harga grosir khusus, lacak komisi referral Anda, dan kelola penarikan saldo dompet secara transparan.</p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-52 h-52 rounded-full bg-emerald-600/20 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Status Alert if Pending -->
    @if($user->status->value === 'pending')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start space-x-3 text-amber-900 text-xs leading-relaxed">
            <span class="text-xl">⚠️</span>
            <div>
                <p class="font-bold text-sm">Status Pendaftaran: Menunggu Verifikasi</p>
                <p class="mt-0.5">Pengajuan kemitraan reseller Anda sedang dalam peninjauan oleh tim admin. Setelah disetujui, harga khusus reseller dan link referral Anda akan otomatis aktif.</p>
            </div>
        </div>
    @endif

    <!-- Reseller Financial & Performance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Saldo Dompet Komisi</span>
            <p class="text-2xl font-bold text-emerald-700 mt-2">Rp 0</p>
            <span class="text-xs text-stone-400">Siap ditarik ke rekening</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Komisi Tertunda (Pending)</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">Rp 0</p>
            <span class="text-xs text-stone-400">Pesanan dalam pengiriman</span>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-soft">
            <span class="text-xs font-bold uppercase text-stone-500 tracking-wider">Total Referral Berhasil</span>
            <p class="text-2xl font-bold text-stone-900 mt-2">0 Transaksi</p>
            <span class="text-xs text-stone-400">Dari tautan promosi Anda</span>
        </div>
    </div>

    <!-- Referral Link Mockup Card -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft space-y-4">
        <h2 class="font-bold text-stone-900 text-base">Tautan Referral & Materi Promosi</h2>
        <p class="text-xs text-stone-600 leading-relaxed">Bagikan tautan referral ini kepada calon pembeli. Setiap pesanan selesai yang berasal dari tautan Anda akan otomatis menambahkan komisi ke dompet.</p>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <input type="text" readonly value="{{ url('/?ref=' . ($user->phone ?? 'reseller-' . $user->id)) }}"
                class="flex-1 bg-stone-100 border border-stone-200 rounded-2xl py-2.5 px-4 text-xs font-mono text-stone-700 select-all">
            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/?ref=' . ($user->phone ?? 'reseller-' . $user->id)) }}'); alert('Tautan berhasil disalin!');"
                class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-2xl shadow-sm transition-all shrink-0">
                Salin Tautan
            </button>
        </div>
    </div>
</div>
@endsection
