@extends('layouts.dashboard')

@section('title', 'Manajemen Mitra Reseller — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Admin Kemitraan</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Manajemen Mitra Reseller</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Verifikasi pendaftaran akun reseller, pantau performa omzet penjualan afiliasi, dan kelola komisi.</p>
        </div>
        <a href="{{ route('admin.withdrawals.index') }}" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold transition-all border border-cream-400/30 text-center">
            Kelola Penarikan Dana &rarr;
        </a>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] uppercase tracking-wider font-bold text-charcoal-400">Total Mitra Reseller</span>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-1">{{ $totalResellersCount }}</p>
        </div>
        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] uppercase tracking-wider font-bold text-amber-800">Menunggu Verifikasi</span>
            <p class="text-2xl font-display font-bold text-amber-900 mt-1">{{ $pendingResellersCount }}</p>
        </div>
        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] uppercase tracking-wider font-bold text-emerald-800">Total Komisi Dicairkan</span>
            <p class="text-2xl font-display font-bold text-emerald-950 font-mono mt-1">Rp {{ number_format($totalCommissionsPaid, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table in Glass Card -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
            <form method="GET" action="{{ route('admin.resellers.index') }}" class="flex items-center space-x-2 flex-1 max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no HP..."
                    class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                <button type="submit" class="px-4 py-2 bg-charcoal-950 text-cream-200 rounded-2xl text-xs font-bold">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Nama &amp; Kontak</th>
                        <th class="py-3 px-4">Toko &amp; Kode Ref</th>
                        <th class="py-3 px-4 text-center">Status Akun</th>
                        <th class="py-3 px-4 text-right">Saldo Tersedia</th>
                        <th class="py-3 px-4 text-center">Order Referral</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($resellers as $reseller)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $reseller->name }}</span>
                                <span class="text-[10px] text-charcoal-500">{{ $reseller->email }} &bull; {{ $reseller->phone }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-charcoal-900 block">{{ $reseller->resellerProfile?->store_name ?? '-' }}</span>
                                <span class="font-mono text-[10px] text-cream-900 font-bold bg-cream-100 px-2 py-0.5 rounded-md inline-block mt-0.5">
                                    {{ $reseller->resellerProfile?->referral_code ?? $reseller->phone }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($reseller->status->value === 'pending')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300">
                                        Menunggu Approval
                                    </span>
                                @elseif($reseller->status->value === 'active')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-300">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-charcoal-950">
                                Rp {{ number_format($reseller->resellerWallet?->balance ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold">
                                {{ $reseller->referral_orders_count }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('admin.resellers.show', $reseller) }}" class="px-2.5 py-1 bg-charcoal-950 text-cream-200 rounded-xl text-[10px] font-bold hover:bg-charcoal-900">
                                        Detail
                                    </a>
                                    @if($reseller->status->value === 'pending')
                                        <form method="POST" action="{{ route('admin.resellers.verify', $reseller) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-700 text-white rounded-xl text-[10px] font-bold hover:bg-emerald-800">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Tidak ada data mitra reseller.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $resellers->links() }}
        </div>
    </div>
</div>
@endsection
