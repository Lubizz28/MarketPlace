@extends('layouts.dashboard')

@section('title', 'CMS Artikel & Blog — MedinaStyle')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">CMS Blog &amp; Hijab Styling Guides</h1>
            <p class="text-xs text-charcoal-500 font-light">Kelola artikel edukasi syar'i, tren busana muslimah, dan tips styling hijab.</p>
        </div>
        <a href="{{ route('admin.cms.posts.create') }}" class="btn-gold inline-flex items-center space-x-2 text-xs font-bold px-4 py-2.5 rounded-2xl shadow-md">
            <span>+ Tulis Artikel Baru</span>
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
                        <th class="py-3 px-4">Artikel</th>
                        <th class="py-3 px-4">Penulis</th>
                        <th class="py-3 px-4 text-center">Dibaca</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Diterbitkan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($posts as $p)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 flex items-center space-x-3">
                                @if($p->thumbnail_path)
                                    <img src="{{ $p->thumbnail_path }}" alt="{{ $p->title }}" class="w-12 h-12 object-cover rounded-xl border border-cream-300 shadow-sm flex-shrink-0">
                                @endif
                                <div>
                                    <span class="font-bold text-charcoal-950 block text-sm">{{ $p->title }}</span>
                                    <span class="text-[10px] text-charcoal-400 font-mono">/blog/{{ $p->slug }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-charcoal-700">
                                {{ $p->author?->name ?? 'Admin' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-charcoal-900">
                                {{ number_format($p->view_count, 0, ',', '.') }}x
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $p->is_published ? 'bg-emerald-100 text-emerald-950' : 'bg-amber-100 text-amber-950' }}">
                                    {{ $p->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-charcoal-500 text-[11px]">
                                {{ $p->published_at ? $p->published_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.cms.posts.edit', $p) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Edit
                                </a>
                                <form action="{{ route('admin.cms.posts.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
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
                            <td colspan="6" class="py-8 text-center text-charcoal-400 font-light italic">
                                Belum ada artikel blog yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
