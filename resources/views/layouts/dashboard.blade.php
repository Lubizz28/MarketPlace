<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sulastika Jaya') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 h-full flex flex-col selection:bg-emerald-950 selection:text-gold-200" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Bar in Frosted Glass -->
    <header class="bg-white/85 backdrop-blur-xl border-b border-cream-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-charcoal-600 hover:bg-cream-200/50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-950 border border-gold-400/50 flex items-center justify-center text-gold-300 font-display font-bold text-sm sm:text-base shadow-xs group-hover:scale-105 transition-transform">
                            <span class="text-gold-gradient">SJ</span>
                        </div>
                        <span class="text-lg sm:text-xl font-display font-bold text-emerald-950 tracking-tight">SULASTIKA<span class="font-serif italic text-gold-700 font-normal ml-1">Jaya</span></span>
                    </a>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-wider text-emerald-800 hover:text-emerald-950 px-3.5 py-1.5 rounded-full border border-gold-300/80 hover:bg-gold-50/60 transition-colors">
                        Katalog Toko
                    </a>
                    <div class="flex items-center space-x-2.5 pl-2.5 border-l border-cream-200">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-emerald-950 text-gold-300 font-bold text-xs flex items-center justify-center border border-gold-400/30 shadow-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-bold text-charcoal-900 leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-emerald-800 font-medium capitalize">{{ auth()->user()->role->label() }}</span>
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
                        <a href="{{ route('admin.analytics.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            <span>Laporan &amp; Analitik</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span>Kelola Pesanan</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                            <span>Katalog Produk</span>
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.inventory.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                            <span>Stok &amp; Gudang</span>
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            <span>Pelanggan &amp; CRM</span>
                        </a>
                        <a href="{{ route('admin.resellers.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.resellers.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                            <span>Kelola Reseller</span>
                        </a>
                        <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.withdrawals.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Penarikan Dana</span>
                        </a>
                        <a href="{{ route('admin.broadcasts.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.broadcasts.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.063.046-.128.09-.194.135a5.986 5.986 0 01-4.048 1.025A5.99 5.99 0 011.5 12a5.99 5.99 0 014.598-5c1.436-.264 2.87.123 4.048 1.025.066.045.131.089.194.135m0 7.68l6.326 3.652A1.5 1.5 0 0019 18.2V5.8a1.5 1.5 0 00-2.336-1.282L10.34 8.16m0 7.68V8.16"/></svg>
                            <span>Pesan Siaran</span>
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.75l-15 15a2.25 2.25 0 003.182 3.182l15-15a2.25 2.25 0 00-3.182-3.182z"/></svg>
                            <span>Kelola Kupon Promo</span>
                        </a>
                        <a href="{{ route('admin.points.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.points.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>Log Poin Loyalitas</span>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v8.25m0-8.25a60.073 60.073 0 0115.797-2.101c.727-.198 1.453.342 1.453 1.096V6m0 0v8.25m0-8.25a60.075 60.075 0 00-15.797 2.101M21 14.25v.75A.75.75 0 0120.25 16h-.75m0 0v2.75M21 6v8.25m-18 0v2.75m0-2.75h18"/></svg>
                            <span>Log Pembayaran</span>
                        </a>

                        <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 px-3 py-1 mt-2">CMS &amp; Konten</div>
                        <a href="{{ route('admin.cms.banners.index') }}" class="flex items-center space-x-2.5 px-3.5 py-2.5 rounded-2xl transition-all {{ request()->routeIs('admin.cms.banners.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span>Banner &amp; Slider</span>
                        </a>
                        <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center space-x-2.5 px-3.5 py-2.5 rounded-2xl transition-all {{ request()->routeIs('admin.cms.pages.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <span>Halaman Statis</span>
                        </a>
                        <a href="{{ route('admin.cms.posts.index') }}" class="flex items-center space-x-2.5 px-3.5 py-2.5 rounded-2xl transition-all {{ request()->routeIs('admin.cms.posts.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                            <span>Blog &amp; Panduan Syar'i</span>
                        </a>

                        <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 px-3 py-1 mt-2">Sistem &amp; Audit</div>
                        <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center space-x-2.5 px-3.5 py-2.5 rounded-2xl transition-all {{ request()->routeIs('admin.activity-logs.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                            <span>Log Audit Aktivitas</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-2.5 px-3.5 py-2.5 rounded-2xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Pengaturan Toko &amp; Sistem</span>
                        </a>
                        <div class="border-t border-cream-100 my-2"></div>
                    @endif

                    @if(auth()->user()->isReseller())
                        <div class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700 px-3 py-1">Reseller Hub</div>
                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.dashboard') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            <span>Overview Reseller</span>
                        </a>
                        <a href="{{ route('reseller.commissions.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.commissions.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Komisi Referral</span>
                        </a>
                        <a href="{{ route('reseller.wallet.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.wallet.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v8.25m0-8.25a60.073 60.073 0 0115.797-2.101c.727-.198 1.453.342 1.453 1.096V6m0 0v8.25m0-8.25a60.075 60.075 0 00-15.797 2.101M21 14.25v.75A.75.75 0 0120.25 16h-.75m0 0v2.75M21 6v8.25m-18 0v2.75m0-2.75h18"/></svg>
                            <span>Dompet Saldo Kas</span>
                        </a>
                        <a href="{{ route('reseller.withdrawals.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.withdrawals.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            <span>Tarik Dana Komisi</span>
                        </a>
                        <a href="{{ route('reseller.profile') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('reseller.profile') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            <span>Profil &amp; Rekening</span>
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
                    <a href="{{ route('member.points.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.points.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <span>Poin Loyalitas</span>
                    </a>
                    <a href="{{ route('member.coupons.index') }}" class="flex items-center space-x-2.5 px-3.5 py-3 rounded-2xl transition-all {{ request()->routeIs('member.coupons.*') ? 'bg-charcoal-950 text-cream-300 font-bold shadow-md border border-cream-400/30' : 'text-charcoal-600 hover:bg-cream-100/70' }}">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                        <span>Kupon &amp; Voucher</span>
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
                        <a href="{{ route('admin.orders.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Kelola Pesanan</a>
                        <a href="{{ route('admin.coupons.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Kelola Kupon Promo</a>
                        <a href="{{ route('admin.points.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Log Poin Loyalitas</a>
                        <a href="{{ route('admin.payments.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Log Pembayaran</a>
                        <a href="{{ route('admin.users.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Kelola Pengguna</a>
                    @endif
                    @if(auth()->user()->isReseller())
                        <a href="{{ route('reseller.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-950 font-bold bg-cream-200/80">Portal Reseller</a>
                    @endif
                    <a href="{{ route('member.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Dashboard Member</a>
                    <a href="{{ route('member.points.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Poin Loyalitas</a>
                    <a href="{{ route('member.coupons.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Kupon &amp; Voucher</a>
                    <a href="{{ route('member.orders.index') }}" class="block px-3.5 py-2.5 rounded-xl text-charcoal-700 hover:bg-cream-100">Pesanan Saya</a>
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
