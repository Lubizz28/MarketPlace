@extends('layouts.app')

@section('title', 'Checkout Pembayaran — Kemewahan Busana Muslim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
    x-data="{
        customerName: '{{ old('customer_name', $user?->name ?? '') }}',
        customerEmail: '{{ old('customer_email', $user?->email ?? '') }}',
        customerPhone: '{{ old('customer_phone', $user?->phone ?? '') }}',
        notes: '{{ old('notes', '') }}',
        
        recipientName: '{{ old('recipient_name', $savedAddresses->first()?->recipient_name ?? $user?->name ?? '') }}',
        recipientPhone: '{{ old('recipient_phone', $savedAddresses->first()?->phone ?? $user?->phone ?? '') }}',
        
        selectedProvinceId: '{{ old('province_id', $savedAddresses->first()?->province_id ?? '1') }}',
        selectedProvinceName: '{{ old('province_name', $savedAddresses->first()?->province_name ?? 'DKI Jakarta') }}',
        
        selectedCityId: '{{ old('city_id', $savedAddresses->first()?->city_id ?? '152') }}',
        selectedCityName: '{{ old('city_name', $savedAddresses->first()?->city_name ?? 'Kota Jakarta Pusat') }}',
        
        subdistrictName: '{{ old('subdistrict_name', $savedAddresses->first()?->subdistrict_name ?? '') }}',
        postalCode: '{{ old('postal_code', $savedAddresses->first()?->postal_code ?? '10110') }}',
        addressLine: `{{ old('address_line', $savedAddresses->first()?->address_line ?? '') }}`,
        
        cities: [],
        shippingOptions: [],
        isLoadingCities: false,
        isLoadingShipping: false,
        
        selectedCourierCode: '{{ old('courier_code', 'jne') }}',
        selectedCourierName: '{{ old('courier_name', 'Jalur Nugraha Ekakurir (JNE)') }}',
        selectedServiceName: '{{ old('service_name', 'REG') }}',
        selectedServiceDesc: '{{ old('service_description', 'Layanan Reguler') }}',
        selectedEtdDays: '{{ old('etd_days', '2-3') }}',
        shippingCost: {{ old('shipping_cost', 18000) }},
        
        selectedPaymentMethod: '{{ old('payment_method', 'qris') }}',
        
        subtotal: {{ $cartTotals['subtotal'] }},
        totalWeightGrams: {{ max(1000, $cartTotals['total_weight_grams']) }},

        get grandTotal() {
            return this.subtotal + parseInt(this.shippingCost || 0);
        },

        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        },

        async fetchCities(provinceId, provinceName = '') {
            if (!provinceId) return;
            this.selectedProvinceId = provinceId;
            if (provinceName) this.selectedProvinceName = provinceName;
            
            this.isLoadingCities = true;
            try {
                const res = await fetch('{{ route('checkout.cities') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ province_id: provinceId })
                });
                const data = await res.json();
                this.cities = data.cities || [];
                if (this.cities.length > 0 && !this.selectedCityId) {
                    this.selectCity(this.cities[0]);
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoadingCities = false;
            }
        },

        selectCity(city) {
            this.selectedCityId = city.id;
            this.selectedCityName = city.name;
            if (city.postal_code) this.postalCode = city.postal_code;
            this.calculateShippingRates();
        },

        async calculateShippingRates() {
            if (!this.selectedCityId) return;
            this.isLoadingShipping = true;
            try {
                const res = await fetch('{{ route('checkout.shipping') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ city_id: this.selectedCityId })
                });
                const data = await res.json();
                this.shippingOptions = data.options || [];
                if (this.shippingOptions.length > 0) {
                    this.selectShippingOption(this.shippingOptions[0]);
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoadingShipping = false;
            }
        },

        selectShippingOption(opt) {
            this.selectedCourierCode = opt.courier_code;
            this.selectedCourierName = opt.courier_name;
            this.selectedServiceName = opt.service_code;
            this.selectedServiceDesc = opt.service_description;
            this.selectedEtdDays = opt.etd_days;
            this.shippingCost = opt.cost;
        },

        applySavedAddress(addr) {
            this.recipientName = addr.recipient_name;
            this.recipientPhone = addr.phone;
            this.selectedProvinceId = addr.province_id;
            this.selectedProvinceName = addr.province_name;
            this.selectedCityId = addr.city_id;
            this.selectedCityName = addr.city_name;
            this.subdistrictName = addr.subdistrict_name || '';
            this.postalCode = addr.postal_code;
            this.addressLine = addr.address_line;
            this.calculateShippingRates();
        },

        init() {
            this.fetchCities(this.selectedProvinceId, this.selectedProvinceName);
            this.calculateShippingRates();
        }
    }">

    <!-- Page Header -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-xs text-charcoal-500 font-light mb-2">
            <a href="{{ route('cart.index') }}" class="hover:text-charcoal-900">&larr; Kembali ke Keranjang</a>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950">Konfirmasi Pemesanan &amp; Checkout</h1>
        <p class="text-xs text-charcoal-500 font-light mt-1">Lengkapi data pengiriman dan pilih metode pembayaran resmi terenkripsi.</p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold">Harap lengkapi formulir dengan benar:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
        @csrf

        <!-- Hidden Form Inputs synced with Alpine.js -->
        <input type="hidden" name="customer_name" :value="customerName">
        <input type="hidden" name="customer_email" :value="customerEmail">
        <input type="hidden" name="customer_phone" :value="customerPhone">
        <input type="hidden" name="notes" :value="notes">

        <input type="hidden" name="recipient_name" :value="recipientName">
        <input type="hidden" name="recipient_phone" :value="recipientPhone">
        <input type="hidden" name="province_id" :value="selectedProvinceId">
        <input type="hidden" name="province_name" :value="selectedProvinceName">
        <input type="hidden" name="city_id" :value="selectedCityId">
        <input type="hidden" name="city_name" :value="selectedCityName">
        <input type="hidden" name="subdistrict_name" :value="subdistrictName">
        <input type="hidden" name="postal_code" :value="postalCode">
        <input type="hidden" name="address_line" :value="addressLine">

        <input type="hidden" name="courier_code" :value="selectedCourierCode">
        <input type="hidden" name="courier_name" :value="selectedCourierName">
        <input type="hidden" name="service_name" :value="selectedServiceName">
        <input type="hidden" name="service_description" :value="selectedServiceDesc">
        <input type="hidden" name="etd_days" :value="selectedEtdDays">
        <input type="hidden" name="shipping_cost" :value="shippingCost">

        <input type="hidden" name="payment_method" :value="selectedPaymentMethod">

        <!-- Left Column: Checkout Inputs (7 Cols) -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Step 1: Customer Contact -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-cream-200">
                    <span class="w-7 h-7 rounded-full bg-charcoal-950 text-cream-300 flex items-center justify-center font-bold text-xs">1</span>
                    <h2 class="font-display font-bold text-charcoal-950 text-base">Informasi Kontak Pemesan</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="font-bold text-charcoal-700">Nama Lengkap *</label>
                        <input type="text" x-model="customerName" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Email Notifikasi *</label>
                        <input type="email" x-model="customerEmail" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Nomor WhatsApp / HP *</label>
                        <input type="tel" x-model="customerPhone" required placeholder="0812xxxx" class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>
                </div>
            </div>

            <!-- Step 2: Shipping Destination Address -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-full bg-charcoal-950 text-cream-300 flex items-center justify-center font-bold text-xs">2</span>
                        <h2 class="font-display font-bold text-charcoal-950 text-base">Alamat Pengiriman</h2>
                    </div>
                </div>

                <!-- Saved Address Picker for Logged in Members -->
                @if($savedAddresses->isNotEmpty())
                    <div class="space-y-2">
                        <label class="font-bold text-[11px] uppercase tracking-wider text-charcoal-500">Pilih dari Buku Alamat Tersimpan:</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach($savedAddresses as $saved)
                                <button type="button" @click="applySavedAddress({{ json_encode($saved) }})"
                                    class="p-3 rounded-2xl text-left border transition-smooth text-xs hover:border-cream-400 bg-white/60">
                                    <div class="font-bold text-charcoal-950">{{ $saved->recipient_name }}</div>
                                    <div class="text-[11px] text-charcoal-500 truncate">{{ $saved->city_name }}, {{ $saved->province_name }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="border-t border-cream-100 my-2"></div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Nama Penerima *</label>
                        <input type="text" x-model="recipientName" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Nomor Telepon Penerima *</label>
                        <input type="tel" x-model="recipientPhone" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>

                    <!-- Province Selector -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Provinsi *</label>
                        <select @change="fetchCities($event.target.value, $event.target.options[$event.target.selectedIndex].text)"
                            class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                            @foreach($provinces as $prov)
                                <option value="{{ $prov['id'] }}" :selected="selectedProvinceId == '{{ $prov['id'] }}'">
                                    {{ $prov['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Selector -->
                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Kota / Kabupaten *</label>
                        <div class="relative">
                            <select @change="selectCity({ id: $event.target.value, name: $event.target.options[$event.target.selectedIndex].text })"
                                class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                                <template x-for="c in cities" :key="c.id">
                                    <option :value="c.id" x-text="c.name" :selected="selectedCityId == c.id"></option>
                                </template>
                            </select>
                            <div x-show="isLoadingCities" class="absolute right-3 top-3 text-[10px] text-charcoal-400">Memuat...</div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Kecamatan / Kelurahan</label>
                        <input type="text" x-model="subdistrictName" placeholder="Contoh: Gambir" class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-bold text-charcoal-700">Kode Pos *</label>
                        <input type="text" x-model="postalCode" required class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs font-mono">
                    </div>

                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="font-bold text-charcoal-700">Alamat Lengkap &amp; Patokan Rumah *</label>
                        <textarea x-model="addressLine" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, cluster / patokan..." class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs"></textarea>
                    </div>

                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="font-bold text-charcoal-700">Catatan Khusus untuk Toko / Kurir (Opsional)</label>
                        <input type="text" x-model="notes" placeholder="Contoh: Titip di satpam jika tidak ada orang" class="w-full px-4 py-3 bg-white/90 border border-cream-300 rounded-2xl focus:ring-2 focus:ring-charcoal-950 text-xs">
                    </div>
                </div>
            </div>

            <!-- Step 3: Courier & Service Selection -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-cream-200">
                    <div class="flex items-center space-x-3">
                        <span class="w-7 h-7 rounded-full bg-charcoal-950 text-cream-300 flex items-center justify-center font-bold text-xs">3</span>
                        <h2 class="font-display font-bold text-charcoal-950 text-base">Pilihan Ekspedisi &amp; Layanan Pengiriman</h2>
                    </div>
                    <span class="text-xs font-mono font-bold text-charcoal-600 bg-cream-200 px-2.5 py-1 rounded-xl" x-text="'Total Berat: ' + (totalWeightGrams / 1000).toFixed(1) + ' kg'"></span>
                </div>

                <div x-show="isLoadingShipping" class="py-8 text-center text-xs text-charcoal-500">
                    <svg class="animate-spin h-5 w-5 text-charcoal-900 mx-auto mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Mengkalkulasi ongkos kirim resmi ke <span x-text="selectedCityName"></span>...
                </div>

                <div x-show="!isLoadingShipping" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="opt in shippingOptions" :key="opt.courier_code + opt.service_code">
                        <button type="button" @click="selectShippingOption(opt)"
                            :class="{
                                'border-charcoal-950 bg-charcoal-950 text-cream-200 shadow-md ring-2 ring-charcoal-950/20': selectedCourierCode === opt.courier_code && selectedServiceName === opt.service_code,
                                'border-cream-300 bg-white/80 text-charcoal-900 hover:border-cream-400': !(selectedCourierCode === opt.courier_code && selectedServiceName === opt.service_code)
                            }"
                            class="p-4 rounded-2xl border text-left transition-smooth flex flex-col justify-between space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold uppercase tracking-wider text-xs" x-text="opt.courier_code + ' — ' + opt.service_code"></span>
                                <span class="text-[11px] font-mono font-bold" x-text="opt.formatted_cost"></span>
                            </div>
                            <div class="text-[11px] opacity-80" x-text="opt.service_description"></div>
                            <div class="text-[10px] opacity-70 font-light" x-text="'Estimasi Tiba: ' + opt.etd_days + ' hari kerja'"></div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Step 4: Payment Method Selection -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-cream-200">
                    <span class="w-7 h-7 rounded-full bg-charcoal-950 text-cream-300 flex items-center justify-center font-bold text-xs">4</span>
                    <h2 class="font-display font-bold text-charcoal-950 text-base">Pilih Metode Pembayaran Resmi</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($paymentMethods as $method)
                        <button type="button" @click="selectedPaymentMethod = '{{ $method->value }}'"
                            :class="{
                                'border-charcoal-950 bg-charcoal-950 text-cream-200 shadow-md ring-2 ring-charcoal-950/20': selectedPaymentMethod === '{{ $method->value }}',
                                'border-cream-300 bg-white/80 text-charcoal-900 hover:border-cream-400': selectedPaymentMethod !== '{{ $method->value }}'
                            }"
                            class="p-4 rounded-2xl border text-left transition-smooth flex items-center justify-between">
                            <div>
                                <div class="font-bold text-xs">{{ $method->shortName() }}</div>
                                <div class="text-[10px] opacity-75 mt-0.5">{{ $method->isAutomated() ? 'Verifikasi Instan 24 Jam' : 'Verifikasi Manual' }}</div>
                            </div>
                            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary Sticky Sidebar (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="glass-card p-6 sm:p-7 rounded-3xl border-2 border-cream-300 shadow-xl space-y-6 sticky top-24">
                
                <h3 class="font-display font-bold text-charcoal-950 text-base pb-3 border-b border-cream-200">
                    Ringkasan Belanja ({{ $cartTotals['total_items'] }} item)
                </h3>

                <!-- Cart Items Compact List -->
                <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                    @foreach($cartTotals['cart_items'] as $item)
                        <div class="flex items-center space-x-3 py-2 border-b border-cream-100 last:border-0">
                            <div class="w-12 h-14 rounded-xl bg-cream-100 overflow-hidden shrink-0">
                                <img src="{{ $item->variant->product->thumbnail_url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0 text-xs">
                                <h4 class="font-bold text-charcoal-900 truncate">{{ $item->variant->product->name }}</h4>
                                <p class="text-[11px] text-charcoal-500">{{ $item->variant->name }} &bull; {{ $item->quantity }} pcs</p>
                                <p class="font-mono font-bold text-charcoal-900 mt-0.5">Rp {{ number_format($item->line_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Calculation Lines -->
                <div class="space-y-2.5 pt-3 border-t border-cream-200 text-xs text-charcoal-600">
                    <div class="flex items-center justify-between">
                        <span>Subtotal Produk</span>
                        <span class="font-mono font-bold text-charcoal-900" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span>Ongkos Kirim</span>
                            <span class="text-[10px] text-charcoal-400 block" x-text="selectedCourierCode.toUpperCase() + ' ' + selectedServiceName"></span>
                        </div>
                        <span class="font-mono font-bold text-charcoal-900" x-text="formatRupiah(shippingCost)"></span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="pt-4 border-t border-cream-300 flex items-baseline justify-between">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-charcoal-500 block">Total Tagihan</span>
                        <span class="text-[10px] text-charcoal-400">Sudah termasuk PPN</span>
                    </div>
                    <span class="text-2xl sm:text-3xl font-display font-bold text-charcoal-950 font-mono" x-text="formatRupiah(grandTotal)"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 px-6 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl border border-cream-400/30 transition-smooth hover:border-cream-300 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    <span>Bayar Sekarang &rarr;</span>
                </button>

                <p class="text-center text-[10px] text-charcoal-400 font-light">
                    🔒 Seluruh transaksi dilindungi enkripsi SSL 256-bit dan diawasi oleh Bank Indonesia.
                </p>

            </div>
        </div>

    </form>
</div>
@endsection
