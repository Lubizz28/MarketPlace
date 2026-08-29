@extends('layouts.dashboard')

@section('title', 'Penarikan Dana Komisi — Reseller Hub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-cream-700">Pencairan Dana &amp; Payout</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Penarikan Dana Komisi</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Cairkan saldo komisi yang telah tersedia langsung ke rekening bank terdaftar Anda.</p>
        </div>
        <a href="{{ route('reseller.profile') }}" class="text-xs font-bold text-cream-800 hover:underline">
            Ubah Data Rekening Bank &rarr;
        </a>
    </div>

    <!-- Withdrawal Form & Account Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Request in Glass Card -->
        <div class="glass-card p-6 rounded-3xl lg:col-span-2 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Formulir Pengajuan Penarikan</h3>
                <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                    Saldo Tersedia: {{ $wallet->formatted_balance }}
                </span>
            </div>

            <form method="POST" action="{{ route('reseller.withdrawals.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Nominal Penarikan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="amount" min="50000" max="{{ $wallet->balance }}" value="{{ old('amount', 50000) }}" required
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-3 px-4 text-sm font-mono font-bold text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                    <p class="text-[10px] text-charcoal-400 mt-1">Minimal penarikan: Rp 50.000. Saldo akan langsung di-hold sementara menunggu verifikasi transfer admin.</p>
                    @error('amount')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Bank Tujuan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $user->resellerProfile?->bank_name ?? 'BCA') }}" required
                            class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        @error('bank_name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                            Nomor Rekening <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->resellerProfile?->bank_account_number) }}" required
                            class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs font-mono text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                        @error('bank_account_number')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Nama Pemilik Rekening <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->resellerProfile?->bank_account_name ?? $user->name) }}" required
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                    @error('bank_account_name')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-charcoal-700 mb-1">
                        Catatan Tambahan (Opsional)
                    </label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Contoh: Tolong proses sebelum sore"
                        class="w-full bg-white/90 border border-cream-300 rounded-2xl py-2.5 px-3.5 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500 shadow-xs">
                </div>

                <div class="pt-2">
                    <button type="submit" {{ $wallet->balance < 50000 ? 'disabled' : '' }}
                        class="w-full py-3.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl border border-cream-400/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Kirim Pengajuan Penarikan Dana
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions & Info Card -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <h3 class="font-display font-bold text-charcoal-950 text-sm pb-2 border-b border-cream-200">Informasi Payout</h3>
            <div class="space-y-3 text-xs text-charcoal-600 leading-relaxed font-light">
                <p><b>1. Waktu Proses:</b> Penarikan dana diproses pada hari kerja (Senin - Jumat) maksimal 1x24 jam.</p>
                <p><b>2. Biaya Transfer:</b> Gratis transfer ke seluruh rekening bank nasional tanpa potongan biaya admin.</p>
                <p><b>3. Keamanan:</b> Pastikan nama pemilik rekening sesuai dengan identitas yang terdaftar pada profil kemitraan Anda.</p>
            </div>
        </div>
    </div>

    <!-- Withdrawals History Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <h3 class="font-display font-bold text-charcoal-950 text-sm">Riwayat Pengajuan Penarikan Dana</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">No. Pengajuan</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Rekening Tujuan</th>
                        <th class="py-3 px-4 text-right">Nominal</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($withdrawals as $wd)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-charcoal-950">
                                {{ $wd->withdrawal_number }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-500 whitespace-nowrap">
                                {{ $wd->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-900 block">{{ $wd->bank_name }} - {{ $wd->bank_account_number }}</span>
                                <span class="text-[10px] text-charcoal-500">a.n {{ $wd->bank_account_name }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-charcoal-950 text-sm">
                                {{ $wd->formatted_amount }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $wd->status->badgeClasses() }}">
                                    {{ $wd->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-600 text-[11px]">
                                {{ $wd->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada riwayat penarikan dana.
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
</div>
@endsection
