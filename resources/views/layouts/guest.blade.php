<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-sand-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MedinaStyle') }} — @yield('title', 'Autentikasi Eksklusif')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-sand-900 min-h-full flex flex-col justify-center py-10 sm:px-6 lg:px-8 selection:bg-emerald-950 selection:text-gold-300 relative bg-islamic-pattern">

    <!-- Ambient Luxury Background Glows -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-emerald-800/10 blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[400px] h-[300px] bg-gold-400/10 blur-[100px] pointer-events-none -z-10"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="{{ route('home') }}" class="inline-flex flex-col items-center group mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-950 border border-gold-500/30 flex items-center justify-center text-gold-400 font-serif font-bold text-2xl shadow-luxury group-hover:scale-105 transition-transform">
                <span class="text-gold-gradient">M</span>
            </div>
            <span class="text-2xl font-serif font-bold tracking-tight text-emerald-950 mt-2">Medina<span class="font-normal italic text-gold-600">Style</span></span>
            <span class="text-[9px] uppercase tracking-[0.25em] text-sand-500 font-medium">Haute Modestie</span>
        </a>
        <h2 class="text-2xl font-serif font-bold text-sand-900 tracking-tight">@yield('heading')</h2>
        <p class="mt-1.5 text-xs text-sand-600">@yield('subheading')</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Flash Alerts -->
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs mb-4 shadow-xs">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-900/10 border border-emerald-800/20 text-emerald-950 px-4 py-3 rounded-2xl text-xs mb-4 shadow-xs">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-luxury-lg rounded-3xl border border-sand-200/90 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-900 via-gold-500 to-emerald-900"></div>
            @yield('content')
        </div>

        <div class="text-center mt-8 space-y-2">
            <p class="text-xs text-sand-500">
                &copy; {{ date('Y') }} MedinaStyle. Transaksi aman, syar'i, dan terpercaya.
            </p>
            <div class="flex items-center justify-center space-x-3 text-[11px] text-gold-700 font-medium">
                <span>✦ Privasi Terjamin</span>
                <span>✦ Enkripsi 256-bit</span>
                <span>✦ Garansi Keaslian</span>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
