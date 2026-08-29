@extends('layouts.dashboard')

@section('title', 'Profil & Rekening Payout — Reseller Hub')

@section('content')
<div class="max-w-3xl space-y-6">
    <!-- Header -->
    <div>
        <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-cream-700">Pengaturan Kemitraan</span>
        <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Profil Bisnis &amp; Rekening Bank</h1>
        <p class="text-xs text-charcoal-500 font-light mt-1">Lengkapi nama toko mitra, kode referral kustom, dan rekening bank pencairan komisi Anda.</p>
    </div>

    <!-- Form in Glass Card -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-6">
        <form method="POST" action="{{ route('reseller.profile.update') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Nama Toko / Usaha Reseller (Opsional)
                </label>
                <input type="text" name="store_name" value="{{ old('store_name', $user->resellerProfile?->store_name) }}" placeholder="Contoh: Butik Muslimah Bandung"
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                @error('store_name')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                    Kode Referral Kustom <span class="text-rose-500">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    <span class="font-mono text-xs text-charcoal-400 font-bold bg-cream-100 px-3 py-2.5 rounded-xl border border-cream-200">{{ url('/?ref=') }}</span>
                    <input type="text" name="referral_code" value="{{ old('referral_code', $user->resellerProfile?->referral_code ?? 'RES' . strtoupper(Str::random(5))) }}" required
                        class="flex-1 bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs font-mono font-bold uppercase text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                </div>
                <p class="text-[10px] text-charcoal-400 mt-1">Kode unik yang digunakan pembeli lewat tautan referral Anda.</p>
                @error('referral_code')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-cream-200">
                <h3 class="font-display font-bold text-charcoal-950 text-sm mb-3">Informasi Rekening Bank Pencairan Komisi</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Nama Bank <span class="text-rose-500">*</span>
                        </label>
                        <select name="bank_name" required class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                            @foreach(['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI (Bank Syariah Indonesia)', 'CIMB Niaga', 'Bank Muamalat'] as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name', $user->resellerProfile?->bank_name) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Nomor Rekening Bank <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->resellerProfile?->bank_account_number) }}" required placeholder="Contoh: 1234567890"
                            class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs font-mono font-bold text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        @error('bank_account_number')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Nama Pemilik Rekening Sesuai Buku Tabungan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->resellerProfile?->bank_account_name ?? $user->name) }}" required
                            class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        @error('bank_account_name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-7 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl border border-cream-400/30 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
