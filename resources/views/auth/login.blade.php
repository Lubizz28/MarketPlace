@extends('layouts.guest')

@section('title', 'Masuk Akun Eksklusif')
@section('heading', 'Selamat Datang Kembali')
@section('subheading', 'Masuk ke akun Anda untuk melanjutkan pengalaman berbelanja istimewa.')

@section('content')
<form method="POST" action="{{ route('login.post') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700 mb-2">
            Email atau Nomor WhatsApp
        </label>
        <div class="relative">
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@email.com atau 08123456789"
                class="w-full bg-sand-50/80 border @error('email') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3.5 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white focus:border-transparent transition-all shadow-xs">
        </div>
        @error('email')
            <p class="text-rose-600 text-[11px] mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-sand-700">
                Kata Sandi
            </label>
            <a href="#" class="text-[11px] font-bold text-gold-700 hover:text-gold-900 hover:underline">Lupa Sandi?</a>
        </div>
        <input type="password" id="password" name="password" required
            placeholder="••••••••"
            class="w-full bg-sand-50/80 border @error('password') border-rose-400 bg-rose-50/30 @else border-sand-200/90 @enderror rounded-2xl py-3.5 px-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-emerald-900 focus:bg-white focus:border-transparent transition-all shadow-xs">
        @error('password')
            <p class="text-rose-600 text-[11px] mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between pt-1">
        <label class="flex items-center space-x-2 text-xs text-sand-600 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-900 focus:ring-emerald-800 border-sand-300">
            <span>Ingat sesi masuk saya</span>
        </label>
    </div>

    <button type="submit" class="w-full py-4 px-6 bg-emerald-950 hover:bg-emerald-900 text-gold-300 font-bold rounded-2xl shadow-luxury border border-gold-500/30 hover:shadow-gold transition-all text-xs uppercase tracking-widest flex items-center justify-center space-x-2 mt-2">
        <span>Masuk Sekarang</span>
    </button>

    <div class="text-center pt-5 border-t border-sand-100">
        <p class="text-xs text-sand-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-emerald-950 hover:underline">Daftar Member</a>
            atau
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="font-bold text-gold-800 hover:underline">Gabung Reseller</a>
        </p>
    </div>
</form>
@endsection
