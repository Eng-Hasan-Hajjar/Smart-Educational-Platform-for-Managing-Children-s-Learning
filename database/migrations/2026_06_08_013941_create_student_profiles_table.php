<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('academic_level_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_number')->unique()->nullable();
            $table->string('blood_type')->nullable();
            $table->text('medical_notes')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->integer('total_points')->default(0);
            $table->integer('current_level')->default(1);   // Gamification level
            $table->decimal('gpa', 4, 2)->default(0.00);
            $table->enum('academic_status', ['excellent', 'good', 'average', 'needs_support', 'at_risk'])->default('average');
            $table->json('learning_style')->nullable();     // AI-determined learning style
            $table->timestamps();

            $table->index('academic_level_id');
            $table->index('academic_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};