<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    protected $fillable = ['student_id','type','recommendable_type','recommendable_id','reason','confidence_score','is_acted_upon','acted_at','expires_at'];
    protected $casts = ['is_acted_upon'=>'boolean','acted_at'=>'datetime','expires_at'=>'datetime'];

    public function student()        { return $this->belongsTo(User::class, 'student_id'); }
    public function recommendable()  { return $this->morphTo(); }

    public function getTypeIconAttribute(): string {
        return match($this->type) {
            'lesson'   => '📚', 'quiz'     => '📝', 'review'   => '🔄',
            'practice' => '🎯', 'warning'  => '⚠️', 'praise'   => '⭐',
            default    => '💡',
        };
    }
    public function getTypeLabelAttribute(): string {
        return __('app.ai_type_'.$this->type);
    }
}
