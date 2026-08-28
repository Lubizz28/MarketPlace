<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-stone-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Marketplace Muslim') }} - @yield('title', 'Autentikasi')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-stone-800 min-h-full flex flex-col justify-center py-8 sm:px-6 lg:px-8 selection:bg-emerald-600 selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-6">
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white font-serif font-bold text-2xl shadow-md">M</span>
            <span class="text-2xl font-serif font-bold tracking-tight text-emerald-950">Medina<span class="text-emerald-600">Style</span></span>
        </a>
        <h2 class="text-2xl font-serif font-bold text-stone-900">@yield('heading')</h2>
        <p class="mt-1 text-sm text-stone-600">@yield('subheading')</p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <!-- Flash Alerts -->
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white py-8 px-6 sm:px-10 shadow-card rounded-3xl border border-stone-200/80">
            @yield('content')
        </div>

        <p class="text-center text-xs text-stone-500 mt-8">
            &copy; {{ date('Y') }} MedinaStyle. Transaksi aman, syar'i, dan terpercaya.
        </p>
    </div>

    @livewireScripts
</body>
</html>
