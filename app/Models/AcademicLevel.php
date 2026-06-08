<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicLevel extends Model
{
    protected $fillable = [
        'school_id', 'name', 'name_en', 'order', 'color', 'icon',
        'description', 'min_age', 'max_age', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }
}