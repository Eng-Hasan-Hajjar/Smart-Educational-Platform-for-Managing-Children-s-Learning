<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['classroom_id','subject_id','teacher_id','time_slot_id','academic_year_id','day_of_week','room','is_online','meeting_link','is_active'];
    protected $casts = ['is_online'=>'boolean','is_active'=>'boolean'];

    public function classroom()    { return $this->belongsTo(Classroom::class); }
    public function subject()      { return $this->belongsTo(Subject::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function timeSlot()     { return $this->belongsTo(TimeSlot::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
