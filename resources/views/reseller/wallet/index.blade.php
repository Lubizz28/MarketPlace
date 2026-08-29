@extends('layouts.dashboard')

@section('title', 'Dompet & Buku Kas Saldo — Reseller Hub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-cream-700">Buku Kas &amp; Mutasi Saldo</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Dompet Saldo Reseller</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Audit log mutasi saldo yang transparan dan terenkripsi menggunakan prinsip double-entry ledger.</p>
        </div>
        <a href="{{ route('reseller.withdrawals.index') }}" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold transition-all border border-cream-400/30 text-center">
            Ajukan Penarikan Dana &rarr;
        </a>
    </div>

    <!-- Balance Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-card p-5 rounded-3xl border border-cream-300">
            <span class="text-[9px] font-bold uppercase tracking-wider text-cream-800">Saldo Tersedia</span>
            <p class="text-2xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $wallet->formatted_balance }}</p>
            <span class="text-[10px] text-emerald-700 font-semibold">Siap ditarik ke rekening</span>
        </div>

        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Saldo Tertunda</span>
            <p class="text-2xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $wallet->formatted_pending_balance }}</p>
            <span class="text-[10px] text-charcoal-400">Menunggu pesanan diselesaikan</span>
        </div>

        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Total Telah Dicairkan</span>
            <p class="text-2xl font-display font-bold text-charcoal-950 font-mono mt-1">{{ $wallet->formatted_total_withdrawn }}</p>
            <span class="text-[10px] text-charcoal-400">Akumulasi seumur hidup</span>
        </div>
    </div>

    <!-- Transactions Ledger Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-sm">Riwayat Transaksi Ledger Dompet</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Tipe Mutasi</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-right">Nominal Mutasi</th>
                        <th class="py-3 px-4 text-right">Saldo Sesudah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-charcoal-500 whitespace-nowrap">
                                {{ $tx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $tx->type->badgeClasses() }}">
                                    {{ $tx->type->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-700">
                                {{ $tx->description }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-sm {{ $tx->amount >= 0 ? 'text-emerald-800' : 'text-amber-800' }}">
                                {{ $tx->amount >= 0 ? '+' : '' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-charcoal-950">
                                Rp {{ number_format($tx->balance_after, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada mutasi transaksi dompet yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
