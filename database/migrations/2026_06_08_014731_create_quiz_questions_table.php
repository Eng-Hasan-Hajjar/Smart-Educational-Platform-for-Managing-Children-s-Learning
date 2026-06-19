<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->string('question_audio')->nullable();   // Audio question for early grades
            $table->enum('type', ['mcq', 'true_false', 'fill_blank', 'match', 'short_answer'])->default('mcq');
            $table->integer('marks')->default(10);
            $table->text('explanation')->nullable();        // Explanation shown after answer
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};