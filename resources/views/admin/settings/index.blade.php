@extends('layouts.dashboard')

@section('title', 'Konfigurasi & Pengaturan Sistem — MedinaStyle')

@section('content')
<div class="max-w-4xl space-y-6">
    <div>
        <h1 class="text-2xl font-display font-bold text-charcoal-950">Pengaturan Platform &amp; Toko</h1>
        <p class="text-xs text-charcoal-500 font-light">Konfigurasi informasi bisnis, customer service, batas penarikan komisi, dan parameter sistem.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $items)
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-4">
                <div class="border-b border-cream-200 pb-3">
                    <h3 class="font-display font-bold text-charcoal-950 text-base uppercase tracking-wider">
                        @if($group === 'general') 🏢 Informasi Bisnis &amp; Kontak Layanan
                        @elseif($group === 'shipping') 🚚 Pengiriman &amp; Logistik (RajaOngkir)
                        @elseif($group === 'affiliate') 🤝 Parameter Kemitraan &amp; Komisi Reseller
                        @elseif($group === 'loyalty') ⭐ Program Poin Loyalitas
                        @else ⚙️ {{ ucfirst($group) }}
                        @endif
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($items as $s)
                        <div class="{{ $s->type === 'textarea' ? 'sm:col-span-2' : '' }}">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                                {{ $s->label }}
                            </label>

                            @if($s->type === 'textarea')
                                <textarea name="{{ $s->key }}" rows="3"
                                    class="w-full bg-white/90 border border-cream-300 rounded-2xl p-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">{{ old($s->key, $s->value) }}</textarea>
                            @else
                                <input type="{{ $s->type === 'number' ? 'number' : 'text' }}" name="{{ $s->key }}" value="{{ old($s->key, $s->value) }}"
                                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 font-mono focus:outline-none focus:border-cream-500">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="btn-gold px-8 py-3 rounded-2xl text-xs font-bold shadow-lg">
                Simpan Semua Konfigurasi
            </button>
        </div>
    </form>
</div>
@endsection
