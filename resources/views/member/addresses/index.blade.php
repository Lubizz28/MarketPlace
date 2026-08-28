@extends('layouts.dashboard')

@section('title', 'Buku Alamat Pengiriman')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 glass-card p-6 sm:p-8 rounded-3xl">
        <div>
            <span class="text-[9px] uppercase tracking-[0.2em] font-bold text-cream-700">Pengaturan Pengiriman</span>
            <h1 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950 mt-0.5">Buku Alamat Pengiriman</h1>
            <p class="text-xs text-charcoal-500 mt-1 font-light">Kelola alamat tujuan untuk mempermudah perhitungan ongkir dan checkout cepat.</p>
        </div>
        <button @click="showAddModal = true" class="px-6 py-3.5 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl border border-cream-400/30 hover:border-cream-300 transition-all shrink-0 flex items-center justify-center space-x-2">
            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            <span>Tambah Alamat</span>
        </button>
    </div>

    <!-- Address Cards List in Frosted Glass -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($addresses as $addr)
            <div class="glass-card p-6 sm:p-7 rounded-3xl {{ $addr->is_primary ? 'border-2 border-cream-400/80 ring-1 ring-cream-400/30' : 'border border-cream-200/90' }} shadow-lg flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-xl bg-cream-100 flex items-center justify-center text-charcoal-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                            </div>
                            <span class="font-display font-bold text-sm text-charcoal-950">{{ $addr->label }}</span>
                        </div>
                        @if($addr->is_primary)
                            <span class="px-3 py-1 bg-charcoal-950 text-cream-300 text-[9px] font-bold uppercase tracking-wider rounded-full shadow-xs border border-cream-400/30">Utama</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-charcoal-900 text-sm">{{ $addr->recipient_name }}</p>
                        <p class="text-xs text-charcoal-500 font-mono">{{ $addr->phone }}</p>
                    </div>
                    <p class="text-xs text-charcoal-700 leading-relaxed font-light">{{ $addr->full_address }}</p>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-cream-200/80 text-xs">
                    <div>
                        @if(! $addr->is_primary)
                            <form method="POST" action="{{ route('member.addresses.primary', $addr) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-bold text-cream-800 hover:text-charcoal-950 hover:underline flex items-center space-x-1">
                                    <span>Jadikan Utama</span>
                                </button>
                            </form>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('member.addresses.destroy', $addr) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-bold text-rose-600 hover:text-rose-800 hover:underline flex items-center space-x-1">
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 rounded-3xl border border-dashed border-cream-300 text-center space-y-4 shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-cream-100 flex items-center justify-center mx-auto text-charcoal-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <h3 class="font-display font-bold text-charcoal-950 text-base">Belum Ada Alamat Tersimpan</h3>
                <p class="text-xs text-charcoal-500 max-w-sm mx-auto font-light">Tambahkan alamat pertama Anda untuk mempermudah perhitungan ongkos kirim dan pengiriman pesanan.</p>
                <button @click="showAddModal = true" class="mt-2 px-6 py-3 bg-charcoal-950 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl">
                    + Tambah Alamat Sekarang
                </button>
            </div>
        @endforelse
    </div>

    <!-- Add Address Modal in Frosted Glass -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-charcoal-950/60 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-white/95 backdrop-blur-2xl w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl z-50 max-h-[90vh] overflow-y-auto border border-cream-200">
            <div class="flex items-center justify-between pb-4 border-b border-cream-200 mb-5">
                <div>
                    <span class="text-[9px] uppercase tracking-[0.2em] font-bold text-cream-700">Form Alamat</span>
                    <h3 class="font-display font-bold text-charcoal-950 text-lg">Tambah Alamat Baru</h3>
                </div>
                <button @click="showAddModal = false" class="p-1.5 text-charcoal-400 hover:text-charcoal-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('member.addresses.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Label Alamat (cth. Rumah, Kantor, Toko)</label>
                    <input type="text" name="label" required placeholder="Rumah Utama" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Nama Penerima</label>
                        <input type="text" name="recipient_name" required placeholder="Nama penerima" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Nomor Telepon</label>
                        <input type="tel" name="phone" required placeholder="08123456789" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Alamat Lengkap</label>
                    <textarea name="address_line" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Provinsi</label>
                        <input type="text" name="province_name" required placeholder="Jawa Barat" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Kota / Kabupaten</label>
                        <input type="text" name="city_name" required placeholder="Kota Bandung" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Kecamatan</label>
                        <input type="text" name="district_name" placeholder="Coblong" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                    <div>
                        <label class="block font-bold text-charcoal-700 uppercase tracking-[0.15em] text-[10px] mb-1.5">Kode Pos</label>
                        <input type="text" name="postal_code" required placeholder="40132" class="w-full bg-cream-50/80 border border-cream-300 rounded-2xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-charcoal-900">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center space-x-2 text-charcoal-700 cursor-pointer">
                        <input type="checkbox" name="is_primary" value="1" class="rounded text-charcoal-950 focus:ring-charcoal-900 border-cream-300">
                        <span>Jadikan sebagai alamat utama</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-3 bg-cream-100 text-charcoal-700 rounded-2xl font-bold uppercase tracking-wider text-[11px]">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-charcoal-950 text-cream-200 rounded-2xl font-bold uppercase tracking-wider text-[11px] shadow-xl border border-cream-400/30">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
