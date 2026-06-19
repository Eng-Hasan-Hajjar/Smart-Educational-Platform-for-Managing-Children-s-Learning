<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('student_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->text('text_answer')->nullable();    // For fill_blank / short_answer
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_obtained', 5, 2)->default(0);
            $table->integer('attempt_number')->default(1);
            $table->timestamps();

            $table->index(['student_id', 'quiz_id']);
        });

        // Quiz attempts summary table
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->integer('attempt_number')->default(1);
            $table->decimal('total_marks_obtained', 6, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('is_passed')->default(false);
            $table->integer('time_taken_seconds')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->text('teacher_feedback')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'quiz_id', 'attempt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('student_quiz_answers');
    }
};