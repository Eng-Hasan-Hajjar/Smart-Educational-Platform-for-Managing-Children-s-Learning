<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['student_id','quiz_id','attempt_number','total_marks_obtained','percentage','is_passed','time_taken_seconds','started_at','submitted_at','status','teacher_feedback'];
    protected $casts = ['is_passed'=>'boolean','started_at'=>'datetime','submitted_at'=>'datetime'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function answers() { return $this->hasMany(StudentQuizAnswer::class, 'student_id', 'student_id')->where('quiz_id', $this->quiz_id); }
}
