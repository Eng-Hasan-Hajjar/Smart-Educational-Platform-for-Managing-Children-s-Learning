<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject_id', 'teacher_id', 'title', 'title_en', 'description',
        'cover_image', 'order', 'duration_weeks', 'is_published', 'is_free', 'objectives',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free'      => 'boolean',
        'objectives'   => 'array',
    ];

    // ─── Relations ────────────────────────────────────────────
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('status', 'published')->orderBy('order');
    }

    public function progress()
    {
        return $this->hasMany(UnitProgress::class);
    }

    // ─── Accessors ────────────────────────────────────────────
    public function getLessonsCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-unit.png');
    }

    public function getTranslatedTitleAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->title
            : ($this->title_en ?? $this->title);
    }
}