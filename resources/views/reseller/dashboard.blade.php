@extends('layouts.dashboard')

@section('title', 'Reseller Hub — Mitra Bisnis')

@section('content')
<div class="space-y-8" x-data="{
    copied: false,
    copyReferral(link) {
        navigator.clipboard.writeText(link);
        this.copied = true;
        setTimeout(() => { this.copied = false; }, 2500);
    }
}">
    <!-- Reseller Banner Header in Charcoal & Cream Glass -->
    <div class="bg-charcoal-luxury rounded-3xl p-7 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-cream-400/30">
        <div class="absolute inset-0 bg-cream-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-cream-btn text-charcoal-950 rounded-full shadow-xs">
                    ✦ Mitra Bisnis Reseller (Komisi {{ $resellerProfile->commission_rate_percent }}%)
                </span>
                @if($user->status->value === 'pending')
                    <span class="text-[9px] uppercase tracking-[0.2em] font-bold px-3 py-1 bg-amber-400 text-charcoal-950 rounded-full">
                        ⏳ Menunggu Verifikasi Admin
                    </span>
                @else
                    <span class="text-[9px] uppercase tracking-[0.2em] font-bold px-3 py-1 bg-emerald-400 text-charcoal-950 rounded-full">
                        ✓ Terverifikasi Aktif
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-4xl font-display font-bold tracking-tight text-white">Portal Reseller: {{ $user->name }}</h1>
            <p class="text-cream-200/90 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Pusat kendali bisnis kemitraan Anda. Dapatkan harga grosir termurah, sebar link referral, dan pantau mutasi saldo dompet komisi secara transparan.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Status Alert if Pending -->
    @if($user->status->value === 'pending')
        <div class="p-5 bg-amber-50/90 backdrop-blur-md border border-amber-300 rounded-3xl flex items-start space-x-3.5 text-amber-950 text-xs leading-relaxed shadow-xs">
            <div class="w-8 h-8 rounded-xl bg-amber-200/60 flex items-center justify-center text-amber-900 shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <div>
                <p class="font-display font-bold text-sm text-amber-950">Status Pendaftaran: Menunggu Peninjauan Admin</p>
                <p class="mt-1 text-amber-800 font-light">Akun kemitraan Anda sedang diverifikasi. Setelah disetujui, tier harga khusus reseller dan link referral aktif akan otomatis dapat digunakan untuk transaksi.</p>
            </div>
        </div>
    @endif

    <!-- Financial Cards in Frosted Glass -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <a href="{{ route('reseller.wallet.index') }}" class="glass-card p-6 rounded-3xl border-2 border-cream-400/80 shadow-lg hover:border-cream-500 transition-all block">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-800">Saldo Dompet Komisi</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 border border-cream-300 flex items-center justify-center text-cream-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2 font-mono">{{ $wallet->formatted_balance }}</p>
            <span class="text-[11px] text-emerald-800 font-semibold">Tersedia untuk ditarik &rarr;</span>
        </a>

        <a href="{{ route('reseller.commissions.index') }}" class="glass-card p-6 rounded-3xl hover:border-cream-400 transition-all block">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Komisi Tertunda</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2 font-mono">{{ $wallet->formatted_pending_balance }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Pesanan belum selesai &rarr;</span>
        </a>

        <div class="glass-card p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Transaksi Referral</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2 font-mono">{{ $referralOrdersCount }} Pesanan</p>
            <span class="text-[11px] text-charcoal-400 font-light">Total tercatat</span>
        </div>
    </div>

    <!-- Referral Link Box in Frosted Glass -->
    <div class="glass-card p-7 sm:p-8 rounded-3xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[9px] uppercase tracking-[0.25em] font-bold text-cream-700">Afiliasi &amp; Promosi</span>
                <h2 class="text-lg font-display font-bold text-charcoal-950 mt-1">Tautan Referral Eksklusif Anda</h2>
            </div>
            <a href="{{ route('reseller.withdrawals.index') }}" class="px-4 py-2 bg-cream-200 hover:bg-cream-300 text-charcoal-900 rounded-xl text-xs font-bold transition-colors">
                Tarik Saldo Komisi &rarr;
            </a>
        </div>
        <p class="text-xs text-charcoal-600 leading-relaxed max-w-2xl font-light">
            Sebarkan tautan di bawah ini ke media sosial (WhatsApp, Instagram, TikTok). Setiap transaksi sukses yang masuk lewat tautan Anda akan otomatis mencatatkan komisi ke buku kas dompet Anda.
        </p>

        @php
            $refUrl = url('/?ref=' . ($resellerProfile->referral_code ?? $user->phone ?? 'reseller-' . $user->id));
        @endphp

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
            <input type="text" readonly value="{{ $refUrl }}"
                class="flex-1 bg-white/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs font-mono text-charcoal-800 select-all focus:outline-none shadow-xs">
            <button type="button" @click="copyReferral('{{ $refUrl }}')"
                class="px-6 py-3.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs uppercase tracking-widest rounded-2xl shadow-xl border border-cream-400/30 hover:border-cream-300 transition-all shrink-0 flex items-center justify-center space-x-2">
                <template x-if="!copied">
                    <span class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                        <span>Salin Tautan</span>
                    </span>
                </template>
                <template x-if="copied">
                    <span class="text-emerald-300 font-bold">✓ Tautan Tersalin!</span>
                </template>
            </button>
        </div>
    </div>

    <!-- Recent Commissions and Wallet Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Commissions -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Komisi Referral Terbaru</h3>
                <a href="{{ route('reseller.commissions.index') }}" class="text-xs text-cream-800 hover:underline font-bold">Semua &rarr;</a>
            </div>

            @if($recentCommissions->isEmpty())
                <p class="text-xs text-charcoal-400 font-light py-6 text-center italic">Belum ada komisi referral.</p>
            @else
                <div class="divide-y divide-cream-100 text-xs">
                    @foreach($recentCommissions as $comm)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-mono font-bold text-charcoal-950 block">Pesanan #{{ $comm->order?->order_number }}</span>
                                <span class="text-[10px] text-charcoal-400">{{ $comm->created_at->format('d M Y H:i') }} &bull; Nilai: {{ $comm->formatted_subtotal }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-emerald-700 block">+{{ $comm->formatted_commission_amount }}</span>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $comm->status->badgeClasses() }}">
                                    {{ $comm->status->label() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Ledger Transactions -->
        <div class="glass-card p-6 rounded-3xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                <h3 class="font-display font-bold text-charcoal-950 text-sm">Mutasi Saldo Terakhir</h3>
                <a href="{{ route('reseller.wallet.index') }}" class="text-xs text-cream-800 hover:underline font-bold">Buku Kas &rarr;</a>
            </div>

            @if($recentTransactions->isEmpty())
                <p class="text-xs text-charcoal-400 font-light py-6 text-center italic">Belum ada mutasi dompet.</p>
            @else
                <div class="divide-y divide-cream-100 text-xs">
                    @foreach($recentTransactions as $tx)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-charcoal-900 block truncate max-w-xs">{{ $tx->description }}</span>
                                <span class="text-[10px] text-charcoal-400 font-mono">{{ $tx->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-xs {{ $tx->amount >= 0 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $tx->amount >= 0 ? '+' : '' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-charcoal-400 block font-mono">Saldo: Rp {{ number_format($tx->balance_after, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
