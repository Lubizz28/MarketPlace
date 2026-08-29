<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsAndSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial Hero Banners
        Banner::firstOrCreate(
            ['title' => 'Koleksi Syar\'i Ramadhan & Idul Fitri'],
            [
                'subtitle' => 'Tampil elegan, anggun, dan nyaman dengan material sutra arab pilihan terbaik.',
                'image_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=1200&auto=format&fit=crop',
                'button_text' => 'Lihat Koleksi',
                'button_url' => '/catalog',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 2. Initial Static Pages
        Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang MedinaStyle',
                'content' => 'MedinaStyle adalah destinasi busana muslimah premium dan busana syar\'i terpercaya di Indonesia. Kami menghadirkan koleksi gamis, abaya, khimar, dan hijab elegan dengan komitmen kenyamanan syariat dan estetika modern.',
                'meta_title' => 'Tentang Kami — MedinaStyle Islamic Fashion',
                'meta_description' => 'Mengenal visi dan filosofi MedinaStyle dalam menghadirkan busana muslimah syar\'i berkualitas.',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'kebijakan-privasi'],
            [
                'title' => 'Kebijakan Privasi',
                'content' => 'Kami di MedinaStyle sangat menghormati dan melindungi data pribadi setiap pelanggan. Seluruh data transaksi, alamat pengiriman, dan riwayat pesanan diamankan dengan enkripsi berstandar industri.',
                'meta_title' => 'Kebijakan Privasi — MedinaStyle',
                'meta_description' => 'Kebijakan perlindungan data dan privasi pengguna platform MedinaStyle.',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'syarat-dan-ketentuan'],
            [
                'title' => 'Syarat & Ketentuan',
                'content' => 'Dengan berbelanja atau bergabung sebagai Member/Reseller di MedinaStyle, Anda menyetujui ketentuan pemesanan, kebijakan retur produk, atribusi komisi reseller, dan penggunaan kupon diskon.',
                'meta_title' => 'Syarat & Ketentuan — MedinaStyle',
                'meta_description' => 'Ketentuan transaksi, garansi pengiriman, dan program kemitraan reseller MedinaStyle.',
                'is_active' => true,
            ]
        );

        // 3. Initial Blog Posts
        $admin = User::where('role', 'admin')->first();
        Post::firstOrCreate(
            ['slug' => 'tips-memilih-bahan-abaya-adem-untuk-iklim-tropis'],
            [
                'author_id' => $admin?->id,
                'title' => 'Tips Memilih Bahan Abaya Adem & Nyaman untuk Iklim Tropis',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop',
                'excerpt' => 'Panduan lengkap mengenali karakteristik kain sutra arab, ceruty babydoll, dan katun madinah.',
                'body' => 'Bagi muslimah Indonesia, memilih busana syar\'i yang tidak hanya anggun tetapi juga sejuk sangatlah penting. Kain sutra arab (silk) dan katun madinah menjadi pilihan favorit karena serat kainnya yang bernapas dan tidak mudah kusut.',
                'view_count' => 120,
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // 4. Default Settings
        $defaultSettings = [
            ['key' => 'store_name', 'value' => 'MedinaStyle', 'group' => 'general', 'label' => 'Nama Toko Marketplace', 'type' => 'text'],
            ['key' => 'store_tagline', 'value' => 'Busana Muslimah & Hijab Syar\'i Premium', 'group' => 'general', 'label' => 'Tagline / Slogan', 'type' => 'text'],
            ['key' => 'cs_phone', 'value' => '081299998888', 'group' => 'general', 'label' => 'Nomor WhatsApp Customer Service', 'type' => 'text'],
            ['key' => 'cs_email', 'value' => 'cs@medinastyle.com', 'group' => 'general', 'label' => 'Email Layanan Pelanggan', 'type' => 'text'],
            ['key' => 'store_address', 'value' => 'Jl. Boulevard Hijab No. 88, Tanah Abang, Jakarta Pusat', 'group' => 'general', 'label' => 'Alamat Kantor / Gudang', 'type' => 'textarea'],
            ['key' => 'rajaongkir_origin_city_id', 'value' => '152', 'group' => 'shipping', 'label' => 'ID Kota Asal Pengiriman (Jakarta Pusat)', 'type' => 'number'],
            ['key' => 'min_withdrawal_amount', 'value' => '50000', 'group' => 'affiliate', 'label' => 'Minimal Penarikan Dana Reseller (Rp)', 'type' => 'number'],
            ['key' => 'default_reseller_commission_rate', 'value' => '10', 'group' => 'affiliate', 'label' => 'Persentase Komisi Referral Standar (%)', 'type' => 'number'],
            ['key' => 'points_per_ten_thousand', 'value' => '1', 'group' => 'loyalty', 'label' => 'Poin Didapat per Rp 10.000 Belanja', 'type' => 'number'],
        ];

        foreach ($defaultSettings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }
    }
}
