<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sulastika Jaya') }} — @yield('title', 'Autentikasi Eksklusif')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 min-h-full flex flex-col justify-center py-8 sm:px-6 lg:px-8 selection:bg-emerald-950 selection:text-gold-200 relative bg-cream-50">

    <!-- Ambient Luxury Glows (Emerald + Champagne Gold) -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[650px] h-[380px] bg-emerald-950/10 blur-[130px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[450px] h-[320px] bg-gold-500/15 blur-[120px] pointer-events-none -z-10"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="{{ route('home') }}" class="inline-flex flex-col items-center group mb-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-950 border border-gold-400/50 flex items-center justify-center text-gold-300 font-display font-bold text-xl shadow-md group-hover:scale-105 transition-transform">
                <span class="text-gold-gradient">SJ</span>
            </div>
            <span class="text-xl font-display font-bold tracking-tight text-emerald-950 mt-2">SULASTIKA<span class="font-serif italic text-gold-700 font-normal ml-1">Jaya</span></span>
            <span class="text-[8px] uppercase tracking-[0.25em] text-emerald-800/80 font-semibold">Modest Fashion &amp; Syar'i</span>
        </a>
        <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 tracking-tight">@yield('heading')</h2>
        <p class="mt-1 text-xs text-charcoal-600 font-light">@yield('subheading')</p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Flash Alerts -->
        @if(session('error'))
            <div class="bg-rose-50/90 backdrop-blur-md border border-rose-200 text-rose-800 px-4 py-2.5 rounded-xl text-xs mb-4 shadow-xs">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-950/10 backdrop-blur-md border border-emerald-800/20 text-emerald-950 px-4 py-2.5 rounded-xl text-xs mb-4 shadow-xs">
                {{ session('success') }}
            </div>
        @endif

        <!-- Frosted Glass Card with subtle gold top gradient bar -->
        <div class="bg-white/90 backdrop-blur-2xl py-7 px-6 sm:px-8 shadow-xl rounded-2xl border border-cream-200/90 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-linear-to-r from-emerald-950 via-gold-400 to-emerald-950"></div>
            @yield('content')
        </div>

        <div class="text-center mt-6 space-y-1.5">
            <p class="text-xs text-charcoal-500 font-light">
                &copy; {{ date('Y') }} Sulastika Jaya. Transaksi aman, syar'i, dan terpercaya.
            </p>
            <div class="flex items-center justify-center space-x-3 text-[10px] text-emerald-800 font-medium">
                <span>✦ Privasi Terjamin</span>
                <span>✦ Enkripsi 256-bit</span>
                <span>✦ Butik Berkelas</span>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
