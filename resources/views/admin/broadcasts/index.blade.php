@extends('layouts.dashboard')

@section('title', 'Pesan Siaran & Notifikasi — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-charcoal-400">Marketing &amp; Notifikasi</span>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Pesan Siaran (Broadcast)</h1>
            <p class="text-xs text-charcoal-500 font-light mt-1">Kirimkan pesan promosi, pengumuman promo, dan informasi produk via WhatsApp &amp; Email massal.</p>
        </div>
        <a href="{{ route('admin.broadcasts.create') }}" class="px-5 py-2.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 rounded-2xl text-xs font-bold transition-all shadow-md flex items-center space-x-2 text-center">
            <span>+ Buat Siaran Baru</span>
        </a>
    </div>

    <!-- Broadcasts Table in Glass Card -->
    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Judul Kampanye</th>
                        <th class="py-3 px-4">Sasaran Target</th>
                        <th class="py-3 px-4">Saluran</th>
                        <th class="py-3 px-4 text-center">Total Terkirim</th>
                        <th class="py-3 px-4">Waktu Siaran</th>
                        <th class="py-3 px-4">Pengirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($broadcasts as $bc)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $bc->title }}</span>
                                <span class="text-[10px] text-charcoal-500 line-clamp-1 max-w-sm">{{ $bc->message }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase bg-cream-200 text-charcoal-800">
                                    {{ $bc->target_role === 'all' ? 'Semua Pengguna' : ($bc->target_role === 'member' ? 'Member Sahaja' : 'Mitra Reseller') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 uppercase font-mono font-bold text-[10px] text-charcoal-700">
                                {{ $bc->channel }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-800">
                                {{ $bc->total_recipients }} Penerima
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-500">
                                {{ $bc->sent_at ? $bc->sent_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-charcoal-700">
                                {{ $bc->sender?->name ?? 'Admin' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada riwayat pesan siaran yang dikirim.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $broadcasts->links() }}
        </div>
    </div>
</div>
@endsection
