@extends('layouts.dashboard')

@section('title', 'Buku Kas Mutasi Stok — ' . $variant->sku)

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.inventory.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Inventori</a>
    </div>

    <!-- Variant Header -->
    <div class="glass-card p-6 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Audit Ledger Stok</span>
            <h1 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-1">{{ $variant->product?->name }}</h1>
            <p class="text-xs text-charcoal-500 font-mono mt-1">Varian: {{ $variant->name }} (SKU: {{ $variant->sku }})</p>
        </div>

        <div class="text-left sm:text-right bg-emerald-50 border border-emerald-200 p-4 rounded-2xl">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-900 block">Stok Gudang Terkini</span>
            <span class="text-2xl font-display font-bold text-emerald-950 font-mono">{{ $variant->stock }} pcs</span>
        </div>
    </div>

    <!-- Movements Ledger Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-3">Riwayat Mutasi Fisik (Double-Entry Ledger)</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Waktu Transaksi</th>
                        <th class="py-3 px-4">Tipe Mutasi</th>
                        <th class="py-3 px-4 text-center">Jumlah Perubahan</th>
                        <th class="py-3 px-4 text-center">Saldo Akhir</th>
                        <th class="py-3 px-4">Keterangan / Alasan</th>
                        <th class="py-3 px-4">Operator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($movements as $m)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-charcoal-500">
                                {{ $m->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-cream-200 text-charcoal-900">
                                    {{ $m->type->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                @php
                                    $isDeduction = in_array($m->type->value, ['sale']);
                                @endphp
                                <span class="{{ $isDeduction ? 'text-rose-700' : 'text-emerald-800' }}">
                                    {{ $isDeduction ? '-' : '+' }}{{ $m->quantity }} pcs
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-charcoal-950">
                                {{ $m->balance_after }} pcs
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-700 max-w-xs">
                                {{ $m->notes ?? '-' }}
                                @if($m->reference_type)
                                    <span class="text-[10px] text-charcoal-400 block font-mono">Ref: {{ $m->reference_type }} #{{ $m->reference_id }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-600">
                                {{ $m->user?->name ?? 'Sistem Otomatis' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada riwayat mutasi stok untuk varian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection
