@extends('layouts.app')

@section('title', 'Koneksi Terputus (Mode Offline) — Sulastika Jaya')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 text-center space-y-6">
    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl sm:rounded-3xl bg-emerald-950 text-gold-300 flex items-center justify-center border border-gold-400/40 shadow-xl">
        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gold-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 7.5h.008v.008H12v-.008z" />
        </svg>
    </div>

    <div class="space-y-2">
        <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-emerald-800">Mode Offline Sulastika Jaya</span>
        <h1 class="text-xl sm:text-3xl font-display font-bold text-charcoal-950">Anda Sedang Tidak Terhubung</h1>
        <p class="text-xs text-charcoal-500 leading-relaxed font-light">
            Koneksi internet Anda terputus. Sulastika Jaya PWA menyimpan beberapa halaman penting secara lokal. Silakan periksa kembali jaringan internet Anda.
        </p>
    </div>

    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
        <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-950 hover:bg-emerald-900 text-gold-200 font-bold rounded-xl text-xs uppercase tracking-wider transition-smooth shadow-md border border-gold-400/30">
            Muat Ulang Halaman
        </button>
        <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-2.5 bg-cream-200 hover:bg-cream-300 text-charcoal-900 font-bold rounded-xl text-xs uppercase tracking-wider transition-smooth">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
