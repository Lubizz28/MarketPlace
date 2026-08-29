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
        // 1. Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('type', 32)->default('fixed'); // fixed, percent (CouponType)
            $table->unsignedBigInteger('amount'); // Nominal in IDR or Percentage e.g. 15 for 15%
            $table->unsignedBigInteger('min_order_amount')->default(0);
            $table->unsignedBigInteger('max_discount')->nullable(); // Cap for percent discounts
            $table->unsignedInteger('max_uses')->nullable(); // Global max redemptions
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('per_user_limit')->default(1);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['is_active', 'expires_at']);
        });

        // 2. Add loyalty points to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('loyalty_points')->default(0)->after('status');
        });

        // 3. Add coupon and points fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('reseller_id')->constrained('coupons')->nullOnDelete();
            $table->string('coupon_code', 64)->nullable()->after('coupon_id');
            $table->unsignedBigInteger('coupon_discount')->default(0)->after('subtotal');
            $table->unsignedInteger('points_redeemed')->default(0)->after('coupon_discount');
            $table->unsignedBigInteger('points_discount')->default(0)->after('points_redeemed');
        });

        // 4. Coupon Usages Table
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('discount_applied');
            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
            $table->index('order_id');
        });

        // 5. Point Transactions Ledger Table
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 32); // earned, redeemed, refunded, adjusted, expired (PointTransactionType)
            $table->integer('points'); // positive for earn/refund, negative for redemption
            $table->unsignedBigInteger('balance_after');
            $table->string('description');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('coupon_usages');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'coupon_id',
                'coupon_code',
                'coupon_discount',
                'points_redeemed',
                'points_discount'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('loyalty_points');
        });

        Schema::dropIfExists('coupons');
    }
};
