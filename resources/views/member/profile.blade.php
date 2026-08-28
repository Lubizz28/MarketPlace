@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-soft">
    <div class="pb-5 border-b border-stone-100 mb-6">
        <h1 class="text-xl font-bold text-stone-900">Edit Profil & Informasi Personal</h1>
        <p class="text-xs text-stone-500 mt-1">Perbarui informasi diri Anda untuk mempermudah konfirmasi transaksi dan pengiriman.</p>
    </div>

    <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full bg-stone-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('name')
                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Alamat Email (Permanen)</label>
            <input type="email" id="email" value="{{ $user->email }}" disabled
                class="w-full bg-stone-100 border border-stone-200 rounded-2xl py-3 px-4 text-sm text-stone-500 cursor-not-allowed">
            <p class="text-[11px] text-stone-400 mt-1">Email digunakan sebagai ID akun unik dan tidak dapat diubah secara langsung.</p>
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Nomor WhatsApp / HP</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                class="w-full bg-stone-50 border @error('phone') border-rose-400 bg-rose-50/30 @else border-stone-200 @enderror rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            @error('phone')
                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Jenis Kelamin</label>
                <select id="gender" name="gender" class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male" @selected(old('gender', $user->profile?->gender?->value) === 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender', $user->profile?->gender?->value) === 'female')>Perempuan</option>
                </select>
            </div>

            <div>
                <label for="birthdate" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Tanggal Lahir</label>
                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->profile?->birthdate?->format('Y-m-d')) }}"
                    class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">
            </div>
        </div>

        <div>
            <label for="bio" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1.5">Catatan / Bio Singkat</label>
            <textarea id="bio" name="bio" rows="3" placeholder="Tuliskan catatan singkat..."
                class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white transition-all">{{ old('bio', $user->profile?->bio) }}</textarea>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-2xl text-sm shadow-md transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
