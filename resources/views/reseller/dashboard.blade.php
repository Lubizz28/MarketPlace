@extends('layouts.dashboard')

@section('title', 'Reseller Hub')

@section('content')
<div class="space-y-8">
    <!-- Reseller Banner Header -->
    <div class="bg-emerald-luxury rounded-3xl p-7 sm:p-10 text-white shadow-luxury-lg relative overflow-hidden border border-gold-500/30">
        <div class="absolute inset-0 bg-islamic-pattern opacity-30 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[10px] uppercase tracking-widest font-bold px-3 py-1 bg-gold-gradient text-emerald-950 rounded-full shadow-xs">
                    ✦ Mitra Bisnis Reseller
                </span>
                @if($user->status->value === 'pending')
                    <span class="text-[10px] uppercase tracking-widest font-bold px-3 py-1 bg-amber-400 text-sand-950 rounded-full">
                        ⏳ Menunggu Verifikasi Admin
                    </span>
                @else
                    <span class="text-[10px] uppercase tracking-widest font-bold px-3 py-1 bg-emerald-400 text-emerald-950 rounded-full">
                        ✓ Terverifikasi Aktif
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-4xl font-serif font-bold tracking-tight text-white">Portal Reseller: {{ $user->name }}</h1>
            <p class="text-sand-300 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Pusat kendali bisnis kemitraan Anda. Dapatkan harga grosir termurah, sebar link referral, dan pantau mutasi saldo dompet komisi secara transparan.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-gold-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Status Alert if Pending -->
    @if($user->status->value === 'pending')
        <div class="p-5 bg-amber-50/90 border border-amber-300/80 rounded-3xl flex items-start space-x-3.5 text-amber-950 text-xs leading-relaxed shadow-xs">
            <span class="text-xl">⚠️</span>
            <div>
                <p class="font-serif font-bold text-sm text-amber-950">Status Pendaftaran: Menunggu Peninjauan Admin</p>
                <p class="mt-1 text-amber-800">Akun kemitraan Anda sedang diverifikasi. Setelah disetujui, tier harga khusus reseller dan link referral aktif akan otomatis dapat digunakan untuk transaksi.</p>
            </div>
        </div>
    @endif

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white/95 backdrop-blur-xl p-6 rounded-3xl border border-gold-500/40 shadow-luxury ring-1 ring-gold-500/10">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-gold-700">Saldo Dompet Komisi</span>
                <span class="text-base">💰</span>
            </div>
            <p class="text-2xl font-serif font-bold text-emerald-950 mt-2">Rp 0</p>
            <span class="text-[11px] text-emerald-700 font-semibold">Tersedia untuk ditarik</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-sand-400">Komisi Tertunda</span>
                <span class="text-base">⏳</span>
            </div>
            <p class="text-2xl font-serif font-bold text-sand-900 mt-2">Rp 0</p>
            <span class="text-[11px] text-sand-400">Pesanan belum selesai</span>
        </div>

        <div class="bg-white/95 backdrop-blur-xl p-6 rounded-3xl border border-sand-200/90 shadow-luxury">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-sand-400">Transaksi Referral</span>
                <span class="text-base">📈</span>
            </div>
            <p class="text-2xl font-serif font-bold text-sand-900 mt-2">0 Transaksi</p>
            <span class="text-[11px] text-sand-400">Dari tautan promosi Anda</span>
        </div>
    </div>

    <!-- Referral Link Box -->
    <div class="bg-white/95 backdrop-blur-xl p-7 sm:p-8 rounded-3xl border border-sand-200/90 shadow-luxury space-y-4">
        <div>
            <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-gold-700">Afiliasi & Promosi</span>
            <h2 class="text-lg font-serif font-bold text-sand-900 mt-1">Tautan Referral Eksklusif Anda</h2>
        </div>
        <p class="text-xs text-sand-600 leading-relaxed max-w-2xl">
            Sebarkan tautan di bawah ini ke media sosial (WhatsApp, Instagram, TikTok). Setiap transaksi sukses yang masuk lewat tautan Anda akan otomatis mencatatkan komisi ke buku kas dompet Anda.
        </p>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
            <input type="text" readonly value="{{ url('/?ref=' . ($user->phone ?? 'reseller-' . $user->id)) }}"
                class="flex-1 bg-sand-50 border border-sand-200/90 rounded-2xl py-3 px-4 text-xs font-mono text-sand-800 select-all focus:outline-none shadow-xs">
            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/?ref=' . ($user->phone ?? 'reseller-' . $user->id)) }}'); alert('Tautan referral berhasil disalin!');"
                class="px-6 py-3.5 bg-emerald-950 hover:bg-emerald-900 text-gold-300 font-bold text-xs uppercase tracking-wider rounded-2xl shadow-luxury border border-gold-500/30 hover:shadow-gold transition-all shrink-0">
                Salin Tautan
            </button>
        </div>
    </div>
</div>
@endsection
