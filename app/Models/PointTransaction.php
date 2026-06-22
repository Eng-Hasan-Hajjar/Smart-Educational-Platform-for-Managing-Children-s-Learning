<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = ['student_id','points','action','description','rewardable_type','rewardable_id'];

    public function student()    { return $this->belongsTo(User::class, 'student_id'); }
    public function rewardable() { return $this->morphTo(); }

    public function getActionLabelAttribute(): string {
        return __('app.action_'.$this->action);
    }
}
