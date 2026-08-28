@extends('layouts.guest')

@section('title', 'Masuk Akun Eksklusif')
@section('heading', 'Selamat Datang Kembali')
@section('subheading', 'Masuk ke akun Anda untuk menikmati pengalaman berbelanja istimewa.')

@section('content')
<form method="POST" action="{{ route('login.post') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-2">
            Email atau Nomor WhatsApp
        </label>
        <div class="relative">
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@email.com atau 08123456789"
                class="w-full bg-white/70 backdrop-blur-md border @error('email') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3.5 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white focus:border-transparent transition-all shadow-xs">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path></svg>
            </div>
        </div>
        @error('email')
            <p class="text-rose-600 text-[11px] mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700">
                Kata Sandi
            </label>
            <a href="#" class="text-[11px] font-bold text-cream-800 hover:text-charcoal-950 hover:underline">Lupa Sandi?</a>
        </div>
        <div class="relative">
            <input type="password" id="password" name="password" required
                placeholder="••••••••"
                class="w-full bg-white/70 backdrop-blur-md border @error('password') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3.5 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white focus:border-transparent transition-all shadow-xs">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
            </div>
        </div>
        @error('password')
            <p class="text-rose-600 text-[11px] mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between pt-1">
        <label class="flex items-center space-x-2 text-xs text-charcoal-600 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-charcoal-950 focus:ring-charcoal-900 border-cream-300">
            <span>Ingat sesi masuk saya</span>
        </label>
    </div>

    <button type="submit" class="w-full py-4 px-6 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl shadow-xl border border-cream-400/30 hover:border-cream-300 transition-all text-xs uppercase tracking-widest flex items-center justify-center space-x-2 mt-2">
        <span>Masuk Sekarang</span>
    </button>

    <div class="text-center pt-5 border-t border-cream-200/80">
        <p class="text-xs text-charcoal-600 font-light">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-charcoal-950 hover:underline">Daftar Member</a>
            atau
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="font-bold text-cream-800 hover:underline">Gabung Reseller</a>
        </p>
    </div>
</form>
@endsection
