@extends('layouts.dashboard')

@section('title', 'Kelola Kupon Promo — Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">Manajemen Kupon &amp; Voucher</h1>
            <p class="text-xs text-charcoal-500 font-light mt-0.5">Kelola kupon diskon nominal tetap, persentase diskon, kuota &amp; batasan penggunaan.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center space-x-2 px-5 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold text-xs rounded-2xl transition-smooth shadow-md">
            <span>+ Buat Kupon Baru</span>
        </a>
    </div>

    <!-- Filters and Search -->
    <div class="glass-card p-5 rounded-3xl space-y-4">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
            <div class="sm:col-span-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama kupon..."
                    class="w-full px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
            </div>

            <div class="sm:col-span-3">
                <select name="type" class="w-full px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    <option value="">Semua Tipe Diskon</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="is_active" class="w-full px-4 py-2.5 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 py-2.5 bg-charcoal-950 text-cream-200 font-bold rounded-2xl hover:bg-charcoal-900 transition-smooth text-xs">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'is_active']))
                    <a href="{{ route('admin.coupons.index') }}" class="px-3 py-2.5 bg-cream-200 text-charcoal-700 rounded-2xl hover:bg-cream-300 transition-colors text-xs flex items-center justify-center">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="glass-card p-6 rounded-3xl space-y-4">
        @if($coupons->isEmpty())
            <div class="text-center py-12 space-y-3">
                <div class="w-12 h-12 rounded-full bg-cream-100 flex items-center justify-center text-charcoal-400 mx-auto text-lg">🎟️</div>
                <p class="text-xs text-charcoal-500 font-light">Tidak ada data kupon promo yang sesuai kriteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-cream-200 text-charcoal-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-3">Kode Kupon</th>
                            <th class="py-3 px-3">Nama Kupon</th>
                            <th class="py-3 px-3">Tipe &amp; Nilai</th>
                            <th class="py-3 px-3">Min. Belanja</th>
                            <th class="py-3 px-3 text-center">Penggunaan</th>
                            <th class="py-3 px-3">Masa Berlaku</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-100 font-light text-charcoal-800">
                        @foreach($coupons as $coupon)
                            <tr class="hover:bg-cream-50/50 transition-colors">
                                <td class="py-3.5 px-3">
                                    <span class="font-mono font-bold text-charcoal-950 text-xs px-2.5 py-1 bg-cream-100 border border-cream-200 rounded-xl">
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 font-semibold text-charcoal-900">
                                    {{ $coupon->name }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $coupon->type->badgeClasses() }}">
                                        {{ $coupon->type->label() }}
                                    </span>
                                    <span class="font-mono font-bold text-charcoal-950 block mt-1">
                                        {{ $coupon->formatted_discount }}
                                        @if($coupon->max_discount)
                                            <span class="text-[10px] text-charcoal-400 font-normal block">(Maks: Rp {{ number_format($coupon->max_discount, 0, ',', '.') }})</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 font-mono">
                                    {{ $coupon->formatted_min_order }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-mono">
                                    <span class="font-bold text-charcoal-950">{{ $coupon->used_count }}</span>
                                    <span class="text-charcoal-400 text-[10px]">/ {{ $coupon->max_uses ?? '∞' }}</span>
                                    <span class="text-[10px] text-charcoal-400 block font-sans">({{ $coupon->per_user_limit }}x/user)</span>
                                </td>
                                <td class="py-3.5 px-3 text-[11px] text-charcoal-500 font-light">
                                    @if($coupon->start_at || $coupon->expires_at)
                                        <div>{{ $coupon->start_at ? $coupon->start_at->format('d/m/Y') : 'Sekarang' }}</div>
                                        <div>s/d {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Selamanya' }}</div>
                                    @else
                                        <span class="text-emerald-700 font-medium">Permanen</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $coupon->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-charcoal-100 text-charcoal-600 border border-charcoal-300 hover:bg-charcoal-200' }}">
                                            {{ $coupon->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="px-3 py-1.5 bg-cream-200 hover:bg-cream-300 text-charcoal-900 rounded-xl font-bold text-xs transition-colors">
                                            Edit
                                        </a>
                                        @if($coupon->usages_count === 0)
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Hapus kupon promo ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-xs transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4 border-t border-cream-200">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
