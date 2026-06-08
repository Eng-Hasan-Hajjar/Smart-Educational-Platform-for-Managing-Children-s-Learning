<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'academic_level_id', 'student_number', 'blood_type',
        'medical_notes', 'emergency_contact', 'emergency_phone',
        'enrollment_date', 'total_points', 'current_level', 'gpa',
        'academic_status', 'learning_style',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'learning_style'  => 'array',
        'gpa'             => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function academicLevel()
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->academic_status) {
            'excellent'     => 'ممتاز',
            'good'          => 'جيد',
            'average'       => 'متوسط',
            'needs_support' => 'يحتاج دعماً',
            'at_risk'       => 'في خطر',
            default         => 'غير محدد',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->academic_status) {
            'excellent'     => 'green',
            'good'          => 'blue',
            'average'       => 'yellow',
            'needs_support' => 'orange',
            'at_risk'       => 'red',
            default         => 'gray',
        };
    }
}