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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Rumah'); // Rumah, Kantor, Toko
            $table->string('recipient_name');
            $table->string('phone', 30);
            $table->text('address_line');
            $table->string('province_id', 50)->nullable();
            $table->string('province_name');
            $table->string('city_id', 50)->nullable();
            $table->string('city_name');
            $table->string('district_id', 50)->nullable();
            $table->string('district_name')->nullable();
            $table->string('postal_code', 20);
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
