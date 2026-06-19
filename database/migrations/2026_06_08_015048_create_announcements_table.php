<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('attachment')->nullable();
            $table->enum('type', ['general', 'academic', 'urgent', 'event'])->default('general');
            $table->enum('target_type', ['all', 'teachers', 'students', 'parents', 'specific'])->default('all');
            $table->json('target_ids')->nullable();     // Specific user/classroom IDs
            $table->boolean('send_notification')->default(true);
            $table->boolean('send_email')->default(false);
            $table->datetime('publish_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};