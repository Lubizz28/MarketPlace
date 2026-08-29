@extends('layouts.dashboard')

@section('title', 'Kupon & Voucher Saya')

@section('content')
<div class="space-y-8" x-data="{
    copiedCode: '',
    copyCode(code) {
        navigator.clipboard.writeText(code);
        this.copiedCode = code;
        setTimeout(() => { this.copiedCode = ''; }, 2500);
    }
}">
    <!-- Header Banner -->
    <div class="bg-charcoal-luxury rounded-3xl p-7 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-cream-400/20">
        <div class="absolute inset-0 bg-cream-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-cream-btn text-charcoal-950 rounded-full shadow-xs">
                <span>✦</span><span>Voucher &amp; Promosi Spesial</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-white">Koleksi Kupon Promo</h1>
            <p class="text-cream-200/90 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Salin kode kupon promo aktif di bawah ini dan tempelkan pada kolom voucher saat Anda melakukan checkout pesanan.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Active Coupons Grid -->
    <div class="space-y-4">
        <h2 class="font-display font-bold text-charcoal-950 text-base">Kupon Promo Aktif</h2>

        @if($activeCoupons->isEmpty())
            <div class="glass-card p-12 text-center rounded-3xl space-y-2">
                <p class="text-xs text-charcoal-500 font-light">Saat ini belum ada kupon promo aktif yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($activeCoupons as $coupon)
                    <div class="glass-card p-6 rounded-3xl border border-cream-300 relative overflow-hidden flex flex-col justify-between space-y-4 group hover:border-cream-400 transition-all">
                        
                        <!-- Top details -->
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $coupon->type->badgeClasses() }}">
                                    {{ $coupon->type->label() }}
                                </span>
                                @if($coupon->expires_at)
                                    <span class="text-[10px] text-charcoal-400 font-light">
                                        s/d {{ $coupon->expires_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-emerald-600 font-medium">Tanpa batas waktu</span>
                                @endif
                            </div>

                            <h3 class="font-display font-bold text-charcoal-950 text-lg mt-3">{{ $coupon->name }}</h3>
                            <p class="text-xs text-charcoal-500 font-light mt-1 line-clamp-2">{{ $coupon->description ?? 'Gunakan kupon ini untuk mendapatkan potongan harga istimewa belanja busana muslim pilihan Anda.' }}</p>

                            <div class="mt-4 pt-3 border-t border-cream-100 space-y-1 text-[11px] text-charcoal-600">
                                <div class="flex justify-between">
                                    <span>Nilai Potongan:</span>
                                    <span class="font-bold text-emerald-700 font-mono">{{ $coupon->formatted_discount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Min. Belanja:</span>
                                    <span class="font-mono">{{ $coupon->formatted_min_order }}</span>
                                </div>
                                @if($coupon->max_discount)
                                    <div class="flex justify-between">
                                        <span>Maks. Diskon:</span>
                                        <span class="font-mono">Rp {{ number_format($coupon->max_discount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Bottom Copy Action -->
                        <div class="pt-3 border-t border-cream-200 flex items-center justify-between gap-2">
                            <div class="px-3 py-1.5 bg-cream-100 rounded-xl font-mono font-bold text-charcoal-950 text-xs tracking-wider border border-cream-300 select-all">
                                {{ $coupon->code }}
                            </div>
                            
                            <button type="button" @click="copyCode('{{ $coupon->code }}')"
                                class="px-3.5 py-1.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-xl text-[11px] font-bold transition-smooth flex items-center space-x-1.5">
                                <template x-if="copiedCode !== '{{ $coupon->code }}'">
                                    <span>Salin Kode</span>
                                </template>
                                <template x-if="copiedCode === '{{ $coupon->code }}'">
                                    <span class="text-emerald-300">✓ Tersalin!</span>
                                </template>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div>
                {{ $activeCoupons->links() }}
            </div>
        @endif
    </div>

    <!-- My Coupon Usages -->
    @if($myUsages->isNotEmpty())
        <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-5">
            <h2 class="font-display font-bold text-charcoal-950 text-base pb-3 border-b border-cream-200">
                Riwayat Kupon yang Telah Anda Gunakan
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-cream-200 text-charcoal-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Kode Kupon</th>
                            <th class="py-3 px-3">Nomor Pesanan</th>
                            <th class="py-3 px-3 text-right">Potongan Diskon</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-100 font-light text-charcoal-800">
                        @foreach($myUsages as $usage)
                            <tr>
                                <td class="py-3 px-3 font-mono text-[11px] text-charcoal-500">
                                    {{ $usage->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-charcoal-950">
                                    {{ $usage->coupon?->code ?? '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($usage->order)
                                        <a href="{{ route('orders.show', $usage->order->order_number) }}" class="text-cream-800 hover:underline font-mono">
                                            #{{ $usage->order->order_number }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-emerald-700">
                                    - Rp {{ number_format($usage->discount_applied, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
