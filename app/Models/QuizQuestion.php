<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = ['quiz_id','question_text','question_image','question_audio','type','marks','explanation','order'];

    public function quiz()    { return $this->belongsTo(Quiz::class); }
    public function options() { return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('order'); }

    public function correctOption() { return $this->hasOne(QuestionOption::class, 'question_id')->where('is_correct', true); }
    public function getQuestionImageUrlAttribute(): ?string {
        return $this->question_image ? asset('storage/'.$this->question_image) : null;
    }
}
