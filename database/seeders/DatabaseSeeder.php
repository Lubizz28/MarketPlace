<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Address;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full administrative access to the platform',
        ]);

        $memberRole = Role::create([
            'name' => 'Member Customer',
            'slug' => 'member',
            'description' => 'Standard shopping member with loyalty benefits',
        ]);

        $resellerRole = Role::create([
            'name' => 'Partner Reseller',
            'slug' => 'reseller',
            'description' => 'Business partner with wholesale pricing and referral commission',
        ]);

        // 2. Seed Permissions
        $permissions = [
            ['name' => 'Kelola Produk', 'slug' => 'manage-products', 'module' => 'catalog'],
            ['name' => 'Kelola Pesanan', 'slug' => 'manage-orders', 'module' => 'orders'],
            ['name' => 'Kelola Pengguna', 'slug' => 'manage-users', 'module' => 'users'],
            ['name' => 'Kelola Komisi Reseller', 'slug' => 'manage-commissions', 'module' => 'reseller'],
            ['name' => 'Tarik Komisi', 'slug' => 'withdraw-commission', 'module' => 'wallet'],
        ];

        foreach ($permissions as $p) {
            $perm = Permission::create($p);
            $adminRole->permissions()->attach($perm);
            if ($p['slug'] === 'withdraw-commission') {
                $resellerRole->permissions()->attach($perm);
            }
        }

        // 3. Seed Default Administrator
        $admin = User::create([
            'name' => 'Admin Medina',
            'email' => 'admin@medinastyle.com',
            'phone' => '081100000001',
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'password' => Hash::make('Password123'),
        ]);
        $admin->roles()->attach($adminRole);
        Profile::create([
            'user_id' => $admin->id,
            'bio' => 'Head Administrator of MedinaStyle.',
            'gender' => Gender::MALE,
        ]);

        // 4. Seed Default Member User
        $member = User::create([
            'name' => 'Aisyah Putri',
            'email' => 'member@medinastyle.com',
            'phone' => '081200000002',
            'role' => UserRole::MEMBER,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'password' => Hash::make('Password123'),
        ]);
        $member->roles()->attach($memberRole);
        Profile::create([
            'user_id' => $member->id,
            'bio' => 'Pencinta busana muslimah syar\'i dan gamis modern.',
            'gender' => Gender::FEMALE,
            'birthdate' => '1998-05-15',
        ]);
        Address::create([
            'user_id' => $member->id,
            'label' => 'Rumah Utama',
            'recipient_name' => 'Aisyah Putri',
            'phone' => '081200000002',
            'address_line' => 'Jl. Anggrek No. 12 RT 03/RW 05, Kel. Merdeka',
            'province_id' => '32',
            'province_name' => 'Jawa Barat',
            'city_id' => '3273',
            'city_name' => 'Kota Bandung',
            'district_id' => '327301',
            'district_name' => 'Coblong',
            'postal_code' => '40132',
            'is_primary' => true,
        ]);

        // 5. Seed Default Reseller User
        $reseller = User::create([
            'name' => 'Khadijah Hijab Store',
            'email' => 'reseller@medinastyle.com',
            'phone' => '081300000003',
            'role' => UserRole::RESELLER,
            'status' => UserStatus::ACTIVE,
            'email_verified_at' => now(),
            'password' => Hash::make('Password123'),
        ]);
        $reseller->roles()->attach($resellerRole);
        Profile::create([
            'user_id' => $reseller->id,
            'bio' => 'Mitra reseller busana muslimah Bandung & Jabodetabek.',
            'gender' => Gender::FEMALE,
            'birthdate' => '1995-10-20',
        ]);
        Address::create([
            'user_id' => $reseller->id,
            'label' => 'Toko / Gudang',
            'recipient_name' => 'Khadijah Hijab Store',
            'phone' => '081300000003',
            'address_line' => 'Ruko Sentra Niaga Blok B-5, Jl. Soekarno Hatta',
            'province_id' => '32',
            'province_name' => 'Jawa Barat',
            'city_id' => '3273',
            'city_name' => 'Kota Bandung',
            'district_id' => '327305',
            'district_name' => 'Batununggal',
            'postal_code' => '40266',
            'is_primary' => true,
        ]);
        \App\Models\ResellerProfile::create([
            'user_id' => $reseller->id,
            'store_name' => 'Khadijah Hijab Store',
            'referral_code' => 'KHADIJAH',
            'bank_name' => 'BCA',
            'bank_account_number' => '8830123456',
            'bank_account_name' => 'Khadijah Hijab Store',
            'kyc_status' => \App\Enums\KycStatus::VERIFIED,
            'commission_rate_percent' => 10,
            'approved_at' => now(),
        ]);
        \App\Models\ResellerWallet::create([
            'user_id' => $reseller->id,
            'balance' => 250000,
            'pending_balance' => 50000,
            'total_withdrawn' => 100000,
        ]);

        // 6. Seed Product Catalog, Categories, Brands, Variants, Multi-Tier Prices, Inventory, Promos, CMS, and Settings
        $this->call([
            ProductCatalogSeeder::class,
            CouponSeeder::class,
            CmsAndSettingsSeeder::class,
        ]);
    }
}
