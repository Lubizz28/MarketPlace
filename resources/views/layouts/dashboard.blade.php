<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-sand-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MedinaStyle') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-sand-900 h-full flex flex-col selection:bg-emerald-950 selection:text-gold-300" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-xl border-b border-sand-200/90 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-sand-600 hover:bg-sand-100 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-950 border border-gold-500/30 flex items-center justify-center text-gold-400 font-serif font-bold text-lg shadow-xs group-hover:scale-105 transition-transform">
                            <span class="text-gold-gradient">M</span>
                        </div>
                        <span class="text-xl font-serif font-bold text-emerald-950 tracking-tight">Medina<span class="font-normal italic text-gold-600">Style</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-wider text-sand-600 hover:text-emerald-950 px-4 py-2 rounded-full border border-sand-200 hover:bg-sand-50 transition-colors">
                        Lihat Katalog Toko
                    </a>
                    <div class="flex items-center space-x-3 pl-3 border-l border-sand-200">
                        <div class="w-9 h-9 rounded-full bg-emerald-950 text-gold-300 font-bold text-xs flex items-center justify-center border border-gold-500/30 shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-sand-900 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-gold-700 font-medium capitalize">{{ auth()->user()->role->label() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 py-8 flex gap-8">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden md:block w-64 shrink-0">
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-6 border border-sand-200/90 shadow-luxury sticky top-28 space-y-6">
                <!-- User Summary -->
                <div class="flex items-center space-x-3.5 pb-5 border-b border-sand-100">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-950 border border-gold-500/30 text-gold-300 font-bold flex items-center justify-center text-base shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <h3 class="font-bold text-sand-900 text-sm truncate">{{ auth()->user()->name }}</h3>
                        <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                            {{ auth()->user()->role->label() }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 text-xs font-semibold tracking-wide">
                    @if(auth()->user()->isAdmin())
                        <div class="text-[10px] font-bold uppercase tracking-[0.15em] text-sand-400 px-3 py-1">Admin Panel</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-purple-900 text-purple-100 font-bold shadow-xs' : 'text-sand-600 hover:bg-sand-50' }}">
                            <span>⚡</span><span>Dashboard Utama</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-purple-900 text-purple-100 font-bold shadow-xs' : 'text-sand-600 hover:bg-sand-50' }}">
                            <span>👥</span><span>Kelola Pengguna</span>
                        </a>
                        <div class="border-t border-sand-100 my-2"></div>
                    @endif

                    @if(auth()->user()->isReseller())
                        <div class="text-[10px] font-bold uppercase tracking-[0.15em] text-gold-700 px-3 py-1">Reseller Hub</div>
                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.dashboard') ? 'bg-emerald-950 text-gold-300 font-bold shadow-luxury border border-gold-500/30' : 'text-sand-600 hover:bg-sand-50' }}">
                            <span>💼</span><span>Overview Reseller</span>
                        </a>
                        <div class="border-t border-sand-100 my-2"></div>
                    @endif

                    <div class="text-[10px] font-bold uppercase tracking-[0.15em] text-sand-400 px-3 py-1">Akun Saya</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.dashboard') ? 'bg-emerald-950 text-gold-300 font-bold shadow-luxury border border-gold-500/30' : 'text-sand-600 hover:bg-sand-50' }}">
                        <span>📊</span><span>Dashboard Belanja</span>
                    </a>
                    <a href="{{ route('member.profile') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.profile') ? 'bg-emerald-950 text-gold-300 font-bold shadow-luxury border border-gold-500/30' : 'text-sand-600 hover:bg-sand-50' }}">
                        <span>👤</span><span>Profil & Informasi</span>
                    </a>
                    <a href="{{ route('member.addresses.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.addresses.*') ? 'bg-emerald-950 text-gold-300 font-bold shadow-luxury border border-gold-500/30' : 'text-sand-600 hover:bg-sand-50' }}">
                        <span>📍</span><span>Buku Alamat</span>
                    </a>

                    <div class="border-t border-sand-100 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl text-rose-600 hover:bg-rose-50 font-bold transition-colors">
                            <span>🚪</span><span>Keluar Akun</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Dashboard View Area -->
        <main class="flex-1 min-w-0">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-900/10 border border-emerald-800/20 text-emerald-950 px-5 py-3.5 rounded-2xl flex items-center space-x-2.5 text-xs font-medium shadow-xs mb-6">
                    <span class="w-5 h-5 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-5 py-3.5 rounded-2xl flex items-center space-x-2.5 text-xs font-medium shadow-xs mb-6">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white flex items-center justify-center text-xs">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer for Dashboard -->
    <div x-show="sidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
        <div class="fixed inset-0 bg-sand-950/60 backdrop-blur-xs" @click="sidebarOpen = false"></div>
        <div class="relative bg-white w-72 max-w-[85vw] h-full p-6 flex flex-col justify-between shadow-2xl z-50 border-r border-sand-200">
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-sand-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-950 text-gold-400 font-serif font-bold text-sm flex items-center justify-center">M</div>
                        <span class="font-serif font-bold text-sand-900">MedinaStyle</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-1.5 text-sand-400 hover:text-sand-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-purple-900 font-bold bg-purple-50">⚡ Admin Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sand-600 hover:bg-sand-50">👥 Kelola Pengguna</a>
                    @endif
                    @if(auth()->user()->isReseller())
                        <a href="{{ route('reseller.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-gold-900 font-bold bg-gold-50">💼 Portal Reseller</a>
                    @endif
                    <a href="{{ route('member.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sand-700 hover:bg-sand-50">📊 Dashboard Member</a>
                    <a href="{{ route('member.profile') }}" class="block px-3.5 py-2.5 rounded-xl text-sand-700 hover:bg-sand-50">👤 Profil Saya</a>
                    <a href="{{ route('member.addresses.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sand-700 hover:bg-sand-50">📍 Buku Alamat</a>
                    <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-xl text-sand-700 hover:bg-sand-50">🛍️ Lihat Toko</a>
                </nav>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 text-center bg-rose-50 text-rose-700 rounded-2xl font-bold text-xs">Keluar Akun</button>
            </form>
        </div>
    </div>

    @livewireScripts
</body>
</html>
