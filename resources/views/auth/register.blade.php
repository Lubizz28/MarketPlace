@extends('layouts.guest')

@section('title', 'Pendaftaran Akun Eksklusif')
@section('heading', 'Bergabung Bersama Sulastika Jaya')
@section('subheading', 'Dapatkan akses eksklusif ke koleksi busana muslim terbaik atau raih sukses berbisnis.')

@section('content')
<div x-data="{ role: '{{ $isReseller ? 'reseller' : 'member' }}' }">
    <!-- Role Switcher Tabs in Frosted Glass -->
    <div class="flex rounded-2xl bg-cream-100/90 backdrop-blur-md p-1.5 mb-6 border border-cream-200">
        <button type="button" @click="role = 'member'"
            :class="role === 'member' ? 'bg-white text-charcoal-950 font-bold shadow-xs border border-cream-300' : 'text-charcoal-600 font-medium hover:text-charcoal-900'"
            class="flex-1 py-2.5 text-xs rounded-xl transition-all flex items-center justify-center space-x-2">
            <svg class="w-4 h-4 text-charcoal-900" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            <span>Akun Member (Belanja)</span>
        </button>
        <button type="button" @click="role = 'reseller'"
            :class="role === 'reseller' ? 'bg-emerald-950 text-gold-200 font-bold shadow-md border border-gold-400/40' : 'text-charcoal-600 font-medium hover:text-charcoal-900'"
            class="flex-1 py-2.5 text-xs rounded-xl transition-all flex items-center justify-center space-x-2">
            <svg class="w-4 h-4 text-gold-300" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            <span>Akun Reseller (Grosir)</span>
        </button>
    </div>

    <!-- Informational Banner for Reseller -->
    <div x-show="role === 'reseller'" x-cloak class="mb-5 p-4 bg-gold-50/70 border border-gold-300 rounded-2xl text-xs text-charcoal-950 space-y-1.5 shadow-xs">
        <p class="font-bold flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-gold-700 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            <span>Keistimewaan Mitra Reseller Sulastika Jaya:</span>
        </p>
        <p class="text-[11px] leading-relaxed text-charcoal-700 font-light">&bull; Potongan harga grosir langsung &amp; komisi referral otomatis.</p>
        <p class="text-[11px] leading-relaxed text-charcoal-700 font-light">&bull; Dompet saldo digital dengan penarikan dana transparan.</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="role" :value="role">

        <div>
            <label for="name" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-1.5">Nama Lengkap</label>
            <div class="relative">
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="cth. Siti Fatimah"
                    class="w-full bg-white/70 backdrop-blur-md border @error('name') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                </div>
            </div>
            @error('name')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="w-full bg-white/70 backdrop-blur-md border @error('email') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path></svg>
                </div>
            </div>
            @error('email')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-1.5">Nomor WhatsApp / HP</label>
            <div class="relative">
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                    placeholder="081234567890"
                    class="w-full bg-white/70 backdrop-blur-md border @error('phone') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"></path></svg>
                </div>
            </div>
            @error('phone')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-1.5">Kata Sandi</label>
            <div class="relative">
                <input type="password" id="password" name="password" required
                    placeholder="Min. 8 karakter (kombinasi huruf & angka)"
                    class="w-full bg-white/70 backdrop-blur-md border @error('password') border-rose-400 bg-rose-50/40 @else border-cream-300 @enderror rounded-2xl py-3 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                </div>
            </div>
            @error('password')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-[0.2em] text-charcoal-700 mb-1.5">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    placeholder="Ulangi kata sandi"
                    class="w-full bg-white/70 backdrop-blur-md border border-cream-300 rounded-2xl py-3 pl-11 pr-4 text-xs tracking-wide focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="pt-2">
            <label class="flex items-start space-x-2.5 text-xs text-charcoal-600 cursor-pointer">
                <input type="checkbox" name="terms" value="1" required class="w-4 h-4 mt-0.5 rounded text-charcoal-950 focus:ring-charcoal-900 border-cream-300">
                <span>Saya menyetujui <a href="#" class="font-bold text-cream-800 underline">Syarat &amp; Ketentuan</a> serta Kebijakan Privasi platform.</span>
            </label>
            @error('terms')
                <p class="text-rose-600 text-[11px] mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full py-4 px-6 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl shadow-xl border border-cream-400/30 hover:border-cream-300 transition-all text-xs uppercase tracking-widest flex items-center justify-center space-x-2 mt-2">
            <span x-text="role === 'reseller' ? 'Daftar Kemitraan Reseller' : 'Daftar Akun Member'"></span>
        </button>

        <div class="text-center pt-4 border-t border-cream-200/80">
            <p class="text-xs text-charcoal-600 font-light">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-charcoal-950 hover:underline">Masuk Sekarang</a>
            </p>
        </div>
    </form>
</div>
@endsection
