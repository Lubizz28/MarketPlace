<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary Meta Tags & SEO Optimization -->
    <title>{{ config('app.name', 'Sulastika Jaya') }} — @yield('title', 'Koleksi Busana Muslim & Modest Fashion Syar\'i')</title>
    <meta name="description" content="@yield('meta_description', 'Sulastika Jaya - Butik busana muslim syari, gamis anggun, abaya modern, pashmina voal, dan koko kurta eksklusif dengan harga retail, member rewards, dan program kemitraan reseller.')">
    <meta name="keywords" content="@yield('meta_keywords', 'sulastika jaya, busana muslim, gamis syari, abaya modern, hijab voal, baju koko, modest fashion indonesia, reseller pakaian muslim')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <meta name="theme-color" content="#061e17">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:site_name" content="Sulastika Jaya Modest Fashion">
    <meta property="og:title" content="@yield('title', 'Sulastika Jaya — Koleksi Busana Muslim & Modest Fashion Syar\'i')">
    <meta property="og:description" content="@yield('meta_description', 'Koleksi busana muslim berstandar butik syari, gamis, abaya, dan hijab mewah di Sulastika Jaya.')">
    <meta property="og:image" content="@yield('og_image', asset('images/icons/icon.svg'))">
    <meta property="og:image:alt" content="@yield('title', 'Sulastika Jaya')">
    <meta property="og:locale" content="id_ID">
    @yield('extra_og')

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@sulastikajaya">
    <meta name="twitter:creator" content="@sulastikajaya">
    <meta name="twitter:title" content="@yield('title', 'Sulastika Jaya — Modest Fashion Syar\'i')">
    <meta name="twitter:description" content="@yield('meta_description', 'Koleksi busana muslim berstandar butik syari di Sulastika Jaya.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/icons/icon.svg'))">

    <!-- PWA & Mobile Optimization -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sulastika Jaya">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon.svg') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/icons/icon.svg') }}">

    <!-- Global Structured Data: Organization & WebSite Schemas -->
    @php
        $globalSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '/#organization',
                    'name' => 'Sulastika Jaya',
                    'url' => url('/'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        '@id' => url('/') . '/#logo',
                        'url' => asset('images/icons/icon.svg'),
                        'caption' => 'Sulastika Jaya Logo',
                    ],
                    'description' => "Butik busana muslim dan modest fashion syar'i terpercaya di Indonesia dengan jahitan butik berkualitas, harga bersaing, reward member, dan program reseller.",
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+62-812-3456-7890',
                        'contactType' => 'customer service',
                        'areaServed' => 'ID',
                        'availableLanguage' => ['Indonesian', 'English'],
                    ],
                    'sameAs' => [
                        'https://instagram.com/sulastikajaya',
                        'https://tiktok.com/@sulastikajaya',
                        'https://facebook.com/sulastikajaya',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '/#website',
                    'url' => url('/'),
                    'name' => 'Sulastika Jaya',
                    'publisher' => [
                        '@id' => url('/') . '/#organization',
                    ],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => url('/catalog') . '?q={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($globalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- Page Specific Structured Data (JSON-LD) -->
    @yield('schema')

    <!-- Professional Global Luxury Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 bg-cream-50 flex flex-col min-h-full selection:bg-emerald-900 selection:text-gold-200" x-data="{ mobileMenuOpen: false }">

    <!-- Screen Reader Accessible Skip-To-Content Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:px-5 focus:py-3 focus:bg-emerald-950 focus:text-gold-200 focus:rounded-2xl focus:shadow-xl text-xs font-bold uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-gold-400">
        Lewati ke Konten Utama
    </a>

    <!-- Top Announcement Bar in Royal Emerald with Warm Champagne Gold Accents -->
    <div class="bg-emerald-950 text-gold-200 text-[11px] py-2 px-4 text-center font-medium tracking-[0.14em] border-b border-emerald-900/80 relative overflow-hidden" role="region" aria-label="Pengumuman Toko">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-2.5">
            <svg class="w-3 h-3 text-gold-400 shrink-0" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            <span class="uppercase font-medium text-[10px] sm:text-[11px] text-gold-100">
                Sulastika Jaya &bull; Keanggunan Busana Muslim Syar'i &bull; Diskon Member &amp; Peluang Reseller
            </span>
            <svg class="w-3 h-3 text-gold-400 shrink-0" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
        </div>
    </div>

    <!-- Header Navigation with Frosted Glassmorphism -->
    <header class="sticky top-0 z-40 glass-surface border-b border-cream-200/90 shadow-xs" role="banner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 sm:h-20">
                <!-- Brand & Mobile Menu Button -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen" aria-label="Buka menu navigasi utama" class="md:hidden p-2 text-charcoal-700 hover:text-emerald-950 hover:bg-cream-200/60 rounded-xl transition-smooth">
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path></svg>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-950 border border-gold-400/50 flex items-center justify-center text-gold-300 font-display font-bold text-lg shadow-sm group-hover:scale-105 transition-smooth">
                            <span class="text-gold-gradient tracking-tight">SJ</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg sm:text-xl font-display font-bold tracking-tight text-emerald-950 leading-none">
                                SULASTIKA <span class="text-gold-600 font-serif italic font-medium ml-0.5">Jaya</span>
                            </span>
                            <span class="text-[8px] uppercase tracking-[0.25em] text-emerald-800/80 font-semibold mt-0.5">Modest Fashion</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Search Bar in Frosted Glass -->
                <div class="hidden md:flex flex-1 max-w-md mx-6 lg:mx-10">
                    <form method="GET" action="{{ route('catalog') }}" class="relative w-full">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari gamis, abaya, hijab, koko..."
                            class="w-full bg-cream-100/80 backdrop-blur-md border border-cream-300/90 rounded-full py-2 pl-10 pr-4 text-xs tracking-wide placeholder:text-charcoal-400 focus:outline-none focus:ring-2 focus:ring-emerald-800 focus:bg-white focus:border-transparent transition-smooth shadow-xs">
                        <button type="submit" class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-charcoal-400 hover:text-emerald-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-2.5 sm:space-x-4">
                    <a href="{{ route('register', ['type' => 'reseller']) }}" class="hidden lg:inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full border border-gold-500/40 bg-gold-50 text-[11px] font-bold text-gold-900 hover:bg-gold-100/80 transition-smooth shadow-xs">
                        <svg class="w-3.5 h-3.5 text-gold-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                        <span>Mitra Reseller</span>
                    </a>

                    <!-- Wishlist Icon Shortcut -->
                    <a href="{{ route('member.wishlist.index') }}" class="p-2 text-charcoal-700 hover:text-rose-600 rounded-full hover:bg-cream-200/60 transition-smooth relative hidden sm:inline-flex" title="Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </a>

                    <!-- Livewire Cart Badge -->
                    <livewire:cart.cart-badge />

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 p-1 rounded-full hover:bg-cream-200/50 transition-smooth border border-transparent hover:border-cream-300">
                                <div class="w-8 h-8 rounded-full bg-emerald-950 text-gold-300 font-bold text-xs flex items-center justify-center border border-gold-400/40 shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:flex flex-col text-left">
                                    <span class="text-xs font-bold text-charcoal-900 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] text-emerald-800 font-medium capitalize">{{ auth()->user()->role->label() }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-charcoal-400 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path></svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white/95 backdrop-blur-2xl rounded-2xl shadow-xl border border-cream-200/90 py-2 z-50 text-xs">
                                <div class="px-4 py-3 border-b border-cream-100">
                                    <p class="font-bold text-charcoal-900 truncate text-sm">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-charcoal-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1.5 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ auth()->user()->role->badgeColor() }}">
                                        {{ auth()->user()->role->label() }}
                                    </span>
                                </div>

                                <div class="py-1">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 hover:bg-emerald-50 text-emerald-950 font-bold transition-smooth">
                                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                                            <span>Admin Panel</span>
                                        </a>
                                    @endif
                                    @if(auth()->user()->isReseller())
                                        <a href="{{ route('reseller.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 hover:bg-gold-50 text-gold-950 font-bold transition-smooth">
                                            <svg class="w-4 h-4 text-gold-700" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                            <span>Portal Reseller</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 hover:bg-cream-100/60 text-charcoal-700 font-medium transition-smooth">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path></svg>
                                        <span>Dashboard Member</span>
                                    </a>
                                    <a href="{{ route('member.profile') }}" class="flex items-center space-x-2.5 px-4 py-2 hover:bg-cream-100/60 text-charcoal-700 font-medium transition-smooth">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                        <span>Profil &amp; Akun</span>
                                    </a>
                                    <a href="{{ route('member.addresses.index') }}" class="flex items-center space-x-2.5 px-4 py-2 hover:bg-cream-100/60 text-charcoal-700 font-medium transition-smooth">
                                        <svg class="w-4 h-4 text-charcoal-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                                        <span>Buku Alamat</span>
                                    </a>
                                </div>

                                <div class="border-t border-cream-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold flex items-center space-x-2 transition-smooth">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                                        <span>Keluar Akun</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-1.5 sm:space-x-2">
                            <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider px-3 sm:px-4 py-2 text-charcoal-800 hover:text-emerald-950 rounded-full hover:bg-cream-200/60 transition-smooth">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="text-xs font-bold uppercase tracking-wider px-3.5 sm:px-4 py-2 bg-emerald-950 hover:bg-emerald-900 text-gold-200 rounded-full shadow-xs border border-gold-400/40 transition-smooth">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation in High-Blur Glass -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-cream-200 bg-white/95 backdrop-blur-2xl px-5 pt-4 pb-6 space-y-3">
            <form method="GET" action="{{ route('catalog') }}" class="relative mb-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari busana syar'i..." class="w-full bg-cream-50 border border-cream-200 rounded-2xl py-2 pl-10 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-800">
                <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center text-charcoal-400 hover:text-emerald-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </button>
            </form>
            <a href="{{ route('home') }}" class="block text-xs font-bold uppercase tracking-wider text-charcoal-800 py-2 border-b border-cream-100">Beranda Toko</a>
            <a href="{{ route('catalog') }}" class="block text-xs font-bold uppercase tracking-wider text-charcoal-800 py-2 border-b border-cream-100">Katalog Lengkap</a>
            <a href="{{ route('register', ['type' => 'reseller']) }}" class="block text-xs font-bold uppercase tracking-wider text-gold-700 py-2 border-b border-cream-100">Program Kemitraan Reseller</a>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="bg-emerald-950/10 backdrop-blur-md border border-emerald-800/20 text-emerald-950 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-medium shadow-xs mb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50/90 backdrop-blur-md border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-medium shadow-xs mb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-5 h-5 rounded-full bg-rose-700 text-white flex items-center justify-center text-xs font-bold">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main id="main-content" class="flex-1 pb-24 md:pb-12" role="main">
        @yield('content')
    </main>

    <!-- Premium Luxury Footer in Royal Emerald & Champagne Gold -->
    <footer class="bg-emerald-950 text-cream-200 pt-14 pb-24 md:pb-14 mt-auto border-t border-emerald-900/80 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 sm:gap-10 relative z-10">
            <div class="space-y-3.5">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-900 border border-gold-400/50 flex items-center justify-center text-gold-300 font-display font-bold text-base shadow-sm">
                        SJ
                    </div>
                    <span class="text-xl font-display font-bold text-white tracking-tight">SULASTIKA <span class="text-gold-400 italic font-serif font-normal ml-0.5">Jaya</span></span>
                </div>
                <p class="text-xs text-cream-300/80 leading-relaxed font-light">
                    Butik busana muslim dan pakaian syar'i terpercaya dengan standar jahitan butik berkelas, kenyamanan bahan premium, dan program kemitraan reseller berkah.
                </p>
                <div class="pt-1 flex flex-wrap items-center gap-2.5 text-gold-300 text-[11px] font-semibold tracking-wider uppercase">
                    <span>&bull; Standar Butik</span>
                    <span>&bull; Garansi Syar'i</span>
                    <span>&bull; 100% Amanah</span>
                </div>
            </div>

            <div>
                <h4 class="text-gold-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-3.5">Kemitraan &amp; Bisnis</h4>
                <ul class="space-y-2 text-xs text-cream-300/80 font-light">
                    <li><a href="{{ route('register', ['type' => 'reseller']) }}" class="hover:text-gold-200 transition-smooth">Daftar Mitra Reseller</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-gold-200 transition-smooth">Portal Login Reseller</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-gold-200 transition-smooth">Katalog Harga Khusus</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-gold-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-3.5">Layanan Pelanggan</h4>
                <ul class="space-y-2 text-xs text-cream-300/80 font-light">
                    <li><a href="{{ route('catalog') }}" class="hover:text-gold-200 transition-smooth">Jelajahi Koleksi</a></li>
                    <li><a href="{{ route('member.orders.index') }}" class="hover:text-gold-200 transition-smooth">Lacak Status Pesanan</a></li>
                    <li><a href="{{ route('member.profile') }}" class="hover:text-gold-200 transition-smooth">Bantuan &amp; Akun Saya</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-gold-300 font-display font-bold text-xs uppercase tracking-[0.2em] mb-3.5">Metode Pembayaran</h4>
                <p class="text-xs text-cream-300/80 mb-3 leading-relaxed font-light">Pembayaran instan, otomatis terverifikasi dengan gateway terpercaya.</p>
                <div class="grid grid-cols-3 gap-2 text-[10px] text-gold-200 font-mono">
                    <span class="py-1.5 px-2 bg-emerald-900 border border-gold-500/20 rounded-xl text-center font-bold">QRIS</span>
                    <span class="py-1.5 px-2 bg-emerald-900 border border-gold-500/20 rounded-xl text-center font-bold">BCA VA</span>
                    <span class="py-1.5 px-2 bg-emerald-900 border border-gold-500/20 rounded-xl text-center font-bold">MANDIRI</span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 pt-6 border-t border-emerald-900 text-center text-[11px] text-cream-400/60 font-light">
            &copy; {{ date('Y') }} Sulastika Jaya Modest Fashion. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    <!-- Mobile Frosted Glass Floating Bottom Bar -->
    <nav class="md:hidden fixed bottom-2.5 left-3 right-3 z-50 glass-bottom-bar rounded-2xl px-4 py-2 flex items-center justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('home') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('catalog') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('catalog') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path></svg>
            <span>Katalog</span>
        </a>
        <a href="{{ route('member.wishlist.index') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('member.wishlist.*') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            <span>Wishlist</span>
        </a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('admin.*') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                    <span>Admin</span>
                </a>
            @elseif(auth()->user()->isReseller())
                <a href="{{ route('reseller.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('reseller.*') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                    <span>Reseller</span>
                </a>
            @else
                <a href="{{ route('member.dashboard') }}" class="flex flex-col items-center text-[10px] {{ request()->routeIs('member.*') ? 'text-gold-300 font-bold' : 'text-cream-400' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                    <span>Akun</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center text-[10px] text-cream-400">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    <!-- Floating Automatic Scroll-To-Top Button -->
    <div x-data="{
            showScrollTop: false,
            init() {
                window.addEventListener('scroll', () => {
                    this.showScrollTop = (window.pageYOffset || document.documentElement.scrollTop) > 280;
                });
            },
            scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }"
        x-cloak>
        <button type="button"
            @click="scrollToTop"
            x-show="showScrollTop"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-8 scale-75"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-75"
            class="fixed bottom-20 right-4 sm:bottom-20 sm:right-6 md:bottom-8 md:right-8 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-950/95 hover:bg-emerald-900 border border-gold-400/50 text-gold-300 hover:text-gold-100 flex items-center justify-center shadow-2xl backdrop-blur-md hover:scale-110 active:scale-95 transition-all duration-200 cursor-pointer group"
            title="Kembali ke Atas"
            aria-label="Scroll ke atas">
            <svg class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </div>

    <!-- Livewire Cart Slide-over Drawer -->
    <livewire:cart.cart-drawer />

    @livewireScripts
</body>
</html>
