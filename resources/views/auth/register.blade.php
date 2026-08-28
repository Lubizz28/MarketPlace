@extends('layouts.guest')

@section('title', 'Pendaftaran Akun Eksklusif')
@section('heading', 'Bergabung Bersama MedinaStyle')
@section('subheading', 'Dapatkan akses eksklusif ke koleksi busana muslim terbaik atau raih sukses berbisnis.')

@section('content')
<div x-data="{ role: '{{ $isReseller ? 'reseller' : 'member' }}' }">
    <!-- Role Switcher Tabs -->
    <div class="flex rounded-2xl bg-sand-100/90 p-1.5 mb-6 border border-sand-200/80">
        <button type="button" @click="role = 'member'"
            :class="role === 'member' ? 'bg-white text-emerald-950 font-bold shadow-xs border border-sand-200' : 'text-sand-600 font-medium hover:text-sand-900'"
            class="flex-1 py-2.5 text-xs rounded-xl transition-all text-center">
            🛍️ Akun Member (Belanja)
        </button>
        <button type="button" @click="role = 'reseller'"
            :class="role === 'reseller' ? 'bg-emerald-950 text-gold-300 font-bold shadow-luxury border border-gold-500/30' : 'text-sand-600 font-medium hover:text-sand-900'"
            class="flex-1 py-2.5 text-xs rounded-xl transition-all text-center">
            💼 Akun Reseller (Grosir)
        </button>
    </div>

    <!-- Informational Banner for Reseller -->
    <div x-show="role === 'reseller'" x-cloak class="mb-5 p-4 bg-gold-50/80 border border-gold-400/50 rounded-2xl text-xs text-gold-900 space-y-1.5 shadow-xs">
        <p class="font-bold flex items-center space-x-1.5">
            <span class="text-gold-600">✦</span>
            <span>Keistimewaan Mitra Reseller MedinaStyle:</span>
        </p>
        <p class="text-[11px] leading-relaxed text-sand-700">• Dapatkan potongan harga grosir langsung & komisi referral otomatis.</p>
        <p class="text-[11px] leading-relaxed text-sand-700">• Dompet saldo digital dengan penarikan dana transparan.</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="role" :value="role">

        <div>
            <label for="name" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-1.5">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                placeholder="cth. Siti Fatimah"
                class="w-full bg-sand-50/80 border @error('name') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white transition-all shadow-xs">
            @error('name')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-1.5">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                placeholder="nama@email.com"
                class="w-full bg-sand-50/80 border @error('email') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white transition-all shadow-xs">
            @error('email')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-1.5">Nomor WhatsApp / HP</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                placeholder="081234567890"
                class="w-full bg-sand-50/80 border @error('phone') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white transition-all shadow-xs">
            @error('phone')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-1.5">Kata Sandi</label>
            <input type="password" id="password" name="password" required
                placeholder="Min. 8 karakter (kombinasi huruf & angka)"
                class="w-full bg-sand-50/80 border @error('password') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white transition-all shadow-xs">
            @error('password')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                placeholder="Ulangi kata sandi"
                class="w-full bg-sand-50/80 border border-sand-200/90 rounded-2xl py-3 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white transition-all shadow-xs">
        </div>

        <div class="pt-2">
            <label class="flex items-start space-x-2.5 text-xs text-sand-600 cursor-pointer">
                <input type="checkbox" name="terms" value="1" required class="w-4 h-4 mt-0.5 rounded text-emerald-900 focus:ring-emerald-800 border-sand-300">
                <span>Saya menyetujui <a href="#" class="font-bold text-gold-800 underline">Syarat & Ketentuan</a> serta Kebijakan Privasi platform.</span>
            </label>
            @error('terms')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full py-4 px-6 bg-emerald-950 hover:bg-emerald-900 text-gold-300 font-bold rounded-2xl shadow-luxury border border-gold-500/30 hover:shadow-gold transition-all text-xs uppercase tracking-widest flex items-center justify-center space-x-2 mt-2">
            <span x-text="role === 'reseller' ? 'Daftar Kemitraan Reseller' : 'Daftar Akun Member'"></span>
        </button>

        <div class="text-center pt-4 border-t border-sand-100">
            <p class="text-xs text-sand-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-950 hover:underline">Masuk Sekarang</a>
            </p>
        </div>
    </form>
</div>
@endsection
