<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->enum('type', ['holiday', 'exam', 'activity', 'meeting', 'deadline', 'other'])->default('activity');
            $table->string('color')->default('#2196F3');
            $table->boolean('is_public')->default(true);
            $table->json('target_audience')->nullable();  // all, specific levels, classrooms
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};