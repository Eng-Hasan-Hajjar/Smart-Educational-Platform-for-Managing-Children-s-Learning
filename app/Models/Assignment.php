<?php
namespace App\Models;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Assignment extends Model
{
    use SoftDeletes;
    protected $fillable = ['lesson_id','subject_id','classroom_id','teacher_id','title','description','instructions','attachment','total_marks','due_date','allow_late_submission','late_penalty_percent','submission_type','allowed_file_types','max_file_size_mb','status'];
    protected $casts = ['due_date'=>'datetime','allow_late_submission'=>'boolean','allowed_file_types'=>'array'];

    public function lesson()      { return $this->belongsTo(Lesson::class); }
    public function subject()     { return $this->belongsTo(Subject::class); }
    public function classroom()   { return $this->belongsTo(Classroom::class); }
    public function teacher()     { return $this->belongsTo(User::class, 'teacher_id'); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }

    public function isOverdue(): bool { return now()->gt($this->due_date); }
}
