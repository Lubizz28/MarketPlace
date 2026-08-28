@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Admin Header in Deep Charcoal Slate with Cream Glow -->
    <div class="bg-charcoal-luxury rounded-3xl p-7 sm:p-10 text-white shadow-2xl relative overflow-hidden border border-cream-400/20">
        <div class="absolute inset-0 bg-cream-pattern opacity-25 pointer-events-none"></div>
        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center space-x-2 text-[9px] uppercase tracking-[0.25em] font-bold px-3.5 py-1 bg-white/10 text-cream-300 rounded-full border border-cream-400/30 backdrop-blur-md shadow-xs">
                <span>⚡</span><span>Executive Control Panel</span>
            </span>
            <h1 class="text-2xl sm:text-4xl font-display font-bold tracking-tight text-white">Pusat Kendali Administrator</h1>
            <p class="text-cream-200/90 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                Ringkasan aktivitas platform marketplace, verifikasi kemitraan reseller, dan manajemen akun pengguna secara terpusat.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-60 h-60 rounded-full bg-cream-400/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Admin Overview Cards in Frosted Glass -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Total Pengguna</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">{{ $metrics['total_users'] }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Akun terdaftar</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-800">Member Belanja</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-cream-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">{{ $metrics['total_members'] }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Pelanggan aktif</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl border-2 border-cream-400/80 shadow-lg">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-cream-700">Mitra Reseller</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-cream-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">{{ $metrics['total_resellers'] }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Mitra afiliasi</span>
        </div>

        <div class="glass-card p-5 sm:p-6 rounded-3xl">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-charcoal-400">Staff Admin</span>
                <div class="w-8 h-8 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-display font-bold text-charcoal-950 mt-2">{{ $metrics['total_admins'] }}</p>
            <span class="text-[11px] text-charcoal-400 font-light">Pengelola platform</span>
        </div>
    </div>

    <!-- Latest Registered Users in Frosted Glass -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <div class="flex items-center justify-between pb-3.5 border-b border-cream-200/80">
            <h2 class="font-display font-bold text-charcoal-950 text-base">Pendaftar Terbaru</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 hover:underline">Kelola Semua Pengguna &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal-600">
                <thead class="bg-cream-100/90 text-charcoal-700 uppercase font-bold text-[9px] tracking-[0.18em] border-b border-cream-200">
                    <tr>
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @foreach($latestUsers as $u)
                        <tr class="hover:bg-cream-100/50 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-charcoal-900">{{ $u->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-[11px]">{{ $u->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $u->role->badgeColor() }}">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $u->status->value === 'active' ? 'bg-emerald-100 text-emerald-950' : 'bg-amber-100 text-amber-950' }}">
                                    {{ $u->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-400 font-light">{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
