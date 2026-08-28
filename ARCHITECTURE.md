# ARCHITECTURE.md - Marketplace Pakaian Muslim & Fashion

## 1. Executive Overview & Business Model
Sistem ini adalah platform e-commerce monolitik terstruktur berbasis **Laravel** untuk brand pakaian muslim dan fashion.
- **Model Toko**: Single-store / First-party store (bukan multi-vendor di MVP).
- **Tipe Pengguna (Actors)**:
  1. **Guest / Customer**: Pengunjung publik tanpa akun atau pembeli retail biasa.
  2. **Member**: Pelanggan terdaftar yang menikmati program loyalitas (poin reward, voucher eksklusif, tier diskon khusus).
  3. **Reseller**: Mitra bisnis terverifikasi yang mendapatkan harga grosir/reseller, tautan referral, sistem komisi bertingkat, dompet saldo (*wallet*), dan fasilitas *withdrawal*.
  4. **Admin**: Pengelola sistem (katalog, pesanan, inventori, persetujuan reseller/withdrawal, promosi, CMS, audit log).

---

## 2. Technology Stack & Design Principles
- **Backend Framework**: PHP 8.2+ / Laravel 11.x
- **Database**: MySQL 8.0+ / MariaDB 10.11+
- **Frontend / View Layer**: Blade Templates + Livewire 3 (untuk reactive stateful components) + Alpine.js (untuk micro-interactions & UI bindings) + Tailwind CSS (styling utilitas modern & mobile-first).
- **Architecture Paradigm**: Server-Side Rendered (SSR) by default, Clean Modular Monolith dengan pola **Action / Service / Enum / Policy / Form Request**.
- **Asset Pipeline**: Vite + Tailwind CSS + Alpine.js.
- **Queue & Async Worker**: Redis / Database Queue untuk notifikasi email, webhook event processing, dan image optimization.

---

## 3. Directory Structure & Domain Architecture
```text
app/
├── Actions/                  # Single Responsibility business actions (e.g. ProcessOrderAction, AllocateCommissionAction)
│   ├── Auth/
│   ├── Order/
│   ├── Reseller/
│   ├── Inventory/
│   └── Payment/
├── Enums/                    # Strict PHP Enums (OrderStatus, PaymentStatus, CommissionStatus, InventoryMovementType, UserRole)
│   ├── OrderStatus.php
│   ├── PaymentStatus.php
│   ├── CommissionStatus.php
│   └── MovementType.php
├── Http/
│   ├── Controllers/          # Thin controllers delegating to Actions/Services
│   │   ├── Admin/
│   │   ├── Member/
│   │   ├── Reseller/
│   │   └── Storefront/
│   ├── Livewire/             # Stateful components (Cart, Dynamic Filter, Live Search, Variant Picker)
│   ├── Middleware/           # Route guards, Role checks, Security headers
│   └── Requests/             # Form Requests & Server-side Validation
├── Models/                   # Eloquent Models with strict typing, relationships, and scopes
├── Policies/                 # Authorization policies (OrderPolicy, WithdrawalPolicy, etc.)
├── Services/                 # Complex orchestration (ShippingService, PaymentGatewayService, LedgerService)
│   ├── Cart/
│   ├── Commission/
│   ├── Inventory/
│   ├── Payment/
│   └── Shipping/
└── View/
    └── Components/           # Reusable Blade UI components (Buttons, Inputs, Badges, Modals)
```

---

## 4. Core Database Schema & Relationships

### A. Auth & RBAC Domain
- `users` (id, name, email, phone, password, role [enum: admin, member, reseller], status, email_verified_at, timestamps)
- `roles` & `permissions` & `role_user` & `permission_role` (fleksibilitas izin granular untuk staff admin)
- `profiles` (user_id, avatar, bio, gender, birthdate, social_links)
- `addresses` (user_id, recipient_name, phone, address_line, province_id, city_id, subdistrict_id, postal_code, is_primary)

