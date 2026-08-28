@extends('layouts.guest')

@section('title', 'Masuk Akun')
@section('heading', 'Selamat Datang Kembali')
@section('subheading', 'Masuk ke akun Anda untuk melanjutkan belanja atau mengelola kemitraan.')

@section('content')
<form method="POST" action="{{ route('login.post') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Email atau Nomor Telepon</label>
        <div class="relative">
            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@email.com atau 08123456789"
                class="w-full bg-stone-50 border @error('email') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
        </div>
        @error('email')
            <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-700">Kata Sandi</label>
            <a href="#" class="text-xs font-semibold text-emerald-800 hover:underline">Lupa Sandi?</a>
        </div>
        <input type="password" id="password" name="password" required
            placeholder="••••••••"
            class="w-full bg-stone-50 border @error('password') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
        @error('password')
            <p class="text-rose-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2 text-sm text-stone-600 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700 border-stone-300">
            <span>Ingat saya</span>
        </label>
    </div>

    <button type="submit" class="w-full py-3.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-2xl shadow-md hover:shadow-lg transition-all text-sm flex items-center justify-center space-x-2">
        <span>Masuk Sekarang</span>
    </button>

    <div class="text-center pt-4 border-t border-stone-100">
        <p class="text-sm text-stone-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-emerald-800 hover:underline">Daftar Member</a>
            atau
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="font-bold text-emerald-800 hover:underline">Gabung Reseller</a>
        </p>
    </div>
</form>
@endsection
