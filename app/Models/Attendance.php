<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id','classroom_id','schedule_id','teacher_id','date','status','notes'];
    protected $casts = ['date'=>'date'];

    public function student()   { return $this->belongsTo(User::class, 'student_id'); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function teacher()   { return $this->belongsTo(User::class, 'teacher_id'); }
    public function schedule()  { return $this->belongsTo(Schedule::class); }
}
