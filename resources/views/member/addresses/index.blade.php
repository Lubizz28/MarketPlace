@extends('layouts.dashboard')

@section('title', 'Buku Alamat Pengiriman')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-stone-200/80 shadow-soft">
        <div>
            <h1 class="text-xl font-bold text-stone-900">Buku Alamat Pengiriman</h1>
            <p class="text-xs text-stone-500 mt-0.5">Kelola alamat tujuan untuk mempermudah perhitungan ongkir dan checkout cepat.</p>
        </div>
        <button @click="showAddModal = true" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-2xl text-xs shadow-md transition-all shrink-0">
            + Tambah Alamat Baru
        </button>
    </div>

    <!-- Address Cards List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($addresses as $addr)
            <div class="bg-white p-6 rounded-3xl border {{ $addr->is_primary ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-stone-200/80' }} shadow-soft flex flex-col justify-between space-y-4">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-sm text-stone-900">{{ $addr->label }}</span>
                            @if($addr->is_primary)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold rounded-full">Utama</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-stone-800 text-sm">{{ $addr->recipient_name }}</p>
                        <p class="text-xs text-stone-500">{{ $addr->phone }}</p>
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed">{{ $addr->full_address }}</p>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-stone-100 text-xs">
                    <div>
                        @if(! $addr->is_primary)
                            <form method="POST" action="{{ route('member.addresses.primary', $addr) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-semibold text-emerald-800 hover:underline">Jadikan Utama</button>
                            </form>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('member.addresses.destroy', $addr) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-semibold text-rose-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-3xl border border-dashed border-stone-300 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto text-xl">📍</div>
                <h3 class="font-bold text-stone-800 text-sm">Belum Ada Alamat Tersimpan</h3>
                <p class="text-xs text-stone-500 max-w-sm mx-auto">Tambahkan alamat pertama Anda untuk pengiriman pesanan yang lebih cepat.</p>
                <button @click="showAddModal = true" class="mt-2 px-4 py-2 bg-emerald-800 text-white font-bold rounded-xl text-xs">
                    Tambah Alamat Sekarang
                </button>
            </div>
        @endforelse
    </div>

    <!-- Add Address Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-stone-900/60" @click="showAddModal = false"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl z-50 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-5">
                <h3 class="font-bold text-stone-900 text-base">Tambah Alamat Baru</h3>
                <button @click="showAddModal = false" class="text-stone-400 hover:text-stone-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('member.addresses.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Label Alamat (cth. Rumah, Kantor)</label>
                    <input type="text" name="label" required placeholder="Rumah" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name" required placeholder="Nama penerima" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Nomor Telepon</label>
                        <input type="tel" name="phone" required placeholder="08123456789" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <textarea name="address_line" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Provinsi</label>
                        <input type="text" name="province_name" required placeholder="Jawa Barat" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city_name" required placeholder="Bandung" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Kecamatan</label>
                        <input type="text" name="district_name" placeholder="Coblong" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                    <div>
                        <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Kode Pos</label>
                        <input type="text" name="postal_code" required placeholder="40132" class="w-full bg-stone-50 border border-stone-200 rounded-xl py-2.5 px-3 text-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center space-x-2 text-stone-700 cursor-pointer">
                        <input type="checkbox" name="is_primary" value="1" class="rounded text-emerald-800 focus:ring-emerald-700 border-stone-300">
                        <span>Jadikan sebagai alamat utama</span>
                    </label>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2.5 bg-stone-100 text-stone-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-800 text-white rounded-xl font-bold shadow-md">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
