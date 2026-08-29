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
        // 1. CMS Banners Table
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('subtitle', 255)->nullable();
            $table->string('image_path');
            $table->string('button_text', 50)->nullable();
            $table->string('button_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. CMS Static Pages Table (Tentang Kami, Kebijakan Privasi, Syarat & Ketentuan, FAQ)
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug', 180)->unique();
            $table->longText('content');
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. CMS Blog / Islamic Fashion Articles Table
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('thumbnail_path')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 4. Platform Key-Value Settings Table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general'); // general, payment, shipping, loyalty
            $table->string('label', 150)->nullable();
            $table->string('type', 30)->default('text'); // text, textarea, number, boolean
            $table->timestamps();
        });

        // 5. Audit Activity Logs Table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 100); // login, order_status_updated, product_created, etc.
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('banners');
    }
};
