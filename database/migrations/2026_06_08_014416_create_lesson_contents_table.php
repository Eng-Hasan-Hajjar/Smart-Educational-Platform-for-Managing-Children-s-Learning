<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

    
        Schema::create('lesson_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'video', 'audio', 'image', 'document', 'interactive']);
            $table->string('title')->nullable();
            $table->longText('body')->nullable();       // For text type: rich HTML content
            $table->string('file_path')->nullable();    // For uploaded files
            $table->string('file_url')->nullable();     // For external URLs (YouTube, SoundCloud, Drive...)
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('duration_seconds')->nullable();  // For audio/video
            $table->string('thumbnail')->nullable();
            $table->enum('source_type', ['upload', 'url', 'youtube', 'soundcloud', 'drive', 'vimeo'])->default('upload');
            $table->string('embed_code')->nullable();   // Raw iframe embed if needed
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('autoplay')->default(false);
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->index(['lesson_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_contents');
    }
};