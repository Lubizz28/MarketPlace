@extends('layouts.dashboard')

@section('title', 'Profil Pelanggan 360° — ' . $customer->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.customers.index') }}" class="text-xs text-charcoal-500 hover:text-charcoal-900 font-bold">&larr; Kembali ke Basis Data Pengguna</a>
    </div>

    <!-- Customer Overview Card in Glass Charcoal -->
    <div class="bg-charcoal-luxury rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden border border-cream-400/20 shadow-2xl">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $customer->role->badgeColor() }}">
                        {{ $customer->role->label() }}
                    </span>
                    <span class="px-3 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $customer->status->value === 'active' ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                        {{ $customer->status->label() }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-bold text-white">{{ $customer->name }}</h1>
                <p class="text-xs text-cream-200 font-mono">{{ $customer->email }} • {{ $customer->phone ?? 'No HP belum dicatat' }}</p>
                <span class="text-[10px] text-cream-400/80 block">Member sejak {{ $customer->created_at->format('d F Y') }}</span>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-4 text-center">
                <div class="bg-white/10 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white/10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-cream-300 block">Lifetime Spend (GMV)</span>
                    <span class="text-xl font-display font-bold text-white font-mono">Rp {{ number_format($lifetimeSpend, 0, ',', '.') }}</span>
                </div>

                <div class="bg-white/10 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white/10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-cream-300 block">Total Pesanan</span>
                    <span class="text-xl font-display font-bold text-white font-mono">{{ $totalOrdersCount }} Order</span>
                </div>

                <div class="bg-white/10 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-white/10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-cream-300 block">Saldo Poin</span>
                    <span class="text-xl font-display font-bold text-cream-300 font-mono">{{ number_format($customer->points_balance, 0, ',', '.') }} pts</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Address Book & Reseller Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Address Book -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Buku Alamat Pengiriman</h3>

            @if($customer->addresses->isEmpty())
                <p class="text-xs text-charcoal-400 font-light italic py-4">Belum ada alamat pengiriman yang tersimpan.</p>
            @else
                <div class="space-y-3">
                    @foreach($customer->addresses as $addr)
                        <div class="p-3.5 rounded-2xl bg-cream-50/70 border border-cream-200 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-charcoal-950">{{ $addr->receiver_name }} ({{ $addr->label }})</span>
                                @if($addr->is_primary)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-charcoal-950 text-cream-200">Utama</span>
                                @endif
                            </div>
                            <p class="text-charcoal-600 font-mono text-[11px]">{{ $addr->phone_number }}</p>
                            <p class="text-charcoal-700 leading-relaxed">{{ $addr->address_line }}, {{ $addr->subdistrict }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Reseller & Bank Profile (if reseller) -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-2">Informasi Kemitraan &amp; Finansial</h3>

            @if($customer->isReseller() && $customer->resellerProfile)
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-cream-100">
                        <span class="text-charcoal-500">Nama Toko Mitra</span>
                        <span class="font-bold text-charcoal-900">{{ $customer->resellerProfile->store_name }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-cream-100">
                        <span class="text-charcoal-500">Kode Referral</span>
                        <span class="font-mono font-bold text-cream-800">{{ $customer->resellerProfile->referral_code }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-cream-100">
                        <span class="text-charcoal-500">Rekening Pencairan Bank</span>
                        <span class="font-bold text-charcoal-900">{{ $customer->resellerProfile->bank_name }} - {{ $customer->resellerProfile->bank_account_number }} (a.n {{ $customer->resellerProfile->bank_account_name }})</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-charcoal-500">Saldo Kas Dompet</span>
                        <span class="font-mono font-bold text-emerald-800">{{ $customer->resellerWallet?->formatted_balance ?? 'Rp 0' }}</span>
                    </div>
                </div>
            @else
                <p class="text-xs text-charcoal-400 font-light italic py-4">Pengguna ini terdaftar sebagai Member Ritel biasa.</p>
            @endif
        </div>
    </div>

    <!-- Recent Orders History Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-sm border-b border-cream-200 pb-3">Riwayat Transaksi Pesanan Pelanggan</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">No. Pesanan</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Item Produk</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Total Tagihan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-charcoal-950">
                                #{{ $ord->order_number }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-500">
                                {{ $ord->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-700">
                                {{ $ord->items->pluck('product_name')->implode(', ') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $ord->status->badgeColor() }}">
                                    {{ $ord->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-charcoal-950">
                                {{ $ord->formatted_grand_total }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->order_number) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada riwayat pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
