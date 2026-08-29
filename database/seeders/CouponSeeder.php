<?php

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Diskon Sambutan Pengguna Baru 10%',
                'description' => 'Potongan diskon 10% untuk pembelanjaan busana muslim pertama Anda.',
                'type' => CouponType::PERCENT,
                'amount' => 10,
                'min_order_amount' => 150000,
                'max_discount' => 50000,
                'max_uses' => 1000,
                'used_count' => 0,
                'per_user_limit' => 1,
                'start_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'BERKAH50K',
                'name' => 'Voucher Berkah Belanja Rp 50.000',
                'description' => 'Potongan langsung Rp 50.000 untuk transaksi koleksi Gamis & Abaya Eksklusif.',
                'type' => CouponType::FIXED,
                'amount' => 50000,
                'min_order_amount' => 300000,
                'max_discount' => null,
                'max_uses' => 500,
                'used_count' => 0,
                'per_user_limit' => 2,
                'start_at' => now()->subDays(1),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'MEMBERVIP',
                'name' => 'Diskon Spesial Member Setia 15%',
                'description' => 'Voucher diskon 15% tanpa minimum belanja khusus member terdaftar.',
                'type' => CouponType::PERCENT,
                'amount' => 15,
                'min_order_amount' => 0,
                'max_discount' => 100000,
                'max_uses' => 250,
                'used_count' => 0,
                'per_user_limit' => 1,
                'start_at' => now()->subDays(2),
                'expires_at' => now()->addMonths(12),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
