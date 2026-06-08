<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon');         // Emoji or image path
            $table->string('color')->default('#FFD700');
            $table->enum('category', ['academic', 'attendance', 'social', 'streak', 'special'])->default('academic');
            $table->enum('condition_type', ['lessons_completed', 'quiz_score', 'streak_days', 'points_earned', 'perfect_attendance', 'assignments_done'])->default('lessons_completed');
            $table->integer('condition_value');     // e.g. 10 (for 10 lessons)
            $table->integer('points_reward')->default(50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};