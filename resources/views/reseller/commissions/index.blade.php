@extends('layouts.dashboard')

@section('title', 'Daftar Komisi Referral — Reseller Hub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-cream-700">Afiliasi &amp; Penjualan</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Daftar Komisi Referral</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Pantau seluruh komisi yang dihasilkan dari tautan promosi dan penjualan afiliasi Anda.</p>
        </div>
        <a href="{{ route('reseller.withdrawals.index') }}" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold transition-all border border-cream-400/30 text-center">
            Tarik Saldo Komisi &rarr;
        </a>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="glass-card p-5 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-emerald-800">Komisi Terealisasi (Tersedia / Cair)</span>
                <p class="text-xl font-display font-bold text-charcoal-950 font-mono mt-1">Rp {{ number_format($totalEarned, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">✓</div>
        </div>

        <div class="glass-card p-5 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-amber-800">Komisi Menunggu Penyelesaian Pesanan</span>
                <p class="text-xl font-display font-bold text-charcoal-950 font-mono mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">⏳</div>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">No. Pesanan</th>
                        <th class="py-3 px-4">Waktu Transaksi</th>
                        <th class="py-3 px-4 text-right">Nilai Belanja</th>
                        <th class="py-3 px-4 text-center">Bagi Hasil</th>
                        <th class="py-3 px-4 text-right">Nominal Komisi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($commissions as $comm)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-charcoal-950 block">#{{ $comm->order?->order_number }}</span>
                                <span class="text-[10px] text-charcoal-500">{{ $comm->order?->customer_name }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-600">
                                {{ $comm->created_at->format('d/m/Y H:i') }} WIB
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-charcoal-700">
                                {{ $comm->formatted_subtotal }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-cream-900">
                                {{ $comm->commission_percent }}%
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-800 text-sm">
                                +{{ $comm->formatted_commission_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $comm->status->badgeClasses() }}">
                                    {{ $comm->status->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada komisi referral yang tercatat. Sebarkan tautan referral Anda untuk mulai menghasilkan komisi!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-3">
            {{ $commissions->links() }}
        </div>
    </div>
</div>
@endsection
