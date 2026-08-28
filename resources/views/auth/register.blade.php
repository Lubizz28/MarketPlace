@extends('layouts.guest')

@section('title', 'Pendaftaran Akun')
@section('heading', 'Bergabung Bersama Kami')
@section('subheading', 'Nikmati kemudahan belanja busana muslim premium atau raih penghasilan sebagai reseller.')

@section('content')
<div x-data="{ role: '{{ $isReseller ? 'reseller' : 'member' }}' }">
    <!-- Role Switcher Tabs -->
    <div class="flex rounded-2xl bg-stone-100 p-1.5 mb-6">
        <button type="button" @click="role = 'member'"
            :class="role === 'member' ? 'bg-white text-emerald-950 font-bold shadow-xs' : 'text-stone-600 font-medium hover:text-stone-900'"
            class="flex-1 py-2 text-xs rounded-xl transition-all text-center">
            🛍️ Akun Member (Belanja)
        </button>
        <button type="button" @click="role = 'reseller'"
            :class="role === 'reseller' ? 'bg-emerald-800 text-white font-bold shadow-sm' : 'text-stone-600 font-medium hover:text-stone-900'"
            class="flex-1 py-2 text-xs rounded-xl transition-all text-center">
            💼 Akun Reseller (Bisnis)
        </button>
    </div>

    <!-- Informational Banner for Reseller -->
    <div x-show="role === 'reseller'" x-cloak class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-900 space-y-1">
        <p class="font-bold">✨ Keuntungan Mitra Reseller:</p>
        <p>• Dapatkan diskon harga grosir khusus & komisi referral.</p>
        <p>• Dompet saldo dan fasilitas penarikan dana instan.</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="role" :value="role">

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                placeholder="cth. Siti Fatimah"
                class="w-full bg-stone-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('name')
                <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                placeholder="nama@email.com"
                class="w-full bg-stone-50 border @error('email') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('email')
                <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Nomor WhatsApp / HP</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                placeholder="081234567890"
                class="w-full bg-stone-50 border @error('phone') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('phone')
                <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Kata Sandi</label>
            <input type="password" id="password" name="password" required
                placeholder="Min. 8 karakter (kombinasi huruf & angka)"
                class="w-full bg-stone-50 border @error('password') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('password')
                <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                placeholder="Ulangi kata sandi"
                class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
        </div>

        <div class="pt-2">
            <label class="flex items-start space-x-2.5 text-xs text-stone-600 cursor-pointer">
                <input type="checkbox" name="terms" value="1" required class="w-4 h-4 mt-0.5 rounded text-emerald-800 focus:ring-emerald-700 border-stone-300">
                <span>Saya menyetujui <a href="#" class="font-semibold text-emerald-800 underline">Syarat & Ketentuan</a> serta Kebijakan Privasi platform.</span>
            </label>
            @error('terms')
                <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-2xl shadow-md hover:shadow-lg transition-all text-sm flex items-center justify-center space-x-2">
            <span x-text="role === 'reseller' ? 'Daftar Sebagai Reseller' : 'Daftar Akun Member'"></span>
        </button>

        <div class="text-center pt-4 border-t border-stone-100">
            <p class="text-sm text-stone-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-800 hover:underline">Masuk Sekarang</a>
            </p>
        </div>
    </form>
</div>
@endsection
