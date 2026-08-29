@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna & CRM — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Customer Relationship Management</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Basis Data Pengguna &amp; Member</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Kelola data pelanggan belanja, keanggotaan loyalty member, mitra reseller, dan moderasi akun pengguna.</p>
        </div>
    </div>

    <!-- User KPI Metrics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-charcoal-400">Total Pengguna</span>
            <p class="text-2xl font-bold font-mono text-charcoal-950 mt-1">{{ $stats['total_users'] }}</p>
            <span class="text-[10px] text-charcoal-400">Semua akun terdaftar</span>
        </div>

        <div class="glass-card p-5 rounded-3xl border-2 border-cream-400">
            <span class="text-[9px] font-bold uppercase tracking-wider text-cream-900">Member Belanja</span>
            <p class="text-2xl font-bold font-mono text-cream-950 mt-1">{{ $stats['total_members'] }}</p>
            <span class="text-[10px] text-cream-800">Pelanggan ritel aktif</span>
        </div>

        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-900">Mitra Reseller</span>
            <p class="text-2xl font-bold font-mono text-emerald-950 mt-1">{{ $stats['total_resellers'] }}</p>
            <span class="text-[10px] text-emerald-800">Afiliasi &amp; Grosir</span>
        </div>

        <div class="glass-card p-5 rounded-3xl">
            <span class="text-[9px] font-bold uppercase tracking-wider text-rose-800">Akun Dinonaktifkan</span>
            <p class="text-2xl font-bold font-mono text-rose-950 mt-1">{{ $stats['banned_count'] }}</p>
            <span class="text-[10px] text-rose-700">Status banned</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-card p-4 rounded-3xl">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center gap-3 text-xs">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau no. telepon..."
                    class="w-full bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <select name="role" class="bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none">
                <option value="">Semua Peran (Role)</option>
                <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member Sahaja</option>
                <option value="reseller" {{ request('role') === 'reseller' ? 'selected' : '' }}>Mitra Reseller</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>

            <select name="status" class="bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-charcoal-950 text-cream-200 rounded-xl font-bold">Filter</button>
            @if(request()->hasAny(['q', 'role', 'status']))
                <a href="{{ route('admin.customers.index') }}" class="px-2 py-2 text-charcoal-400 hover:text-charcoal-800 text-xs">Reset</a>
            @endif
        </form>
    </div>

    <!-- Users CRM Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Nama Pelanggan</th>
                        <th class="py-3 px-4">Kontak (Email / Telepon)</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4 text-center">Poin Loyalitas</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Bergabung</th>
                        <th class="py-3 px-4 text-right">Aksi CRM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block text-sm">{{ $u->name }}</span>
                                @if($u->isReseller() && $u->resellerProfile)
                                    <span class="text-[10px] text-emerald-800 font-medium">Toko: {{ $u->resellerProfile->store_name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] text-charcoal-800 block">{{ $u->email }}</span>
                                <span class="font-mono text-[10px] text-charcoal-400 block">{{ $u->phone ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $u->role->badgeColor() }}">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-cream-950">
                                {{ number_format($u->points_balance, 0, ',', '.') }} pts
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $u->status->value === 'active' ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                                    {{ $u->status->label() }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-400">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.customers.show', $u) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Profil 360° &rarr;
                                </a>
                                @if(!$u->isAdmin())
                                    <form method="POST" action="{{ route('admin.customers.toggle', $u) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold {{ $u->status->value === 'active' ? 'text-rose-600 hover:text-rose-900' : 'text-emerald-700 hover:text-emerald-950' }}">
                                            {{ $u->status->value === 'active' ? 'Ban' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
