<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    protected $fillable = ['student_id','subject_id','steps','current_step','progress','is_completed','algorithm_version','generated_at','completed_at'];
    protected $casts = ['steps'=>'array','is_completed'=>'boolean','generated_at'=>'datetime','completed_at'=>'datetime'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }

    public function getTotalStepsAttribute(): int { return count($this->steps ?? []); }
    public function getCurrentStepDataAttribute(): ?array {
        return ($this->steps ?? [])[$this->current_step] ?? null;
    }
}
