<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('performance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['weekly', 'monthly', 'semester', 'annual', 'counselor'])->default('weekly');
            $table->json('subjects_data');         // Per-subject scores and analysis
            $table->decimal('overall_gpa', 4, 2)->default(0);
            $table->decimal('overall_attendance', 5, 2)->default(0);
            $table->text('teacher_notes')->nullable();
            $table->text('counselor_notes')->nullable();
            $table->text('recommendations')->nullable();
            $table->boolean('is_sent_to_parent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reports');
    }
};