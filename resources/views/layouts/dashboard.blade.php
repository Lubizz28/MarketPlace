<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MedinaStyle') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 h-full flex flex-col selection:bg-charcoal-950 selection:text-cream-200" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar in Frosted Glass -->
    <header class="bg-white/75 backdrop-blur-xl border-b border-cream-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-charcoal-600 hover:bg-cream-200/50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-charcoal-950 border border-cream-400/40 flex items-center justify-center text-cream-300 font-display font-bold text-lg shadow-xs group-hover:scale-105 transition-transform">
                            <span class="text-cream-gradient">M</span>
                        </div>
                        <span class="text-xl font-display font-bold text-charcoal-950 tracking-tight">MEDINA<span class="font-serif italic text-cream-600 font-normal ml-0.5">Style</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-wider text-charcoal-600 hover:text-charcoal-950 px-4 py-2 rounded-full border border-cream-300 hover:bg-white/80 transition-colors">
                        Lihat Katalog Toko
                    </a>
                    <div class="flex items-center space-x-3 pl-3 border-l border-cream-200">
                        <div class="w-9 h-9 rounded-full bg-charcoal-950 text-cream-300 font-bold text-xs flex items-center justify-center border border-cream-400/30 shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-charcoal-900 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-cream-600 font-medium capitalize">{{ auth()->user()->role->label() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 py-8 flex gap-8">
        <!-- Desktop Frosted Glass Sidebar -->
        <aside class="hidden md:block w-64 shrink-0">
            <div class="bg-white/80 backdrop-blur-2xl rounded-3xl p-6 border border-cream-200/90 shadow-xl sticky top-28 space-y-6">
                <!-- User Summary -->
                <div class="flex items-center space-x-3.5 pb-5 border-b border-cream-100">
                    <div class="w-12 h-12 rounded-2xl bg-charcoal-950 border border-cream-400/30 text-cream-300 font-bold flex items-center justify-center text-base shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <h3 class="font-bold text-charcoal-900 text-sm truncate">{{ auth()->user()->name }}</h3>
                        <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                            {{ auth()->user()->role->label() }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 text-xs font-semibold tracking-wide">
                    @if(auth()->user()->isAdmin())
                        <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 px-3 py-1">Admin Panel</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                            <span>Dashboard Utama</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            <span>Kelola Pengguna</span>
                        </a>
                        <div class="border-t border-cream-100 my-2"></div>
                    @endif

                    @if(auth()->user()->isReseller())
                        <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700 px-3 py-1">Reseller Hub</div>
                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.dashboard') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                            <span>Overview Reseller</span>
                        </a>
                        <div class="border-t border-cream-100 my-2"></div>
                    @endif

                    <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 px-3 py-1">Akun Saya</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.dashboard') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path></svg>
                        <span>Dashboard Belanja</span>
                    </a>
                    <a href="{{ route('member.profile') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.profile') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                        <span>Profil &amp; Informasi</span>
                    </a>
                    <a href="{{ route('member.orders.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.orders.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span>Pesanan Saya</span>
                    </a>
                    <a href="{{ route('member.addresses.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.addresses.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                        <span>Buku Alamat</span>
                    </a>
                    <a href="{{ route('member.wishlist.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.wishlist.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path></svg>
                        <span>Wishlist Favorit</span>
                    </a>

                    <div class="border-t border-cream-100 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl text-rose-600 hover:bg-rose-50 font-bold transition-colors">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                            <span>Keluar Akun</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Dashboard View Area in Frosted Glass -->
        <main class="flex-1 min-w-0">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-950/10 backdrop-blur-md border border-emerald-800/20 text-emerald-950 px-5 py-3.5 rounded-2xl flex items-center space-x-2.5 text-xs font-medium shadow-xs mb-6">
                    <span class="w-5 h-5 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50/90 backdrop-blur-md border border-rose-200 text-rose-900 px-5 py-3.5 rounded-2xl flex items-center space-x-2.5 text-xs font-medium shadow-xs mb-6">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white flex items-center justify-center text-xs font-bold">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer for Dashboard -->
    <div x-show="sidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
        <div class="fixed inset-0 bg-charcoal-950/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
        <div class="relative bg-white/95 backdrop-blur-2xl w-72 max-w-[85vw] h-full p-6 flex flex-col justify-between shadow-2xl z-50 border-r border-cream-200">
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-cream-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-charcoal-950 text-cream-300 font-display font-bold text-sm flex items-center justify-center">M</div>
                        <span class="font-display font-bold text-charcoal-950">MedinaStyle</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-1.5 text-charcoal-400 hover:text-charcoal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-950 font-bold bg-cream-200/80">Admin Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-600 hover:bg-cream-100">Kelola Pengguna</a>
                    @endif
                    @if(auth()->user()->isReseller())
                        <a href="{{ route('reseller.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-950 font-bold bg-cream-200/80">Portal Reseller</a>
                    @endif
                    <a href="{{ route('member.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Dashboard Member</a>
                    <a href="{{ route('member.profile') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Profil Saya</a>
                    <a href="{{ route('member.addresses.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Buku Alamat</a>
                    <a href="{{ route('home') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Lihat Toko</a>
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
