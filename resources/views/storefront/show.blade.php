@extends('layouts.app')

@section('title', $product->name . ' — Koleksi Eksklusif')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 pt-4"
    x-data="{
        selectedVariant: {{ $product->variants->first()?->id ?? 'null' }},
        selectedVariantName: '{{ $product->variants->first()?->name ?? '' }}',
        selectedStock: {{ $product->variants->first()?->stock ?? 0 }},
        retailPrice: {{ $product->variants->first()?->getPriceFor('retail') ?? 0 }},
        memberPrice: {{ $product->variants->first()?->getPriceFor('member') ?? 0 }},
        resellerPrice: {{ $product->variants->first()?->getPriceFor('reseller') ?? 0 }},
        activeImage: '{{ $product->thumbnail_url }}',
        quantity: 1,
        activeTab: 'desc',
        variants: @js($product->variants->map(fn ($v) => [
            'id' => $v->id,
            'name' => $v->name,
            'color' => $v->color_name,
            'size' => $v->size,
            'stock' => $v->stock,
            'retail_price' => $v->getPriceFor('retail'),
            'member_price' => $v->getPriceFor('member'),
            'reseller_price' => $v->getPriceFor('reseller'),
        ])),
        selectVariant(v) {
            this.selectedVariant = v.id;
            this.selectedVariantName = v.name;
            this.selectedStock = v.stock;
            this.retailPrice = v.retail_price;
            this.memberPrice = v.member_price;
            this.resellerPrice = v.reseller_price;
            if (this.quantity > v.stock) {
                this.quantity = Math.max(1, v.stock);
            }
        },
        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        }
    }">

    <!-- Product Breadcrumbs -->
    <nav class="flex items-center space-x-2 text-xs text-charcoal-500 font-light">
        <a href="{{ route('home') }}" class="hover:text-charcoal-900">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-charcoal-900">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-charcoal-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
        
        <!-- Left: Image Gallery (5 Cols) -->
        <div class="lg:col-span-6 space-y-4">
            <div class="glass-card p-3 rounded-3xl overflow-hidden aspect-3/4 relative bg-cream-100/60 shadow-xl">
                <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-2xl transition-smooth">
                <span class="absolute top-6 left-6 px-3.5 py-1 bg-charcoal-950/80 backdrop-blur-md text-cream-200 text-[10px] font-bold uppercase tracking-wider rounded-full border border-cream-400/30">
                    {{ $product->category->name }}
                </span>
            </div>

            <!-- Thumbnail Selector Carousel -->
            @if($product->images->count() > 1)
                <div class="flex items-center space-x-3 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button type="button" @click="activeImage = '{{ $img->image_path }}'"
                            :class="activeImage === '{{ $img->image_path }}' ? 'border-charcoal-950 ring-2 ring-charcoal-950/20' : 'border-cream-300 opacity-70 hover:opacity-100'"
                            class="w-16 h-20 rounded-xl overflow-hidden border-2 transition-smooth shrink-0 bg-cream-100">
                            <img src="{{ $img->image_path }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Product Details & Variant Purchasing Box (6 Cols) -->
        <div class="lg:col-span-6 space-y-7">
            <div>
                @if($product->brand)
                    <span class="text-xs uppercase tracking-[0.2em] font-bold text-cream-700 block mb-1">{{ $product->brand->name }}</span>
                @endif
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-display font-bold text-charcoal-950 leading-tight">
                    {{ $product->name }}
                </h1>
                <p class="text-xs text-charcoal-500 mt-2 font-mono">SKU: {{ $product->sku }}</p>
            </div>

            <!-- Multi-Tier Pricing Display Card in Frosted Glass -->
            <div class="glass-card p-6 rounded-3xl space-y-3 border-2 border-cream-300 shadow-md">
                <div class="flex items-baseline space-x-3">
                    @auth
                        @if(auth()->user()->isReseller())
                            <span class="text-3xl font-display font-bold text-charcoal-950 font-mono" x-text="formatRupiah(resellerPrice)"></span>
                            <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 bg-emerald-100 text-emerald-950 rounded-full">Harga Grosir Reseller</span>
                            <span class="text-xs text-charcoal-400 line-through font-mono" x-text="formatRupiah(retailPrice)"></span>
                        @elseif(auth()->user()->isMember())
                            <span class="text-3xl font-display font-bold text-charcoal-950 font-mono" x-text="formatRupiah(memberPrice)"></span>
                            <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 bg-cream-200 text-charcoal-900 rounded-full">Harga Spesial Member</span>
                            <span class="text-xs text-charcoal-400 line-through font-mono" x-text="formatRupiah(retailPrice)"></span>
                        @else
                            <span class="text-3xl font-display font-bold text-charcoal-950 font-mono" x-text="formatRupiah(retailPrice)"></span>
                        @endif
                    @else
                        <span class="text-3xl font-display font-bold text-charcoal-950 font-mono" x-text="formatRupiah(retailPrice)"></span>
                    @endauth
                </div>

                <!-- Privilege Incentive Prompt for Guests -->
                @guest
                    <div class="pt-3 border-t border-cream-200/80 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-charcoal-600 gap-2">
                        <div class="flex items-center space-x-2">
                            <span class="text-cream-800 font-bold">✦ Hemat Belanja:</span>
                            <span>Dapatkan harga <b class="font-mono" x-text="formatRupiah(memberPrice)"></b> dengan gabung Member!</span>
                        </div>
                        <a href="{{ route('register') }}" class="font-bold text-charcoal-950 underline shrink-0">Daftar &rarr;</a>
                    </div>
                @endguest
            </div>

            <!-- Variant Selector -->
            @if($product->variants->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-charcoal-800">
                            Pilih Varian: <span class="font-normal normal-case text-charcoal-600" x-text="selectedVariantName"></span>
                        </label>
                        <span class="text-xs font-medium" :class="selectedStock > 0 ? 'text-emerald-800 font-semibold' : 'text-rose-600 font-bold'">
                            <span x-text="selectedStock > 0 ? 'Tersedia: ' + selectedStock + ' pcs' : 'Stok Habis'"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <template x-for="v in variants" :key="v.id">
                            <button type="button" @click="selectVariant(v)"
                                :disabled="v.stock <= 0"
                                :class="{
                                    'bg-charcoal-950 text-cream-200 border-charcoal-950 shadow-md': selectedVariant === v.id,
                                    'bg-white/80 border-cream-300 text-charcoal-800 hover:border-cream-400': selectedVariant !== v.id && v.stock > 0,
                                    'bg-cream-100/40 border-cream-200 text-charcoal-300 cursor-not-allowed line-through': v.stock <= 0
                                }"
                                class="p-3 rounded-2xl border text-left transition-smooth flex flex-col justify-between space-y-1">
                                <span class="font-semibold text-xs truncate" x-text="v.name"></span>
                                <span class="text-[11px] font-mono opacity-80" x-text="formatRupiah(v.retail_price)"></span>
                            </button>
                        </template>
                    </div>
                </div>
            @endif

            <!-- Quantity & Actions -->
            <form method="POST" action="{{ route('cart.add') }}" class="space-y-4 pt-2">
                @csrf
                <input type="hidden" name="variant_id" :value="selectedVariant">
                <input type="hidden" name="quantity" :value="quantity">

                <div class="flex items-center space-x-4">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center space-x-3 bg-white/90 border border-cream-300 rounded-2xl p-1 shadow-xs">
                        <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="w-9 h-9 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-cream-100 font-bold text-sm transition-smooth">-</button>
                        <span class="w-8 text-center text-sm font-bold font-mono text-charcoal-950" x-text="quantity"></span>
                        <button type="button" @click="quantity = Math.min(selectedStock, quantity + 1)" :disabled="quantity >= selectedStock" class="w-9 h-9 rounded-xl flex items-center justify-center text-charcoal-700 hover:bg-cream-100 font-bold text-sm transition-smooth disabled:opacity-30">+</button>
                    </div>

                    <!-- Add to Cart CTA Button -->
                    <button type="submit" :disabled="selectedStock <= 0"
                        class="flex-1 py-4 px-8 bg-charcoal-950 hover:bg-charcoal-900 text-cream-200 font-bold rounded-2xl text-xs uppercase tracking-widest shadow-xl border border-cream-400/30 transition-smooth hover:border-cream-300 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center space-x-2.5">
                        <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        <span x-text="selectedStock > 0 ? '+ Tambah ke Keranjang' : 'Stok Habis'"></span>
                    </button>
                </div>
            </form>

            <!-- Product Details Tabs -->
            <div class="pt-6 border-t border-cream-200/90 space-y-4">
                <div class="flex items-center space-x-6 border-b border-cream-200 pb-2 text-xs font-bold uppercase tracking-wider">
                    <button type="button" @click="activeTab = 'desc'" :class="activeTab === 'desc' ? 'text-charcoal-950 border-b-2 border-charcoal-950 pb-2' : 'text-charcoal-400 hover:text-charcoal-700'">Deskripsi</button>
                    <button type="button" @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'text-charcoal-950 border-b-2 border-charcoal-950 pb-2' : 'text-charcoal-400 hover:text-charcoal-700'">Garansi &amp; Pengiriman</button>
                </div>

                <div x-show="activeTab === 'desc'" class="text-xs text-charcoal-600 leading-relaxed font-light space-y-3">
                    <p>{{ $product->description ?? $product->short_description }}</p>
                    <div class="pt-2 grid grid-cols-2 gap-2 text-[11px] text-charcoal-700 font-medium">
                        <div>&bull; Berat: {{ $product->weight_grams }} gram</div>
                        <div>&bull; Kategori: {{ $product->category->name }}</div>
                    </div>
                </div>

                <div x-show="activeTab === 'shipping'" x-cloak class="text-xs text-charcoal-600 leading-relaxed font-light space-y-2">
                    <p>&bull; <b>Garansi Keaslian:</b> Produk 100% original berstandar butik syar'i.</p>
                    <p>&bull; <b>Pengiriman:</b> Dukungan ekspedisi kilat terpercaya ke seluruh pelosok Nusantara.</p>
                    <p>&bull; <b>Retur 7 Hari:</b> Garansi penukaran ukuran jika terjadi ketidaksesuaian size chart.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="pt-12 border-t border-cream-200/90 space-y-6">
            <h2 class="text-xl sm:text-2xl font-display font-bold text-charcoal-950">Koleksi Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="glass-card p-4 rounded-3xl space-y-3 hover:border-cream-400 transition-smooth group">
                        <div class="aspect-3/4 rounded-2xl overflow-hidden bg-cream-100">
                            <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-smooth">
                        </div>
                        <h4 class="font-display font-bold text-xs text-charcoal-950 truncate">{{ $rel->name }}</h4>
                        <p class="text-xs font-mono font-bold text-charcoal-900">Rp {{ number_format($rel->getMinPriceFor('retail'), 0, ',', '.') }}</p>
                        <a href="{{ route('product.show', $rel->slug) }}" class="block py-2 text-center bg-cream-200/80 hover:bg-cream-300 text-charcoal-900 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-smooth">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
