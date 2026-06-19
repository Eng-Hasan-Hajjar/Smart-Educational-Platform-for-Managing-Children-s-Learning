<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('order')->default(0);
            $table->integer('duration_weeks')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free')->default(false);
            $table->json('objectives')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['subject_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};