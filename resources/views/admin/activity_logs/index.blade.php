@extends('layouts.dashboard')

@section('title', 'Log Aktivitas & Audit Trail — MedinaStyle')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">Log Aktivitas &amp; Audit Trail</h1>
            <p class="text-xs text-charcoal-500 font-light">Rekaman jejak audit sistem, pembaruan status pesanan, mutasi inventori, dan perubahan konfigurasi.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="glass-card rounded-3xl p-4 sm:p-6 space-y-4">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-600 mb-1">Cari Keterangan / IP / Operator</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci pencarian..."
                    class="w-full bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-600 mb-1">Filter Tipe Aksi</label>
                <select name="action" class="w-full bg-white/90 border border-cream-300 rounded-xl py-2 px-3 text-xs text-charcoal-950 focus:outline-none focus:border-cream-500">
                    <option value="">Semua Tipe Aksi</option>
                    <option value="banner_created" {{ request('action') === 'banner_created' ? 'selected' : '' }}>Banner Dibuat</option>
                    <option value="banner_updated" {{ request('action') === 'banner_updated' ? 'selected' : '' }}>Banner Diperbarui</option>
                    <option value="page_created" {{ request('action') === 'page_created' ? 'selected' : '' }}>Halaman Dibuat</option>
                    <option value="post_created" {{ request('action') === 'post_created' ? 'selected' : '' }}>Artikel Dibuat</option>
                    <option value="settings_updated" {{ request('action') === 'settings_updated' ? 'selected' : '' }}>Pengaturan Diperbarui</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full btn-gold py-2 px-4 rounded-xl text-xs font-bold shadow">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['q', 'action']))
                    <a href="{{ route('admin.activity-logs.index') }}" class="p-2 rounded-xl border border-cream-300 text-charcoal-600 hover:bg-cream-100/60 text-xs">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Operator / Pengguna</th>
                        <th class="py-3 px-4">Tipe Aksi</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4">Alamat IP &amp; Agen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-[11px] text-charcoal-500 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-charcoal-950">
                                {{ $log->user?->name ?? 'System / Anonymous' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-charcoal-950 text-cream-300 font-mono">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-800">
                                {{ $log->description }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[10px] text-charcoal-400">
                                <span class="block">{{ $log->ip_address }}</span>
                                <span class="block truncate max-w-xs">{{ Str::limit($log->user_agent, 40) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada log aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
