<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id', 'academic_level_id', 'semester_id', 'name', 'name_en',
        'code', 'description', 'icon', 'color', 'cover_image',
        'order', 'weekly_hours', 'is_active', 'objectives',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'objectives' => 'array',
    ];

    // ─── Relations ────────────────────────────────────────────
    public function school()        { return $this->belongsTo(School::class); }
    public function academicLevel() { return $this->belongsTo(AcademicLevel::class); }
    public function semester()      { return $this->belongsTo(Semester::class); }

    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy('order');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_classroom', 'subject_id', 'teacher_id')
                    ->withPivot('classroom_id', 'academic_year_id')
                    ->withTimestamps();
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'teacher_subject_classroom', 'subject_id', 'classroom_id')
                    ->withPivot('teacher_id', 'academic_year_id')
                    ->withTimestamps();
    }

    public function lessons() { return $this->hasManyThrough(Lesson::class, Unit::class); }
    public function quizzes()       { return $this->hasMany(Quiz::class); }
    public function assignments()   { return $this->hasMany(Assignment::class); }
    public function analytics()     { return $this->hasMany(StudentAnalytic::class); }
    public function learningPaths() { return $this->hasMany(LearningPath::class); }

    // ─── Accessors ────────────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-subject.png');
    }

    public function getTranslatedNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->name
            : ($this->name_en ?? $this->name);
    }
}