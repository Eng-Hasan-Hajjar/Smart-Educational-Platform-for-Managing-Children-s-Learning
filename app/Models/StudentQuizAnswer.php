<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentQuizAnswer extends Model
{
    protected $fillable = ['student_id','quiz_id','question_id','selected_option_id','text_answer','is_correct','marks_obtained','attempt_number'];
    protected $casts = ['is_correct'=>'boolean'];

    public function student()        { return $this->belongsTo(User::class, 'student_id'); }
    public function quiz()           { return $this->belongsTo(Quiz::class); }
    public function question()       { return $this->belongsTo(QuizQuestion::class, 'question_id'); }
    public function selectedOption() { return $this->belongsTo(QuestionOption::class, 'selected_option_id'); }
}
