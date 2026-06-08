<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name');           // e.g. "الصف الأول - أ"
            $table->string('section')->nullable(); // أ، ب، ج
            $table->integer('capacity')->default(30);
            $table->string('room_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'academic_level_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};