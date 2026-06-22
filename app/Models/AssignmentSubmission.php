<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id','student_id','text_answer','file_path','file_name','file_size','is_late','marks_obtained','teacher_feedback','graded_at','graded_by','status'];
    protected $casts = ['is_late'=>'boolean','graded_at'=>'datetime'];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student()    { return $this->belongsTo(User::class, 'student_id'); }
    public function grader()     { return $this->belongsTo(User::class, 'graded_by'); }
}
