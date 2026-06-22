<?php
namespace App\Models;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Quiz extends Model
{
    use SoftDeletes;
    protected $fillable = ['lesson_id','unit_id','subject_id','teacher_id','title','description','instructions','type','total_marks','pass_marks','duration_minutes','max_attempts','shuffle_questions','shuffle_options','show_results_immediately','show_correct_answers','available_from','available_until','status'];
    protected $casts = ['shuffle_questions'=>'boolean','shuffle_options'=>'boolean','show_results_immediately'=>'boolean','show_correct_answers'=>'boolean','available_from'=>'datetime','available_until'=>'datetime'];

    public function lesson()    { return $this->belongsTo(Lesson::class); }
    public function unit()      { return $this->belongsTo(Unit::class); }
    public function subject()   { return $this->belongsTo(Subject::class); }
    public function teacher()   { return $this->belongsTo(User::class, 'teacher_id'); }
    public function questions() { return $this->hasMany(QuizQuestion::class)->orderBy('order'); }
    public function attempts()  { return $this->hasMany(QuizAttempt::class); }

    public function isAvailable(): bool {
        if ($this->status !== 'published') return false;
        if ($this->available_from && now()->lt($this->available_from)) return false;
        if ($this->available_until && now()->gt($this->available_until)) return false;
        return true;
    }
    public function getQuestionsCountAttribute(): int { return $this->questions()->count(); }
}
