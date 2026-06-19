<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['lesson', 'quiz', 'review', 'practice', 'warning', 'praise'])->default('lesson');
            $table->morphs('recommendable'); // lesson, quiz, etc.
            $table->string('reason');
            $table->decimal('confidence_score', 4, 2)->default(0);
            $table->boolean('is_acted_upon')->default(false);
            $table->timestamp('acted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'type', 'is_acted_upon']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};