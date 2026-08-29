@extends('layouts.dashboard')

@section('title', 'CMS Banner Promo — MedinaStyle')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">CMS Hero Banner &amp; Slider</h1>
            <p class="text-xs text-charcoal-500 font-light">Kelola gambar banner promosi, call-to-action, dan visual etalase utama.</p>
        </div>
        <a href="{{ route('admin.cms.banners.create') }}" class="btn-gold inline-flex items-center space-x-2 text-xs font-bold px-4 py-2.5 rounded-2xl shadow-md">
            <span>+ Tambah Banner Baru</span>
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
                        <th class="py-3 px-4">Urutan</th>
                        <th class="py-3 px-4">Visual Banner</th>
                        <th class="py-3 px-4">Judul &amp; Subtitle</th>
                        <th class="py-3 px-4">Tombol CTA</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-100">
                    @forelse($banners as $b)
                        <tr class="hover:bg-cream-50/50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-charcoal-950">
                                #{{ $b->sort_order }}
                            </td>
                            <td class="py-3.5 px-4">
                                <img src="{{ $b->image_path }}" alt="{{ $b->title }}" class="w-28 h-14 object-cover rounded-xl border border-cream-300 shadow-sm">
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-charcoal-950 block">{{ $b->title }}</span>
                                <span class="text-[10px] text-charcoal-500 block line-clamp-1">{{ $b->subtitle ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($b->button_text)
                                    <span class="px-2.5 py-1 rounded-lg bg-charcoal-950 text-cream-200 text-[10px] font-bold">
                                        {{ $b->button_text }} &rarr; {{ $b->button_url }}
                                    </span>
                                @else
                                    <span class="text-charcoal-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $b->is_active ? 'bg-emerald-100 text-emerald-950' : 'bg-rose-100 text-rose-950' }}">
                                    {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.cms.banners.edit', $b) }}" class="text-xs font-bold text-cream-800 hover:text-charcoal-950 underline">
                                    Edit
                                </a>
                                <form action="{{ route('admin.cms.banners.destroy', $b) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner ini?')">
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
                                Belum ada banner promosi yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3">
            {{ $banners->links() }}
        </div>
    </div>
</div>
@endsection
