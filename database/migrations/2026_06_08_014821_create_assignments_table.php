<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('instructions')->nullable();
            $table->string('attachment')->nullable();    // Teacher's attachment
            $table->integer('total_marks')->default(100);
            $table->datetime('due_date');
            $table->boolean('allow_late_submission')->default(false);
            $table->integer('late_penalty_percent')->default(0);
            $table->enum('submission_type', ['text', 'file', 'both'])->default('both');
            $table->json('allowed_file_types')->nullable();
            $table->integer('max_file_size_mb')->default(10);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['classroom_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};