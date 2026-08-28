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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name', 200)->index();
            $table->string('slug', 220)->unique();
            $table->string('sku', 100)->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->integer('weight_grams')->default(500);
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedBigInteger('view_count')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->boolean('is_primary')->default(false)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('name', 150); // e.g. "Hitam Jetblack - L"
            $table->string('color_name', 80)->nullable()->index();
            $table->string('color_hex', 30)->nullable();
            $table->string('size', 50)->nullable()->index();
            $table->integer('stock')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->string('customer_type', 30)->index(); // 'retail', 'member', 'reseller'
            $table->decimal('price', 15, 2)->index();
            $table->integer('min_quantity')->default(1);
            $table->timestamps();

            $table->unique(['product_variant_id', 'customer_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
