<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'academic_level_id', 'academic_year_id',
        'name', 'section', 'capacity', 'room_number', 'is_active', 'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ────────────────────────────────────────────
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'student_id')
                    ->withPivot('enrolled_at', 'is_active', 'seat_number')
                    ->wherePivot('is_active', true)
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_classroom', 'classroom_id', 'teacher_id')
                    ->withPivot('subject_id', 'academic_year_id')
                    ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject_classroom', 'classroom_id', 'subject_id')
                    ->withPivot('teacher_id', 'academic_year_id')
                    ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('day_of_week')->orderBy('time_slot_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // ─── Accessors ────────────────────────────────────────────
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    public function getIsFullAttribute(): bool
    {
        return $this->students_count >= $this->capacity;
    }

    public function getFillPercentageAttribute(): int
    {
        return $this->capacity > 0
            ? (int) round(($this->students_count / $this->capacity) * 100)
            : 0;
    }
}