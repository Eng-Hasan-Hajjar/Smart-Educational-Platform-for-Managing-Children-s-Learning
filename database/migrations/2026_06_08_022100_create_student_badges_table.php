<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('earned_at')->useCurrent();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'badge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_badges');
    }
};
