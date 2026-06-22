<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PerformanceReport extends Model
{
    protected $fillable = ['student_id','semester_id','generated_by','type','subjects_data','overall_gpa','overall_attendance','teacher_notes','counselor_notes','recommendations','is_sent_to_parent','sent_at'];
    protected $casts = ['subjects_data'=>'array','is_sent_to_parent'=>'boolean','sent_at'=>'datetime'];

    public function student()     { return $this->belongsTo(User::class, 'student_id'); }
    public function semester()    { return $this->belongsTo(Semester::class); }
    public function generatedBy() { return $this->belongsTo(User::class, 'generated_by'); }

    public function getTypeLabelAttribute(): string {
        return match($this->type) {
            'weekly'    => __('counselor.type_weekly'),
            'monthly'   => __('counselor.type_monthly'),
            'semester'  => __('counselor.type_semester'),
            'annual'    => __('counselor.type_annual'),
            'counselor' => __('counselor.type_counselor'),
            default     => $this->type,
        };
    }
}
