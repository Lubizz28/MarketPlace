<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Marketplace Muslim') }} - @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-stone-800 h-full flex flex-col selection:bg-emerald-600 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-stone-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-stone-600 hover:bg-stone-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white font-serif font-bold text-lg shadow-sm">M</span>
                        <span class="text-lg font-serif font-bold text-emerald-950">Medina<span class="text-emerald-600">Style</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="text-xs font-semibold text-stone-600 hover:text-emerald-800 px-3 py-1.5 rounded-lg border border-stone-200 hover:bg-stone-50">Lihat Toko</a>
                    <div class="flex items-center space-x-2 pl-2 border-l border-stone-200">
                        <div class="w-8 h-8 rounded-full bg-emerald-800 text-white font-semibold text-xs flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="text-xs font-semibold text-stone-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 py-6 flex gap-8">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden md:block w-64 shrink-0">
            <div class="bg-white rounded-3xl p-5 border border-stone-200/80 shadow-soft sticky top-24 space-y-6">
                <!-- User Summary -->
                <div class="flex items-center space-x-3 pb-4 border-b border-stone-100">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-800 text-white font-bold flex items-center justify-center text-base shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <h3 class="font-bold text-stone-900 text-sm truncate">{{ auth()->user()->name }}</h3>
                        <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                            {{ auth()->user()->role->label() }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1 text-sm font-medium">
                    @if(auth()->user()->isAdmin())
                        <div class="text-[11px] font-bold uppercase tracking-wider text-stone-400 px-3 py-1">Admin Panel</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-50 text-purple-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                            <span>Dashboard Utama</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                            <span>Kelola Pengguna</span>
                        </a>
                        <div class="border-t border-stone-100 my-2"></div>
                    @endif

                    @if(auth()->user()->isReseller())
                        <div class="text-[11px] font-bold uppercase tracking-wider text-stone-400 px-3 py-1">Reseller Area</div>
                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('reseller.dashboard') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                            <span>Overview Reseller</span>
                        </a>
                        <div class="border-t border-stone-100 my-2"></div>
                    @endif

                    <div class="text-[11px] font-bold uppercase tracking-wider text-stone-400 px-3 py-1">Menu Akun</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.dashboard') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                        <span>Dashboard Member</span>
                    </a>
                    <a href="{{ route('member.profile') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.profile') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ route('member.addresses.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.addresses.*') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-stone-600 hover:bg-stone-50' }}">
                        <span>Buku Alamat</span>
                    </a>

                    <div class="border-t border-stone-100 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-3 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold">
                            <span>Keluar</span>
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Dashboard Content -->
        <main class="flex-1 min-w-0">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center space-x-2 text-sm shadow-xs mb-6">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center space-x-2 text-sm shadow-xs mb-6">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile Drawer for Dashboard -->
    <div x-show="sidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
        <div class="fixed inset-0 bg-stone-900/60" @click="sidebarOpen = false"></div>
        <div class="relative bg-white w-72 max-w-[80vw] h-full p-6 flex flex-col justify-between shadow-2xl z-50">
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-800 text-white font-bold flex items-center justify-center text-sm">M</span>
                        <span class="font-serif font-bold text-stone-900">MedinaStyle</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-1.5 text-stone-400 hover:text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="space-y-1 text-sm font-medium">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-xl text-purple-800 font-semibold bg-purple-50">Admin Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-xl text-stone-600 hover:bg-stone-50">Kelola Pengguna</a>
                    @endif
                    @if(auth()->user()->isReseller())
                        <a href="{{ route('reseller.dashboard') }}" class="block px-3 py-2 rounded-xl text-emerald-800 font-semibold bg-emerald-50">Reseller Overview</a>
                    @endif
                    <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Dashboard Member</a>
                    <a href="{{ route('member.profile') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Profil Saya</a>
                    <a href="{{ route('member.addresses.index') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Buku Alamat</a>
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-stone-700 hover:bg-stone-50">Lihat Toko</a>
                </nav>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 text-center bg-rose-50 text-rose-700 rounded-xl font-semibold text-sm">Keluar</button>
            </form>
        </div>
    </div>

    @livewireScripts
</body>
</html>
