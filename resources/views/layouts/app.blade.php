<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Marketplace Muslim') }} - @yield('title', 'Busana Muslim & Fashion Premium')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-stone-800 flex flex-col min-h-full selection:bg-emerald-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-emerald-900 text-emerald-100 text-xs py-2 px-4 text-center font-medium tracking-wide">
        <span>✨ Koleksi Ramadhan & Hari Raya Terbaru Telah Hadir! Gratis Ongkir Min. Pembelian Rp150.000</span>
    </div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-stone-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Mobile Menu Button & Brand -->
                <div class="flex items-center space-x-3">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-stone-600 hover:text-emerald-800 hover:bg-stone-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white font-serif font-bold text-xl shadow-md">M</span>
                        <span class="text-xl sm:text-2xl font-serif font-bold tracking-tight text-emerald-950">Medina<span class="text-emerald-600">Style</span></span>
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Cari gamis, hijab, koko, mukena..." class="w-full bg-stone-100/80 border border-stone-200 rounded-full py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Right Actions (Cart, Wishlist, User Menu) -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-full hover:bg-stone-100 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-emerald-800 text-white font-semibold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <span class="hidden lg:inline-block text-sm font-medium text-stone-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-stone-100 py-2 z-50 text-sm">
                                <div class="px-4 py-2 border-b border-stone-100">
                                    <p class="font-semibold text-stone-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-stone-500 truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                                        {{ auth()->user()->role->label() }}
                                    </span>
                                </div>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-stone-50 font-medium text-purple-700">Admin Panel</a>
                                @endif
                                @if(auth()->user()->isReseller())
                                    <a href="{{ route('reseller.dashboard') }}" class="block px-4 py-2 hover:bg-stone-50 font-medium text-emerald-700">Reseller Hub</a>
                                @endif
                                <a href="{{ route('member.dashboard') }}" class="block px-4 py-2 hover:bg-stone-50 text-stone-700">Dashboard Member</a>
                                <a href="{{ route('member.profile') }}" class="block px-4 py-2 hover:bg-stone-50 text-stone-700">Profil & Akun</a>
                                <a href="{{ route('member.addresses.index') }}" class="block px-4 py-2 hover:bg-stone-50 text-stone-700">Buku Alamat</a>
                                <div class="border-t border-stone-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-medium">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold px-3.5 py-2 text-stone-700 hover:text-emerald-800 rounded-lg hover:bg-stone-100 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white rounded-full shadow-sm transition-all">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-stone-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <div class="relative mb-3">
                <input type="text" placeholder="Cari busana muslim..." class="w-full bg-stone-100 border border-stone-200 rounded-xl py-2.5 pl-10 pr-4 text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <a href="{{ route('home') }}" class="block text-base font-medium text-stone-800 py-1.5">Beranda</a>
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="block text-base font-semibold text-emerald-700 py-1.5">Program Reseller</a>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between text-sm shadow-xs mb-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between text-sm shadow-xs mb-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1 pb-16 md:pb-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-300 pt-12 pb-24 md:pb-12 mt-auto border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-serif font-bold text-lg">M</span>
                    <span class="text-xl font-serif font-bold text-white tracking-tight">Medina<span class="text-emerald-400">Style</span></span>
                </div>
                <p class="text-sm text-stone-400 leading-relaxed">Platform busana muslim dan fashion terpercaya dengan standar syar'i modern, bahan premium, dan harga bersahabat.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Program Mitra</h4>
                <ul class="space-y-2 text-sm text-stone-400">
                    <li><a href="{{ route('register', ['type' => 'reseller']) }}" class="hover:text-emerald-400">Gabung Reseller</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-emerald-400">Portal Reseller</a></li>
                    <li><a href="#" class="hover:text-emerald-400">Katalog Grosir</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Bantuan & Layanan</h4>
                <ul class="space-y-2 text-sm text-stone-400">
                    <li><a href="#" class="hover:text-emerald-400">Konfirmasi Pembayaran</a></li>
                    <li><a href="#" class="hover:text-emerald-400">Lacak Pengiriman</a></li>
                    <li><a href="#" class="hover:text-emerald-400">Kebijakan Privasi & Retur</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Keamanan & Pembayaran</h4>
                <p class="text-xs text-stone-400 mb-3">Transaksi aman terenkripsi 256-bit SSL dengan beragam metode pembayaran instan.</p>
                <div class="flex flex-wrap gap-2 text-xs text-stone-300">
                    <span class="px-2.5 py-1 bg-stone-800 rounded-md border border-stone-700">QRIS</span>
                    <span class="px-2.5 py-1 bg-stone-800 rounded-md border border-stone-700">BCA / Mandiri</span>
                    <span class="px-2.5 py-1 bg-stone-800 rounded-md border border-stone-700">GoPay / OVO</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-8 border-t border-stone-800 text-center text-xs text-stone-500">
            &copy; {{ date('Y') }} MedinaStyle Marketplace. Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-t border-stone-200 px-6 py-2 flex items-center justify-around shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('home') ? 'text-emerald-800 font-semibold' : 'text-stone-500' }}">
            <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Beranda</span>
        </a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('admin.*') ? 'text-purple-800 font-semibold' : 'text-stone-500' }}">
                    <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Admin</span>
                </a>
            @elseif(auth()->user()->isReseller())
                <a href="{{ route('reseller.dashboard') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('reseller.*') ? 'text-emerald-800 font-semibold' : 'text-stone-500' }}">
                    <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Reseller</span>
                </a>
            @else
                <a href="{{ route('member.dashboard') }}" class="flex flex-col items-center text-xs {{ request()->routeIs('member.*') ? 'text-emerald-800 font-semibold' : 'text-stone-500' }}">
                    <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Akun</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center text-xs text-stone-500">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    @livewireScripts
</body>
</html>
