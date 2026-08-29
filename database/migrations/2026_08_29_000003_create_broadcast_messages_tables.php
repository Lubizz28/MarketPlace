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
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 200);
            $table->text('message');
            $table->string('target_role', 32)->default('all'); // all, member, reseller
            $table->string('channel', 32)->default('both'); // email, whatsapp, both
            $table->unsignedInteger('total_recipients')->default(0);
            $table->string('status', 32)->default('sent'); // draft, sent
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('broadcast_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained('broadcast_messages')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_name', 150);
            $table->string('recipient_target', 150); // email or phone
            $table->string('channel', 32); // email or whatsapp
            $table->string('status', 32)->default('sent'); // sent, failed
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['broadcast_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_logs');
        Schema::dropIfExists('broadcast_messages');
    }
};
