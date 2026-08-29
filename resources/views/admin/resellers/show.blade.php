@extends('layouts.dashboard')

@section('title', 'Detail Reseller: ' . $reseller->name . ' — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.resellers.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Daftar</a>
                <span class="text-charcoal-300">/</span>
                <span class="text-xs text-charcoal-700 font-mono">{{ $reseller->resellerProfile?->referral_code ?? $reseller->phone }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-1">Detail Mitra: {{ $reseller->name }}</h1>
        </div>

        <div class="flex items-center space-x-2">
            @if($reseller->status->value === 'pending')
                <form method="POST" action="{{ route('admin.resellers.verify', $reseller) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl text-xs font-bold shadow-md">
                        ✓ Setujui Mitra Reseller
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.resellers.reject', $reseller) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-rose-700 hover:bg-rose-800 text-white rounded-2xl text-xs font-bold shadow-md">
                        ✕ Tolak
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Account Info -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Informasi Akun</h3>
            <div class="space-y-2 text-xs text-charcoal-700">
                <p><b>Nama:</b> {{ $reseller->name }}</p>
                <p><b>Email:</b> {{ $reseller->email }}</p>
                <p><b>No. HP:</b> {{ $reseller->phone }}</p>
                <p><b>Toko:</b> {{ $reseller->resellerProfile?->store_name ?? '-' }}</p>
                <p><b>Kode Ref:</b> <span class="font-mono font-bold bg-cream-100 px-2 py-0.5 rounded">{{ $reseller->resellerProfile?->referral_code ?? $reseller->phone }}</span></p>
                <p><b>Bagi Hasil:</b> {{ $reseller->resellerProfile?->commission_rate_percent ?? 10 }}%</p>
            </div>
        </div>

        <!-- Bank Info -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Rekening Payout</h3>
            <div class="space-y-2 text-xs text-charcoal-700">
                <p><b>Nama Bank:</b> {{ $reseller->resellerProfile?->bank_name ?? 'Belum Diisi' }}</p>
                <p><b>No. Rekening:</b> <span class="font-mono font-bold">{{ $reseller->resellerProfile?->bank_account_number ?? '-' }}</span></p>
                <p><b>Atas Nama:</b> {{ $reseller->resellerProfile?->bank_account_name ?? '-' }}</p>
                <p><b>Status KYC:</b> 
                    <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $reseller->resellerProfile?->kyc_status->badgeClasses() ?? 'bg-cream-100' }}">
                        {{ $reseller->resellerProfile?->kyc_status->label() ?? 'Pending' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Financial Wallet Info -->
        <div class="glass-card p-6 rounded-3xl space-y-3 border-2 border-cream-400">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Status Dompet Kas</h3>
            <div class="space-y-2 text-xs text-charcoal-700">
                <div class="flex justify-between">
                    <span>Saldo Tersedia:</span>
                    <span class="font-mono font-bold text-emerald-800 text-sm">{{ $wallet->formatted_balance }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Komisi Tertunda:</span>
                    <span class="font-mono font-bold text-amber-800">{{ $wallet->formatted_pending_balance }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Ditarik:</span>
                    <span class="font-mono font-bold text-charcoal-950">{{ $wallet->formatted_total_withdrawn }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Commissions History for this Reseller -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-sm">Riwayat Komisi Afiliasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold">
                        <th class="py-2.5 px-4">No. Pesanan</th>
                        <th class="py-2.5 px-4">Waktu</th>
                        <th class="py-2.5 px-4 text-right">Nilai Belanja</th>
                        <th class="py-2.5 px-4 text-right">Nominal Komisi</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($commissions as $comm)
                        <tr>
                            <td class="py-3 px-4 font-mono font-bold">#{{ $comm->order?->order_number }}</td>
                            <td class="py-3 px-4 font-mono text-charcoal-500">{{ $comm->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 text-right font-mono">{{ $comm->formatted_subtotal }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-800">+{{ $comm->formatted_commission_amount }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $comm->status->badgeClasses() }}">
                                    {{ $comm->status->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-charcoal-400 italic">Belum ada catatan komisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
