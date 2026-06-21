<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('learning_paths', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->json('steps');             // Ordered list of lesson/quiz/activity IDs
            $table->integer('current_step')->default(0);
            $table->decimal('progress', 5, 2)->default(0);
            $table->boolean('is_completed')->default(false);
            $table->string('algorithm_version')->default('v1');
            $table->timestamp('generated_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};