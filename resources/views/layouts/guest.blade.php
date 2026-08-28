<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-cream-100/70">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MedinaStyle') }} — @yield('title', 'Autentikasi Eksklusif')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-charcoal-900 min-h-full flex flex-col justify-center py-10 sm:px-6 lg:px-8 selection:bg-charcoal-950 selection:text-cream-200 relative bg-cream-pattern">

    <!-- Ambient Luxury Glows (Charcoal + Warm Cream) -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[650px] h-[380px] bg-charcoal-900/10 blur-[130px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[450px] h-[320px] bg-cream-500/15 blur-[120px] pointer-events-none -z-10"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="{{ route('home') }}" class="inline-flex flex-col items-center group mb-6">
            <div class="w-12 h-12 rounded-2xl bg-charcoal-950 border border-cream-400/40 flex items-center justify-center text-cream-300 font-display font-bold text-2xl shadow-luxury-sm group-hover:scale-105 transition-transform">
                <span class="text-cream-gradient">M</span>
            </div>
            <span class="text-2xl font-display font-bold tracking-tight text-charcoal-950 mt-2.5">MEDINA<span class="font-serif italic text-cream-600 font-normal ml-0.5">Style</span></span>
            <span class="text-[8px] uppercase tracking-[0.3em] text-charcoal-500 font-medium">Haute Modestie</span>
        </a>
        <h2 class="text-2xl font-display font-bold text-charcoal-950 tracking-tight">@yield('heading')</h2>
        <p class="mt-1.5 text-xs text-charcoal-600 font-light">@yield('subheading')</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Flash Alerts -->
        @if(session('error'))
            <div class="bg-rose-50/90 backdrop-blur-md border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs mb-4 shadow-xs">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-950/10 backdrop-blur-md border border-emerald-800/20 text-emerald-950 px-4 py-3 rounded-2xl text-xs mb-4 shadow-xs">
                {{ session('success') }}
            </div>
        @endif

        <!-- Frosted Glass Card with subtle top gradient bar -->
        <div class="bg-white/80 backdrop-blur-2xl py-8 px-6 sm:px-10 shadow-2xl rounded-3xl border border-cream-200/90 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-charcoal-950 via-cream-400 to-charcoal-950"></div>
            @yield('content')
        </div>

        <div class="text-center mt-8 space-y-2">
            <p class="text-xs text-charcoal-500 font-light">
                &copy; {{ date('Y') }} MedinaStyle. Transaksi aman, syar'i, dan terpercaya.
            </p>
            <div class="flex items-center justify-center space-x-3 text-[11px] text-cream-800 font-medium">
                <span>✦ Privasi Terjamin</span>
                <span>✦ Enkripsi 256-bit</span>
                <span>✦ Garansi Keaslian</span>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
