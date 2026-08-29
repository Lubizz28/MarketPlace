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
        // 1. Reseller Profiles
        Schema::create('reseller_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('store_name')->nullable();
            $table->string('referral_code', 64)->unique();
            $table->string('bank_name', 64)->nullable(); // BCA, Mandiri, BNI, BRI, BSI
            $table->string('bank_account_number', 64)->nullable();
            $table->string('bank_account_name', 150)->nullable();
            $table->string('kyc_status', 32)->default('pending'); // unsubmitted, pending, verified, rejected
            $table->string('id_card_image')->nullable();
            $table->unsignedInteger('commission_rate_percent')->default(10); // default 10% commission
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('referral_code');
            $table->index('kyc_status');
        });

        // 2. Reseller Wallets
        Schema::create('reseller_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0); // Available for withdrawal
            $table->unsignedBigInteger('pending_balance')->default(0); // Uncompleted orders
            $table->unsignedBigInteger('total_withdrawn')->default(0); // Lifetime paid out
            $table->timestamps();

            $table->unique('user_id');
        });

        // 3. Reseller Commissions
        Schema::create('reseller_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedInteger('commission_percent')->default(10);
            $table->unsignedBigInteger('commission_amount')->default(0);
            $table->string('status', 32)->default('pending'); // pending, available, paid, cancelled (CommissionStatus)
            $table->timestamp('mature_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['reseller_id', 'status']);
            $table->index('order_id');
        });

        // 4. Reseller Wallet Ledger Transactions
        Schema::create('reseller_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('reseller_wallets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32); // commission_earned, withdrawal_hold, withdrawal_paid, withdrawal_refund, adjustment
            $table->bigInteger('amount'); // positive for credits, negative for debits
            $table->unsignedBigInteger('balance_after');
            $table->string('reference_type', 64)->nullable(); // order, withdrawal
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });

        // 5. Reseller Withdrawals
        Schema::create('reseller_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('withdrawal_number', 64)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('bank_name', 64);
            $table->string('bank_account_number', 64);
            $table->string('bank_account_name', 150);
            $table->string('status', 32)->default('pending'); // pending, approved, paid, rejected (WithdrawalStatus)
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_withdrawals');
        Schema::dropIfExists('reseller_wallet_transactions');
        Schema::dropIfExists('reseller_commissions');
        Schema::dropIfExists('reseller_wallets');
        Schema::dropIfExists('reseller_profiles');
    }
};
