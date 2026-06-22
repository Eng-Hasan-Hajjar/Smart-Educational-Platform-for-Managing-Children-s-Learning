<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';
    protected $fillable = ['student_id','lesson_id','progress_percentage','is_completed','started_at','completed_at','time_spent_seconds','play_count','content_progress'];
    protected $casts = ['is_completed'=>'boolean','started_at'=>'datetime','completed_at'=>'datetime','content_progress'=>'array'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function lesson()  { return $this->belongsTo(Lesson::class); }
}
