@extends('layouts.dashboard')

@section('title', 'Edit Kupon Promo #' . $coupon->code . ' — Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    type: '{{ old('type', $coupon->type->value) }}'
}">
    <!-- Header -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.coupons.index') }}" class="p-2.5 rounded-2xl bg-white border border-cream-300 text-charcoal-700 hover:bg-cream-100 transition-colors">
            &larr;
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-charcoal-950">Edit Kupon #{{ $coupon->code }}</h1>
            <p class="text-xs text-charcoal-500 font-light mt-0.5">Perbarui informasi kupon promo, nilai diskon, atau kuota penggunaan.</p>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold">Harap perbaiki kesalahan berikut:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-card p-6 sm:p-8 rounded-3xl">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
                <!-- Code -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Kode Kupon Promo *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono uppercase text-xs">
                </div>

                <!-- Name -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Nama Promosi *</label>
                    <input type="text" name="name" value="{{ old('name', $coupon->name) }}" required
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="font-bold text-charcoal-700">Deskripsi / Syarat Ketentuan</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">{{ old('description', $coupon->description) }}</textarea>
                </div>

                <!-- Type Selector -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Tipe Diskon *</label>
                    <select name="type" x-model="type" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                        <option value="fixed">Nominal Tetap (Potongan Rp)</option>
                        <option value="percent">Persentase (Potongan %)</option>
                    </select>
                </div>

                <!-- Amount -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">
                        <span x-show="type === 'fixed'">Nilai Potongan Rupiah *</span>
                        <span x-show="type === 'percent'">Persentase Diskon (%) *</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount', $coupon->amount) }}" required min="1"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                </div>

                <!-- Max Discount Cap (Only for percent) -->
                <div class="space-y-1.5" x-show="type === 'percent'">
                    <label class="font-bold text-charcoal-700">Maksimal Nominal Diskon (Cap Rp)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}" min="0"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                </div>

                <!-- Minimum Order Amount -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Minimal Total Belanja (Rp)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                </div>

                <!-- Max Uses (Overall Quota) -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Batas Kuota Keseluruhan</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                    <span class="text-[10px] text-charcoal-400">Telah digunakan: <b>{{ $coupon->used_count }}</b> kali.</span>
                </div>

                <!-- Per User Limit -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Batas Penggunaan per Pengguna *</label>
                    <input type="number" name="per_user_limit" value="{{ old('per_user_limit', $coupon->per_user_limit) }}" required min="1"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 font-mono text-xs">
                </div>

                <!-- Start At -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Tanggal Mulai Berlaku</label>
                    <input type="datetime-local" name="start_at" value="{{ old('start_at', $coupon->start_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                </div>

                <!-- Expires At -->
                <div class="space-y-1.5">
                    <label class="font-bold text-charcoal-700">Tanggal Kedaluwarsa</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                </div>

                <!-- Is Active -->
                <div class="sm:col-span-2 pt-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded-lg border-cream-300 text-charcoal-950 focus:ring-charcoal-950">
                        <span class="font-bold text-charcoal-900 text-xs">Kupon promo aktif &amp; dapat digunakan pelanggan</span>
                    </label>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="pt-6 border-t border-cream-200 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-5 py-3 bg-cream-200 hover:bg-cream-300 text-charcoal-800 font-bold rounded-2xl text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-wider transition-smooth shadow-md">
                    Perbarui Kupon Promo
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
