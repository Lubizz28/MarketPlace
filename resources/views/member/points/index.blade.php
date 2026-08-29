@extends('layouts.dashboard')

@section('title', 'Poin Loyalitas Member')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-charcoal-luxury rounded-3xl p-7 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-cream-400/20">
        <div class="absolute inset-0 bg-cream-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-cream-btn text-charcoal-950 rounded-full shadow-xs">
                <span>✦</span><span>Program Loyalitas MedinaStyle</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold tracking-tight text-white">Poin Reward Anda</h1>
            <p class="text-cream-200/90 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Kumpulkan poin setiap kali Anda berbelanja (1 Poin tiap Rp 10.000) dan tukarkan langsung sebagai potongan belanja saat checkout.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Poin Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Active Balance Card -->
        <div class="glass-card p-6 rounded-3xl border-2 border-cream-400/90 shadow-xl flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700">Saldo Poin Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-cream-200 border border-cream-300 flex items-center justify-center text-charcoal-950 font-bold">
                    💎
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-display font-bold text-charcoal-950">{{ number_format($user->loyalty_points, 0, ',', '.') }} <span class="text-sm font-sans font-normal text-charcoal-500">Poin</span></p>
                <p class="text-xs text-emerald-700 font-medium mt-1">Setara diskon Rp {{ number_format($user->loyalty_points * 10, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Total Earned Card -->
        <div class="glass-card p-6 rounded-3xl flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Total Diperoleh</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                    ↑
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-display font-bold text-charcoal-950">+{{ number_format($totalEarned, 0, ',', '.') }}</p>
                <p class="text-xs text-charcoal-400 font-light mt-1">Akumulasi seluruh perolehan</p>
            </div>
        </div>

        <!-- Total Redeemed Card -->
        <div class="glass-card p-6 rounded-3xl flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Total Ditukarkan</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                    ↓
                </div>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-display font-bold text-charcoal-950">-{{ number_format($totalRedeemed, 0, ',', '.') }}</p>
                <p class="text-xs text-charcoal-400 font-light mt-1">Telah digunakan untuk diskon</p>
            </div>
        </div>
    </div>

    <!-- How Points Work Explainer -->
    <div class="glass-card p-6 rounded-3xl border border-cream-300 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-charcoal-600">
        <div class="flex items-start space-x-3 p-2">
            <span class="w-7 h-7 rounded-xl bg-charcoal-950 text-cream-300 font-bold flex items-center justify-center shrink-0 text-[11px]">1</span>
            <div>
                <h4 class="font-bold text-charcoal-900">Belanja &amp; Selesaikan</h4>
                <p class="text-charcoal-500 font-light mt-0.5">Dapatkan 1 poin untuk setiap pembelanjaan Rp 10.000 saat pesanan selesai.</p>
            </div>
        </div>
        <div class="flex items-start space-x-3 p-2">
            <span class="w-7 h-7 rounded-xl bg-charcoal-950 text-cream-300 font-bold flex items-center justify-center shrink-0 text-[11px]">2</span>
            <div>
                <h4 class="font-bold text-charcoal-900">Tukarkan di Checkout</h4>
                <p class="text-charcoal-500 font-light mt-0.5">Gunakan slider poin di halaman checkout (1 Poin = Potongan Rp 10).</p>
            </div>
        </div>
        <div class="flex items-start space-x-3 p-2">
            <span class="w-7 h-7 rounded-xl bg-charcoal-950 text-cream-300 font-bold flex items-center justify-center shrink-0 text-[11px]">3</span>
            <div>
                <h4 class="font-bold text-charcoal-900">Gabungkan dengan Kupon</h4>
                <p class="text-charcoal-500 font-light mt-0.5">Poin dapat digabungkan dengan kode kupon promo untuk hemat maksimal.</p>
            </div>
        </div>
    </div>

    <!-- Point Transactions Ledger Table -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-cream-200">
            <h2 class="font-display font-bold text-charcoal-950 text-base">Riwayat Mutasi Poin (Ledger)</h2>
            <span class="text-xs text-charcoal-400 font-light">{{ $transactions->total() }} transaksi tercatat</span>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-12 space-y-3">
                <div class="w-12 h-12 rounded-full bg-cream-100 flex items-center justify-center text-charcoal-400 mx-auto text-lg">💎</div>
                <p class="text-xs text-charcoal-500 font-light">Belum ada riwayat transaksi poin.</p>
                <a href="{{ route('catalog') }}" class="inline-block px-5 py-2.5 bg-charcoal-950 text-cream-200 font-bold text-xs rounded-2xl hover:bg-charcoal-900 transition-smooth">
                    Mulai Belanja &rarr;
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-cream-200 text-charcoal-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-3">Tanggal &amp; Waktu</th>
                            <th class="py-3 px-3">Tipe Mutasi</th>
                            <th class="py-3 px-3">Deskripsi / Keterangan</th>
                            <th class="py-3 px-3 text-right">Jumlah Poin</th>
                            <th class="py-3 px-3 text-right">Saldo Setelahnya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-100 font-light text-charcoal-800">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-cream-50/50 transition-colors">
                                <td class="py-3.5 px-3 font-mono text-[11px] text-charcoal-500">
                                    {{ $tx->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $tx->type->badgeClasses() }}">
                                        {{ $tx->type->label() }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-normal text-charcoal-900">{{ $tx->description }}</span>
                                    @if($tx->order_id && $tx->order)
                                        <a href="{{ route('orders.show', $tx->order->order_number) }}" class="text-[11px] text-cream-800 hover:underline block font-mono">
                                            Lihat Pesanan #{{ $tx->order->order_number }}
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-xs {{ $tx->points > 0 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-charcoal-950 text-xs">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} Poin
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4 border-t border-cream-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
