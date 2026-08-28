# DEVELOPMENT_RULES.md - Aturan & Standar Pengembangan

## 1. Aturan Fundamental & Stack
1. **Tech Stack**: Laravel 11.x + PHP 8.2+ + MySQL/MariaDB + Blade + Livewire 3 + Alpine.js + Tailwind CSS. Tidak boleh mengubah stack tanpa persetujuan arsitek.
2. **Model Bisnis MVP**: Single-Store (Toko Utama) dengan 3 kelas customer: **Customer/Guest**, **Member**, dan **Reseller**. Dilarang membuat fitur multi-vendor pada MVP.
3. **Preservasi Kode**: Dilarang menghapus atau merusak kode/fitur yang sudah bekerja sebelumnya.
4. **Phase-by-Phase Execution**: Implementasi dilakukan secara bertahap per fase (Phase 0 hingga Phase 10) sesuai acceptance criteria masing-masing.

---

## 2. Standar Penulisan Kode & Desain Arsitektur
1. **No Giant Controllers / Livewire Components**:
   - Controller & Livewire Component harus tetap ramping (*thin*).
   - Logika bisnis yang kompleks wajib diekstrak ke dalam **Action Class** (misal: `CreateOrderAction`, `ProcessWithdrawalAction`) atau **Domain Service** (`LedgerService`, `ShippingCalculator`).
2. **Strict Typing & Enums**:
   - Gunakan PHP 8.2 typed properties, return types, dan PHP Native Enums untuk semua status (misal: `OrderStatus`, `PaymentStatus`, `CommissionStatus`, `UserRole`).
3. **Validasi & Integritas Server-Side**:
   - Gunakan Form Request khusus untuk setiap payload request.
   - **Harga, diskon voucher, persentase komisi, biaya ongkir, stok, dan hak akses TIDAK PERNAH dipercayai dari input klien**. Semua wajib diverifikasi dan dihitung ulang di server.
4. **Financial & Ledger Integrity**:
   - Nilai uang wajib bertipe `decimal(12, 2)` (tidak boleh float).
   - Jumlah stok inventori wajib bertipe `integer` / `unsignedInteger`.
   - Mutasi stok dan saldo dompet reseller wajib dicatat dalam transaksi ledger audit (`inventory_movements`, `wallet_transactions`) dan tidak boleh langsung memodifikasi balance tanpa rekam jejak.

---

## 3. Standar Database & Query Performance
1. **Pencegahan N+1 Query**:
   - Selalu gunakan Eager Loading (`with(['variants', 'images', 'prices'])`) pada query relasional.
   - Aktifkan `Model::preventLazyLoading(!app()->isProduction())` di development.
2. **Indexing Strategy**:
   - Tambahkan index pada kolom yang sering dicari atau difilter: `slug`, `sku`, `status`, `user_id`, `reseller_id`, `created_at`, serta semua Foreign Key.
3. **Database Constraints**:
   - Terapkan Foreign Key cascading dan Unique Constraints yang tepat (misal: unique `sku`, unique `slug`).

---

## 4. Standar UI/UX & Frontend
1. **Mobile-First Design**:
   - Prioritas tampilan awal pada ukuran mobile (360px, 390px, 430px) kemudian responsif ke tablet dan desktop.
2. **State Handling Wajib**:
   - Setiap komponen interaktif harus memiliki visualisasi state lengkap: **Loading State**, **Empty State**, **Error State**, dan **Success State**.
3. **Design Tokens & Theme Consistency**:
   - Gunakan konfigurasi Tailwind CSS terpusat (`tailwind.config.js`) untuk palet warna, tipografi, radius, dan spacing. Hindari penggunaan arbitrary hardcoded colors di setiap komponen.
4. **Aset & Gambar**:
   - Gambar produk harus dioptimalkan (WebP/AVIF format), lazy-loaded, dan memiliki thumbnail khusus untuk katalog produk.

---

## 5. Standar Keamanan & Authorization
1. **Authorization & Policies**:
   - Setiap aksi modifikasi data (Edit Profil, Lihat Pesanan, Tarik Komisi, Admin CRUD) wajib dilindungi oleh Laravel Policy / Gates.
   - Reseller hanya boleh melihat data referral dan komisinya sendiri (mencegah IDOR).
2. **Keamanan Transaksi**:
   - Webhook callback dari Payment Gateway wajib diverifikasi tandatangan/token keamanannya dan bersifat idempotent.
   - Gunakan Database Transaction atomik (`DB::transaction`) untuk alur checkout, perubahan stok, dan pencairan komisi.

---

## 6. Standar QA, Testing & Output Phase
1. **Automated Testing**:
   - Setiap fitur kritis wajib memiliki Feature/Unit Test (Pendaftaran, Kalkulasi Harga/Diskon, Checkout, Lifecycle Komisi, RBAC).
2. **Checklist Selesai per Phase**:
   - Semua acceptance criteria terpenuhi.
   - Tidak ada syntax error, broken route, migrasi gagal, atau query N+1.
   - Laporan format standar disajikan sebelum melangkah ke fase berikutnya.
