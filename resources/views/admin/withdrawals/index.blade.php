@extends('layouts.dashboard')

@section('title', 'Kelola Penarikan Dana — Admin Panel')

@section('content')
<div class="space-y-6" x-data="{
    modalOpen: false,
    selectedWd: null,
    targetStatus: 'paid',
    notes: '',
    openProcess(wd, status) {
        this.selectedWd = wd;
        this.targetStatus = status;
        this.notes = '';
        this.modalOpen = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Admin Payout</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Kelola Penarikan Dana Reseller</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Verifikasi dan proses transfer pembayaran saldo komisi kemitraan reseller.</p>
        </div>
        <a href="{{ route('admin.resellers.index') }}" class="px-4 py-2 bg-cream-200 hover:bg-cream-300 text-charcoal-900 rounded-2xl text-xs font-bold transition-all text-center">
            &larr; Daftar Mitra Reseller
        </a>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="glass-card p-5 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-amber-800">Menunggu Transfer (Pending)</span>
                <p class="text-2xl font-display font-bold text-charcoal-950 font-mono mt-1">Rp {{ number_format($totalPendingWithdrawals, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">⏳</div>
        </div>

        <div class="glass-card p-5 rounded-3xl flex items-center justify-between">
            <div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-emerald-800">Total Telah Ditransfer (Paid)</span>
                <p class="text-2xl font-display font-bold text-charcoal-950 font-mono mt-1">Rp {{ number_format($totalPaidWithdrawals, 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">✓</div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">No. Penarikan</th>
                        <th class="py-3 px-4">Mitra Reseller</th>
                        <th class="py-3 px-4">Rekening Tujuan</th>
                        <th class="py-3 px-4 text-right">Nominal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi Proses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($withdrawals as $wd)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-charcoal-950">
                                {{ $wd->withdrawal_number }}
                                <span class="block text-[10px] text-charcoal-400 font-normal">{{ $wd->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-900 block">{{ $wd->user?->name }}</span>
                                <span class="text-[10px] text-charcoal-500">{{ $wd->user?->email }} &bull; {{ $wd->user?->phone }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $wd->bank_name }} - {{ $wd->bank_account_number }}</span>
                                <span class="text-[10px] text-charcoal-500 font-medium">a.n {{ $wd->bank_account_name }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-sm text-charcoal-950">
                                {{ $wd->formatted_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $wd->status->badgeClasses() }}">
                                    {{ $wd->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($wd->status->value === 'pending' || $wd->status->value === 'approved')
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" @click="openProcess({{ json_encode($wd) }}, 'paid')"
                                            class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-[10px] font-bold">
                                            ✓ Konfirmasi Transfer
                                        </button>
                                        <button type="button" @click="openProcess({{ json_encode($wd) }}, 'rejected')"
                                            class="px-3 py-1.5 bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-[10px] font-bold">
                                            ✕ Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[10px] text-charcoal-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada pengajuan penarikan dana reseller.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $withdrawals->links() }}
        </div>
    </div>

    <!-- Process Modal in AlpineJS -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-charcoal-950/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-cream-200" @click.away="modalOpen = false">
            <h3 class="font-display font-bold text-charcoal-950 text-base" x-text="targetStatus === 'paid' ? 'Konfirmasi Pembayaran Transfer' : 'Tolak Penarikan Dana'"></h3>
            <p class="text-xs text-charcoal-600 leading-relaxed font-light" x-text="targetStatus === 'paid' ? 'Pastikan dana telah berhasil ditransfer ke rekening bank mitra sebelum mengonfirmasi.' : 'Saldo yang ditarik akan dikembalikan secara otomatis ke dompet kas mitra reseller.'"></p>

            <form :action="'{{ url('/admin/withdrawals') }}/' + selectedWd?.id + '/process'" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" :value="targetStatus">

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">Catatan / Keterangan Admin</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Transfer via BCA No. Ref 982312" class="w-full bg-cream-50 border border-cream-300 rounded-2xl p-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500"></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-cream-200 text-charcoal-800 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 text-white rounded-xl text-xs font-bold"
                        :class="targetStatus === 'paid' ? 'bg-emerald-700 hover:bg-emerald-800' : 'bg-rose-700 hover:bg-rose-800'"
                        x-text="targetStatus === 'paid' ? 'Konfirmasi Selesai Ditransfer' : 'Tolak Penarikan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
