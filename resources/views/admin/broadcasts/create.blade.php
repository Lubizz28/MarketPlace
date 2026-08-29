@extends('layouts.dashboard')

@section('title', 'Kirim Pesan Siaran Baru — Admin Panel')

@section('content')
<div class="max-w-3xl space-y-6" x-data="{
    title: '',
    message: '',
    targetRole: 'all',
    channel: 'both'
}">
    <!-- Header -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.broadcasts.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Riwayat Siaran</a>
    </div>

    <div>
        <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Pemberitahuan Massal</span>
        <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1">Buat Kampanye Siaran Baru</h1>
        <p class="text-xs text-charcoal-500 font-light mt-1">Tentukan target penerima dan buat pesan interaktif yang akan dikirim serentak.</p>
    </div>

    <!-- Form in Glass Card -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <form method="POST" action="{{ route('admin.broadcasts.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Judul Kampanye / Subjek <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" x-model="title" required placeholder="Contoh: Flash Sale Ramadhan Diskon 20%"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                @error('title')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Target Sasaran Pengguna <span class="text-rose-500">*</span>
                    </label>
                    <select name="target_role" x-model="targetRole" required class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        <option value="all">Semua Pengguna Terdaftar</option>
                        <option value="member">Pelanggan Member Saja</option>
                        <option value="reseller">Mitra Reseller Saja</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Saluran Pengiriman <span class="text-rose-500">*</span>
                    </label>
                    <select name="channel" x-model="channel" required class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        <option value="both">WhatsApp &amp; Email (Rekomendasi)</option>
                        <option value="whatsapp">WhatsApp Saja</option>
                        <option value="email">Email Saja</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Isi Pesan Siaran <span class="text-rose-500">*</span>
                </label>
                <textarea name="message" rows="5" x-model="message" required placeholder="Tuliskan isi pesan pengumuman atau promo di sini..."
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs leading-relaxed"></textarea>
                <p class="text-[10px] text-charcoal-400 mt-1">Gunakan salam pembuka islami dan tautan voucher/koleksi produk terkini.</p>
                @error('message')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Live Preview Card in WhatsApp Style -->
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-900 block">💬 Pratinjau Pesan WhatsApp:</span>
                <div class="bg-white p-3.5 rounded-xl border border-emerald-100 text-xs text-charcoal-800 leading-relaxed font-sans shadow-xs">
                    <p class="font-bold text-emerald-900" x-text="'*[MedinaStyle Promo]* ' + (title || '[Judul Kampanye]')"></p>
                    <p class="mt-2 text-charcoal-600">Assalamu'alaikum [Nama Pengguna],</p>
                    <p class="mt-2 text-charcoal-800 whitespace-pre-line" x-text="message || '[Isi pesan kampanye siaran Anda...]'"></p>
                    <p class="mt-2 text-[10px] text-charcoal-400 font-mono">Kunjungi: {{ url('/') }}</p>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.broadcasts.index') }}" class="px-5 py-2.5 bg-cream-200 text-charcoal-800 rounded-2xl text-xs font-bold">Batal</a>
                <button type="submit" class="px-7 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl border border-cream-400/30 transition-all">
                    Kirim Siaran Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
