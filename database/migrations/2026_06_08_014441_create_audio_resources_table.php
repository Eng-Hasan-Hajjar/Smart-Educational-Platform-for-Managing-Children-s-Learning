<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_content_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['phonics', 'pronunciation', 'story', 'song', 'explanation', 'exercise'])->default('explanation');
            $table->string('language')->default('ar');
            $table->string('file_path')->nullable();    // uploaded file
            $table->string('file_url')->nullable();     // external URL (shows embedded player)
            $table->enum('source_type', ['upload', 'url', 'soundcloud', 'drive'])->default('upload');
            $table->integer('duration_seconds')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('transcript')->nullable();   // Text transcript for accessibility
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_transcript')->default(false);
            $table->timestamps();

            $table->index(['lesson_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audio_resources');
    }
};