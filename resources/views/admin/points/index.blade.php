@extends('layouts.dashboard')

@section('title', 'Log Poin Loyalitas — Admin Panel')

@section('content')
<div class="space-y-6" x-data="{
    showAdjustModal: false,
    selectedUserId: '',
    selectedUserName: '',
    pointsAmount: 0,
    adjustReason: '',

    openAdjustModal(userId = '', userName = '') {
        this.selectedUserId = userId;
        this.selectedUserName = userName;
        this.pointsAmount = 0;
        this.adjustReason = '';
        this.showAdjustModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">Audit Log Poin Loyalitas</h1>
            <p class="text-xs text-charcoal-500 font-light mt-0.5">Rekam jejak perolehan, penukaran, dan penyesuaian saldo poin reward pelanggan.</p>
        </div>
        <button type="button" @click="openAdjustModal()"
            class="inline-flex items-center space-x-2 px-5 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs rounded-2xl transition-smooth shadow-md">
            <span>⚖️ Penyesuaian Saldo Poin Manual</span>
        </button>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass-card p-6 rounded-3xl border-2 border-cream-400/90 shadow-xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700 block">Total Saldo Beredar</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 mt-2 font-mono">{{ number_format($totalActivePoints, 0, ',', '.') }} <span class="text-xs font-sans text-charcoal-500">Poin</span></p>
            <span class="text-xs text-charcoal-400 font-light mt-1 block">Tersimpan di seluruh akun member</span>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 block">Total Diberikan</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-emerald-700 mt-2 font-mono">+{{ number_format($totalPointsEarned, 0, ',', '.') }}</p>
            <span class="text-xs text-charcoal-400 font-light mt-1 block">Perolehan dari belanja member</span>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400 block">Total Ditebus</span>
            <p class="text-2xl sm:text-3xl font-display font-bold text-amber-700 mt-2 font-mono">-{{ number_format($totalPointsRedeemed, 0, ',', '.') }}</p>
            <span class="text-xs text-charcoal-400 font-light mt-1 block">Telah dikonversi menjadi diskon belanja</span>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-card p-5 rounded-3xl space-y-4">
        <form method="GET" action="{{ route('admin.points.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
            <div class="sm:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama member, email, atau nomor HP..."
                    class="w-full px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
            </div>

            <div class="sm:col-span-4">
                <select name="type" class="w-full px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    <option value="">Semua Tipe Transaksi</option>
                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Perolehan Belanja</option>
                    <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Penukaran Diskon</option>
                    <option value="refunded" {{ request('type') === 'refunded' ? 'selected' : '' }}>Pengembalian</option>
                    <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Penyesuaian Manual</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl hover:bg-charcoal-900 transition-smooth text-xs">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.points.index') }}" class="px-3 py-2.5 bg-cream-200 text-charcoal-700 rounded-2xl hover:bg-cream-300 transition-colors text-xs flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Point Transactions Ledger Table -->
    <div class="glass-card p-6 rounded-3xl space-y-4">
        @if($transactions->isEmpty())
            <div class="text-center py-12 space-y-3">
                <div class="w-12 h-12 rounded-full bg-cream-100 flex items-center justify-center text-charcoal-400 mx-auto text-lg">💎</div>
                <p class="text-xs text-charcoal-500 font-light">Tidak ada data transaksi poin loyalitas.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-cream-200 text-charcoal-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Member / Pengguna</th>
                            <th class="py-3 px-3">Tipe Mutasi</th>
                            <th class="py-3 px-3">Keterangan</th>
                            <th class="py-3 px-3 text-right">Mutasi Poin</th>
                            <th class="py-3 px-3 text-right">Saldo Akhir</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-100 font-light text-charcoal-800">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-cream-50/50 transition-colors">
                                <td class="py-3.5 px-3 font-mono text-[11px] text-charcoal-500">
                                    {{ $tx->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="font-semibold text-charcoal-900">{{ $tx->user?->name ?? 'Guest/Unknown' }}</div>
                                    <div class="text-[10px] text-charcoal-500 font-mono">{{ $tx->user?->email }}</div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $tx->type->badgeClasses() }}">
                                        {{ $tx->type->label() }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span>{{ $tx->description }}</span>
                                    @if($tx->order)
                                        <a href="{{ route('admin.orders.show', $tx->order->order_number) }}" class="text-cream-800 hover:underline font-mono text-[11px] block">
                                            Pesanan #{{ $tx->order->order_number }}
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-xs {{ $tx->points > 0 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-charcoal-950 text-xs">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    @if($tx->user)
                                        <button type="button" @click="openAdjustModal('{{ $tx->user->id }}', '{{ addslashes($tx->user->name) }}')"
                                            class="px-2.5 py-1 bg-cream-200 hover:bg-cream-300 text-charcoal-900 rounded-xl text-[10px] font-bold transition-colors">
                                            Sesuaikan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4 border-t border-cream-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Manual Adjustment Modal -->
    <div x-show="showAdjustModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-charcoal-950/60 backdrop-blur-sm" @click="showAdjustModal = false"></div>
        <div class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full z-10 space-y-5 border border-cream-300 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                <h3 class="font-display font-bold text-charcoal-950 text-base">Penyesuaian Saldo Poin Manual</h3>
                <button @click="showAdjustModal = false" class="text-charcoal-400 hover:text-charcoal-600 font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.points.adjust') }}" class="space-y-4 text-xs">
                @csrf

                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Pilih Member *</label>
                    <select name="user_id" x-model="selectedUserId" required class="w-full px-4 py-3 bg-white border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                        <option value="">-- Pilih Akun Member --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }}) — Saldo: {{ $m->loyalty_points }} Poin</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Jumlah Perubahan Poin (+/-) *</label>
                    <input type="number" name="points" x-model="pointsAmount" required placeholder="Contoh: 500 untuk tambah, -200 untuk kurangi"
                        class="w-full px-4 py-3 bg-white border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                    <span class="text-[10px] text-charcoal-400">Gunakan angka positif untuk menambah saldo poin, dan angka negatif (misal: -100) untuk mengurangi.</span>
                </div>

                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Alasan Penyesuaian (Audit Log) *</label>
                    <textarea name="reason" x-model="adjustReason" required rows="2" placeholder="Misal: Bonus reward event promosi, koreksi manual order kompensasi..."
                        class="w-full px-4 py-3 bg-white border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs"></textarea>
                </div>

                <div class="pt-4 border-t border-cream-200 flex items-center justify-end space-x-3">
                    <button type="button" @click="showAdjustModal = false" class="px-5 py-2.5 bg-cream-200 text-charcoal-800 font-bold rounded-2xl text-xs hover:bg-cream-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-wider hover:bg-charcoal-900 transition-smooth shadow-md">
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
