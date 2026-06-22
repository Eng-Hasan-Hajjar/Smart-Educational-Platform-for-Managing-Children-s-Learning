<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['question_id','option_text','option_image','option_audio','is_correct','order'];
    protected $casts = ['is_correct'=>'boolean'];

    public function question() { return $this->belongsTo(QuizQuestion::class, 'question_id'); }
    public function getOptionImageUrlAttribute(): ?string {
        return $this->option_image ? asset('storage/'.$this->option_image) : null;
    }
}
