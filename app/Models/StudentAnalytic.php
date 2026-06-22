<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentAnalytic extends Model
{
    protected $fillable = ['student_id','subject_id','average_score','attendance_rate','completion_rate','total_time_spent_minutes','lessons_completed','quizzes_taken','assignments_submitted','weekly_activity','strength_areas','weak_areas','learning_pace','risk_level','last_analyzed_at'];
    protected $casts = ['weekly_activity'=>'array','strength_areas'=>'array','weak_areas'=>'array','last_analyzed_at'=>'datetime'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }
}
