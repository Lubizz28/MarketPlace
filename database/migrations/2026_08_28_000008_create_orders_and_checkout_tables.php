<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 64)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_type', 32)->default('retail'); // retail, member, reseller
            $table->foreignId('reseller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 32)->default('pending_payment'); // OrderStatus
            $table->string('payment_status', 32)->default('unpaid'); // PaymentStatus

            // Monetary fields (in IDR integer)
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('grand_total')->default(0);

            // Customer Contact (for guest and member)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 32);
            $table->text('notes')->nullable();

            // Lifecycle Timestamps
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('customer_email');
            $table->index('customer_phone');
        });

        // 2. Order Items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name');
            $table->string('sku', 64);
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('weight_grams')->default(0);
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->index(['order_id', 'product_variant_id']);
        });

        // 3. Order Addresses table (Snapshot of shipping destination)
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name');
            $table->string('phone', 32);
            $table->string('province_id', 32)->nullable();
            $table->string('province_name');
            $table->string('city_id', 32)->nullable();
            $table->string('city_name');
            $table->string('subdistrict_id', 32)->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->string('postal_code', 16);
            $table->text('address_line');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Order Shipments table
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('courier_code', 32); // jne, pos, tiki, sicepat, jnt
            $table->string('courier_name', 64);
            $table->string('service_name', 64); // REG, OKE, YES, EZ, etc.
            $table->string('service_description')->nullable();
            $table->string('etd_days', 32)->nullable(); // e.g. "1-2", "2-3"
            $table->unsignedBigInteger('shipping_cost');
            $table->unsignedInteger('weight_grams');
            $table->string('tracking_number', 128)->nullable(); // resi
            $table->string('status', 32)->default('pending'); // pending, manifest, in_transit, delivered
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'tracking_number']);
        });

        // 5. Payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method', 64); // PaymentMethod enum
            $table->string('payment_gateway', 32)->default('midtrans'); // midtrans, mock, manual
            $table->string('transaction_id', 128)->nullable()->unique();
            $table->unsignedBigInteger('amount');
            $table->string('status', 32)->default('unpaid'); // PaymentStatus enum
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->json('payment_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        // 6. Payment Transactions Audit Log table
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('gateway_reference', 128)->nullable();
            $table->string('event_type', 64); // charge, webhook, settlement, expire, cancel
            $table->json('payload_json')->nullable();
            $table->json('response_json')->nullable();
            $table->string('status', 32);
            $table->timestamps();

            $table->index(['payment_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
