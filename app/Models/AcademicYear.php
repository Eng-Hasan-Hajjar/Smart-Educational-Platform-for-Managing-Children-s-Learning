<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name', 'start_date', 'end_date', 'is_current', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class)->orderBy('order');
    }

    public function currentSemester()
    {
        return $this->hasOne(Semester::class)->where('is_current', true);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}