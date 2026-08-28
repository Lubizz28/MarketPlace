@extends('layouts.dashboard')

@section('title', 'Audit Log Transaksi Pembayaran — Admin Panel')
@section('page-title', 'Audit Log Pembayaran')

@section('content')
<div class="space-y-6">

    <!-- Header Box -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950">Audit Trail Webhook &amp; Transaksi Pembayaran</h2>
                <p class="text-xs text-charcoal-500 font-light mt-0.5">Rekam jejak seluruh mutasi, charge, notifikasi webhook Midtrans, dan audit payload transaksi.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-white/80 hover:bg-cream-100 border border-cream-300 text-charcoal-800 rounded-2xl text-xs font-bold transition-smooth shadow-xs">
                &larr; Kembali ke Kelola Pesanan
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="glass-card p-4 sm:p-5 rounded-3xl">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari gateway reference, event type, nomor order..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl text-xs focus:ring-2 focus:ring-charcoal-950">
                <svg class="w-4 h-4 text-charcoal-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-wider shadow-sm transition-smooth">
                Cari Log
            </button>
            @if(request('q'))
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2.5 bg-cream-200 text-charcoal-700 font-bold rounded-2xl text-xs text-center flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Transactions Log Table -->
    <div class="glass-card rounded-3xl overflow-hidden shadow-lg border border-cream-200/90">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/90 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-4 px-5">Waktu Transaksi</th>
                        <th class="py-4 px-5">Nomor Pesanan</th>
                        <th class="py-4 px-5">Gateway Reference / ID</th>
                        <th class="py-4 px-5">Tipe Event</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-right">Payload JSON</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-cream-50/70 transition-smooth">
                            <td class="py-4 px-5 font-mono text-charcoal-500">
                                {{ $trx->created_at->format('d/m/Y H:i:s') }} WIB
                            </td>
                            <td class="py-4 px-5 font-mono font-bold">
                                @if($trx->payment?->order)
                                    <a href="{{ route('admin.orders.show', $trx->payment->order->order_number) }}" class="text-charcoal-950 hover:underline">
                                        #{{ $trx->payment->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-charcoal-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 font-mono font-semibold text-charcoal-900">
                                {{ $trx->gateway_reference }}
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2 py-0.5 rounded bg-cream-200 font-mono text-[11px] font-bold uppercase text-charcoal-800">
                                    {{ $trx->event_type }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase font-mono bg-charcoal-950 text-cream-300">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                @if(!empty($trx->payload_json))
                                    <details class="inline-block text-left">
                                        <summary class="cursor-pointer px-3 py-1 bg-cream-200 hover:bg-cream-300 text-charcoal-800 rounded-lg text-[10px] font-bold uppercase tracking-wider select-none transition-smooth">
                                            JSON Log
                                        </summary>
                                        <div class="fixed inset-0 z-50 bg-charcoal-950/60 backdrop-blur-sm flex items-center justify-center p-4" onclick="if(event.target === this) this.parentElement.removeAttribute('open')">
                                            <div class="bg-white rounded-3xl p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto space-y-4 shadow-2xl border border-cream-300">
                                                <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                                                    <h4 class="font-display font-bold text-charcoal-950 text-sm">Payload Transaksi: {{ $trx->gateway_reference }}</h4>
                                                    <button type="button" onclick="this.closest('details').removeAttribute('open')" class="text-charcoal-400 hover:text-charcoal-700 font-bold">&times;</button>
                                                </div>
                                                <pre class="bg-cream-50 p-4 rounded-2xl text-[11px] font-mono text-charcoal-800 overflow-x-auto border border-cream-200">{{ json_encode($trx->payload_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </div>
                                        </div>
                                    </details>
                                @else
                                    <span class="text-charcoal-400 text-[11px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-charcoal-400">
                                Belum ada log transaksi pembayaran yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-cream-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
