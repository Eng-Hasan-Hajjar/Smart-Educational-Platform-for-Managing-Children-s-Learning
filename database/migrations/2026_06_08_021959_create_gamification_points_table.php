<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamification_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->integer('total_points')->default(0);
            $table->integer('weekly_points')->default(0);
            $table->integer('monthly_points')->default(0);
            $table->integer('level')->default(1);
            $table->string('level_title')->default('مبتدئ');   // Level name in Arabic
            $table->json('points_history')->nullable();          // Log of points earned
            $table->timestamps();

            $table->unique('student_id');
        });

        // Log every point earning event
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->integer('points');
            $table->enum('action', ['lesson_complete', 'quiz_pass', 'assignment_submit', 'perfect_score', 'streak', 'badge_earned', 'attendance', 'bonus'])->default('lesson_complete');
            $table->string('description')->nullable();
            $table->morphs('rewardable');  // Lesson, quiz, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('gamification_points');
    }
};