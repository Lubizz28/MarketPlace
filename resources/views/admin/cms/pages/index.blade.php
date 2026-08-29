@extends('layouts.dashboard')

@section('title', 'CMS Halaman Statis — MedinaStyle')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">CMS Halaman Statis &amp; Informasi</h1>
            <p class="text-xs text-charcoal-500 font-light">Kelola halaman Tentang Kami, Kebijakan Privasi, Syarat &amp; Ketentuan, dan Panduan Belanja.</p>
        </div>
        <a href="{{ route('admin.cms.pages.create') }}" class="btn-gold inline-flex items-center space-x-2 text-xs font-bold px-4 py-2.5 rounded-2xl shadow-md">
            <span>+ Tambah Halaman Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-3xl p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cream-100/70 text-charcoal-950 uppercase tracking-wider font-bold border-b border-cream-200">
                        <th class="py-3 px-4">Judul Halaman</th>
                        <th class="py-3 px-4">Slug / URL Publik</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Terakhir Diperbarui</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($pages as $p)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-charcoal-950">
                                {{ $p->title }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-cream-900">
                                <a href="{{ route('pages.show', $p->slug) }}" target="_blank" class="hover:underline">
                                    /pages/{{ $p->slug }} &nearr;
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $p->is_active ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-500 text-[11px]">
                                {{ $p->updated_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.cms.pages.edit', $p) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Edit
                                </a>
                                <form action="{{ route('admin.cms.pages.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus halaman statis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada halaman statis yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection
