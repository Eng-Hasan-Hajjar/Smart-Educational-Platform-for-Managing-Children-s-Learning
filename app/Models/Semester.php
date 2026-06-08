<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'order', 'start_date', 'end_date', 'is_current', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function performanceReports()
    {
        return $this->hasMany(PerformanceReport::class);
    }
}