### B. Catalog & Inventory Domain
- `categories` (id, parent_id, name, slug, image, is_active, order)
- `brands` (id, name, slug, logo, is_active)
- `products` (id, category_id, brand_id, name, slug, sku, description, short_description, is_active, has_variants, timestamps)
- `product_variants` (id, product_id, sku, variant_name, attributes_json, weight_grams, is_active)
- `product_images` (id, product_id, variant_id, image_path, is_primary, sort_order)
- `product_prices` (id, product_variant_id, customer_type [guest, member, reseller], price [decimal(12,2)], min_qty, timestamps)
- `inventories` (id, product_variant_id, current_stock, reserved_stock, timestamps)
- `inventory_movements` (id, product_variant_id, movement_type [opening, restock, sale, return, adjustment], quantity, reference_type, reference_id, note, user_id, timestamps)

### C. Cart, Order & Checkout Domain
- `carts` (id, user_id, session_id, timestamps)
- `cart_items` (id, cart_id, product_variant_id, quantity, custom_note)
- `orders` (id, order_number, user_id, customer_type, reseller_id, status [OrderStatus], subtotal, discount_amount, shipping_cost, grand_total, notes, timestamps)
- `order_items` (id, order_id, product_variant_id, product_name, variant_name, price, quantity, total_price)
- `order_addresses` (id, order_id, type [shipping, billing], recipient_name, phone, full_address, city, postal_code)
- `order_shipments` (id, order_id, courier_code, service_name, tracking_number, shipping_cost, status, shipped_at, delivered_at)
- `payments` (id, order_id, payment_method, payment_gateway, transaction_id, amount, status [PaymentStatus], expired_at, paid_at)
- `payment_transactions` (id, payment_id, payload_json, response_json, status, gateway_reference)

### D. Reseller & Commission Domain
- `reseller_profiles` (id, user_id, store_name, bank_name, bank_account_number, bank_account_name, kyc_status, id_card_image, approved_at, timestamps)
- `reseller_links` (id, reseller_id, code, target_url, total_clicks, timestamps)
- `reseller_commissions` (id, reseller_id, order_id, commission_amount, status [enum: PENDING, AVAILABLE, PAID, CANCELLED], mature_at, timestamps)
- `reseller_wallets` (id, reseller_id, balance [decimal(12,2)], reserved_balance, timestamps)
- `reseller_withdrawals` (id, reseller_id, amount, bank_info, status [enum: PENDING, APPROVED, REJECTED, PAID], processed_by, processed_at, notes, timestamps)

### E. Loyalty, Marketing & CMS Domain
- `coupons` (id, code, type [fixed, percent], amount, min_order_amount, max_discount, max_uses, per_user_limit, start_at, expires_at, is_active)
- `coupon_usages` (id, coupon_id, user_id, order_id, discount_applied, timestamps)
- `points` & `point_transactions` (user_id, order_id, points_in, points_out, balance_after, description)
- `reviews` & `review_images` (order_item_id, user_id, rating [1-5], comment, is_approved)
- `banners`, `pages`, `blogs`, `settings`, `activity_logs`

---

## 5. Financial & State Machine Lifecycles

### Order State Machine
```text
[Created] -> UNPAID -> (Payment Confirmed) -> PROCESSING -> (Dispatched) -> SHIPPED -> (Delivered) -> COMPLETED
              |                                     |
         (Cancelled)                            (Refunded)
```

### Commission Lifecycle
```text
[Order Placed via Referral] -> PENDING -> (Order COMPLETED) -> AVAILABLE -> (Withdrawal Approved) -> PAID
                                   |
                             (Order Cancel/Refund) -> CANCELLED
```

---

## 6. Security & Performance Baseline
1. **Server-Side Integrity**: Tidak mempercayai input harga/ongkir/komisi dari frontend. Seluruh angka dihitung ulang dari database pada saat checkout.
2. **Double-Entry Ledger Principle**: Saldo reseller wallet dan inventory stock tidak boleh dimutasi tanpa record `inventory_movements` atau `wallet_ledger`.
3. **Optimasi Query**: Wajib Eager Loading (`with()`), index pada `slug`, `sku`, `status`, `created_at`, dan foreign keys.
4. **Idempotency**: Webhook payment handler wajib idempotent menggunakan transaksi database atomik (`DB::transaction`) dan locking row (`lockForUpdate`).
