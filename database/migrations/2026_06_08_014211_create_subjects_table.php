<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');           // اللغة العربية
            $table->string('name_en')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('#2196F3');
            $table->string('cover_image')->nullable();
            $table->integer('order')->default(0);
            $table->integer('weekly_hours')->default(4);
            $table->boolean('is_active')->default(true);
            $table->json('objectives')->nullable();    // أهداف المادة
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'academic_level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};