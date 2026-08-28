<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MedinaStyle') }} — @yield('title', 'Koleksi Busana Muslim & Modest Fashion Haute Couture')</title>

    <!-- Professional Fonts: Cinzel + Cormorant Garamond + Plus Jakarta Sans + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 bg-cream-100/60 flex flex-col min-h-full selection:bg-charcoal-900 selection:text-cream-200" x-data="{ mobileMenuOpen: false }">

    <!-- Top Luxury Announcement Banner in Deep Charcoal with Cream Accent -->
    <div class="bg-charcoal-950 text-cream-300 text-[11px] py-2.5 px-4 text-center font-medium tracking-[0.18em] border-b border-charcoal-800/60 relative overflow-hidden">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-3">
            <svg class="w-3 h-3 text-cream-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            <span class="uppercase font-semibold text-[10px] sm:text-[11px]">Koleksi Mahakarya Syar'i 2026 &bull; Kemewahan Bahan Sutra &amp; Voal Premium</span>
            <svg class="w-3 h-3 text-cream-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
        </div>
    </div>

    <!-- Header Navigation with Frosted Glassmorphism -->
    <header class="sticky top-0 z-40 bg-white/75 backdrop-blur-xl border-b border-cream-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand & Mobile Menu Button -->
                <div class="flex items-center space-x-4">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 text-charcoal-700 hover:text-charcoal-950 hover:bg-cream-200/60 rounded-2xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-3.5 group">
                        <div class="w-10 h-10 rounded-2xl bg-charcoal-950 border border-cream-400/40 flex items-center justify-center text-cream-300 font-display font-bold text-xl shadow-xs group-hover:scale-105 transition-transform">
                            <span class="text-cream-gradient">M</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl sm:text-2xl font-display font-bold tracking-tight text-charcoal-950 leading-none">
                                MEDINA<span class="font-serif italic text-cream-600 font-normal ml-0.5">Style</span>
                            </span>
                            <span class="text-[8px] uppercase tracking-[0.3em] text-charcoal-500 font-medium mt-1">Haute Modestie</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Search Bar in Frosted Glass -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Cari gamis sutra, abaya bordir, koko eksklusif..."
                            class="w-full bg-cream-50/80 backdrop-blur-md border border-cream-200/90 rounded-full py-2.5 pl-11 pr-4 text-xs tracking-wide placeholder:text-charcoal-400 focus:outline-none focus:ring-2 focus:ring-charcoal-900 focus:bg-white focus:border-transparent transition-all shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-charcoal-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('register', ['type' => 'reseller']) }}" class="hidden lg:inline-flex items-center space-x-2 px-4 py-2 rounded-full border border-cream-300 bg-white/70 backdrop-blur-md text-[11px] font-bold text-charcoal-800 hover:bg-white hover:border-cream-400 transition-all shadow-xs">
                        <svg class="w-3.5 h-3.5 text-cream-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                        <span>Kemitraan Reseller</span>
                    </a>

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2.5 p-1 rounded-full hover:bg-cream-200/50 transition-colors border border-transparent hover:border-cream-300">
                                <div class="w-9 h-9 rounded-full bg-charcoal-950 text-cream-300 font-bold text-xs flex items-center justify-center border border-cream-400/30 shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:flex flex-col text-left">
                                    <span class="text-xs font-bold text-charcoal-900 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] text-cream-600 font-medium capitalize">{{ auth()->user()->role->label() }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-charcoal-400 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path></svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white/90 backdrop-blur-2xl rounded-3xl shadow-xl border border-cream-200/90 py-2.5 z-50 text-xs">
                                <div class="px-5 py-3.5 border-b border-cream-100">
                                    <p class="font-bold text-charcoal-900 truncate text-sm">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-charcoal-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-2 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                                        {{ auth()->user()->role->label() }}
                                    </span>
                                </div>

                                <div class="py-1.5">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-5 py-2.5 hover:bg-charcoal-100 text-charcoal-950 font-bold">
                                            <svg class="w-4 h-4 text-charcoal-700" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                                            <span>Admin Panel</span>
                                        </a>
                                    @endif
                                    @if(auth()->user()->isReseller())
                                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center space-x-2.5 px-5 py-2.5 hover:bg-cream-100 text-charcoal-950 font-bold">
                                            <svg class="w-4 h-4 text-cream-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                            <span>Portal Reseller</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-2.5 px-5 py-2.5 hover:bg-cream-100/60 text-charcoal-700 font-medium">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path></svg>
                                        <span>Dashboard Member</span>
                                    </a>
                                    <a href="{{ route('member.profile') }}" class="flex items-center space-x-2.5 px-5 py-2.5 hover:bg-cream-100/60 text-charcoal-700 font-medium">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                        <span>Profil &amp; Akun</span>
                                    </a>
                                    <a href="{{ route('member.addresses.index') }}" class="flex items-center space-x-2.5 px-5 py-2.5 hover:bg-cream-100/60 text-charcoal-700 font-medium">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                                        <span>Buku Alamat</span>
                                    </a>
                                </div>

                                <div class="border-t border-cream-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-5 py-2.5 text-rose-600 hover:bg-rose-50 font-bold flex items-center space-x-2.5">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                                        <span>Keluar Akun</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest px-4 py-2.5 text-charcoal-800 hover:text-charcoal-950 rounded-full hover:bg-cream-200/60 transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="text-xs font-bold uppercase tracking-widest px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-full shadow-md border border-cream-400/30 transition-all hover:border-cream-300">
                                Bergabung
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-cream-200 bg-white/95 backdrop-blur-2xl px-5 pt-4 pb-6 space-y-3">
            <div class="relative mb-3">
                <input type="text" placeholder="Cari busana syar'i..." class="w-full bg-cream-50 border border-cream-200 rounded-2xl py-2.5 pl-10 pr-4 text-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-charcoal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </div>
            </div>
            <a href="{{ route('home') }}" class="block text-xs font-bold uppercase tracking-wider text-charcoal-800 py-2 border-b border-cream-100">Beranda Toko</a>
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="block text-xs font-bold uppercase tracking-wider text-cream-800 py-2 border-b border-cream-100">Program Kemitraan Reseller</a>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="bg-emerald-950/10 border border-emerald-800/20 text-emerald-950 px-5 py-3.5 rounded-2xl flex items-center justify-between text-xs font-medium shadow-xs mb-4">
                <div class="flex items-center space-x-2.5">
                    <span class="w-5 h-5 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-900 px-5 py-3.5 rounded-2xl flex items-center justify-between text-xs font-medium shadow-xs mb-4">
                <div class="flex items-center space-x-2.5">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white flex items-center justify-center text-xs font-bold">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-1 pb-24 md:pb-12">
        @yield('content')
    </main>

    <!-- Premium Luxury Footer in Charcoal & Cream -->
    <footer class="bg-charcoal-950 text-cream-300 pt-16 pb-28 md:pb-16 mt-auto border-t border-charcoal-800/80 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 relative z-10">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-charcoal-900 border border-cream-400/40 flex items-center justify-center text-cream-300 font-display font-bold text-lg shadow-sm">
                        M
                    </div>
                    <span class="text-2xl font-display font-bold text-white tracking-tight">MEDINA<span class="text-cream-400 italic font-serif font-normal ml-0.5">Style</span></span>
                </div>
                <p class="text-xs text-charcoal-400 leading-relaxed font-light">
                    Brand busana muslim &amp; modest fashion premium dengan standar keanggunan syar'i, jahitan butik berkelas, serta material kain impor terpilih.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-3 text-cream-300 text-xs font-medium tracking-wider uppercase">
                    <span>&bull; Standar Butik</span>
                    <span>&bull; Garansi Syar'i</span>
                    <span>&bull; 100% Halal</span>
                </div>
            </div>

            <div>
                <h4 class="text-cream-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-4">Kemitraan &amp; Bisnis</h4>
                <ul class="space-y-2.5 text-xs text-charcoal-400">
                    <li><a href="{{ route('register', ['type' => 'reseller']) }}" class="hover:text-cream-200 transition-colors">Daftar Mitra Reseller</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-cream-200 transition-colors">Portal Login Reseller</a></li>
                    <li><a href="#" class="hover:text-cream-200 transition-colors">Ketentuan Komisi &amp; Grosir</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-cream-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-4">Layanan Pelanggan</h4>
                <ul class="space-y-2.5 text-xs text-charcoal-400">
                    <li><a href="#" class="hover:text-cream-200 transition-colors">Panduan Ukuran (Size Chart)</a></li>
                    <li><a href="#" class="hover:text-cream-200 transition-colors">Lacak Status Pengiriman</a></li>
                    <li><a href="#" class="hover:text-cream-200 transition-colors">Garansi &amp; Kebijakan Retur</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-cream-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-4">Metode Pembayaran</h4>
                <p class="text-xs text-charcoal-400 mb-4 leading-relaxed font-light">Seluruh transaksi terenkripsi aman dan diverifikasi secara instan.</p>
                <div class="grid grid-cols-3 gap-2 text-[10px] text-cream-200 font-mono">
                    <span class="py-2 px-2 bg-charcoal-900 border border-charcoal-700/60 rounded-xl text-center font-bold">QRIS</span>
                    <span class="py-2 px-2 bg-charcoal-900 border border-charcoal-700/60 rounded-xl text-center font-bold">BCA VA</span>
                    <span class="py-2 px-2 bg-charcoal-900 border border-charcoal-700/60 rounded-xl text-center font-bold">MANDIRI</span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-charcoal-900 text-center text-[11px] text-charcoal-500 font-light">
            &copy; {{ date('Y') }} MedinaStyle Haute Modestie. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    <!-- Mobile Frosted Glass Floating Bottom Bar -->
    <nav class="md:hidden fixed bottom-3 left-4 right-4 z-50 glass-bottom-bar rounded-3xl px-6 py-3 flex items-center justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('home') ? 'text-cream-300 font-bold' : 'text-charcoal-400' }}">
            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path></svg>
            <span>Beranda</span>
        </a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('admin.*') ? 'text-cream-300 font-bold' : 'text-charcoal-400' }}">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                    <span>Admin</span>
                </a>
            @elseif(auth()->user()->isReseller())
                <a href="{{ route('reseller.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('reseller.*') ? 'text-cream-300 font-bold' : 'text-charcoal-400' }}">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                    <span>Reseller</span>
                </a>
            @else
                <a href="{{ route('member.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('member.*') ? 'text-cream-300 font-bold' : 'text-charcoal-400' }}">
                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                    <span>Akun</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center text-[10px] text-charcoal-400">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    @livewireScripts
</body>
</html>
