<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('average_score', 5, 2)->default(0);
            $table->decimal('attendance_rate', 5, 2)->default(0);
            $table->decimal('completion_rate', 5, 2)->default(0);
            $table->integer('total_time_spent_minutes')->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->integer('quizzes_taken')->default(0);
            $table->integer('assignments_submitted')->default(0);
            $table->json('weekly_activity')->nullable();    // Activity per day
            $table->json('strength_areas')->nullable();     // Topics student excels in
            $table->json('weak_areas')->nullable();         // Topics needing improvement
            $table->enum('learning_pace', ['fast', 'average', 'slow'])->default('average');
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_analytics');
    }
};