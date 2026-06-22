<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UnitProgress extends Model
{
    protected $table = 'unit_progress';
    protected $fillable = ['student_id','unit_id','progress_percentage','is_completed','lessons_completed','total_lessons','average_score','completed_at'];
    protected $casts = ['is_completed'=>'boolean','completed_at'=>'datetime'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function unit()    { return $this->belongsTo(Unit::class); }
}
