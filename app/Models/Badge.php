<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name','description','icon','color','category','condition_type','condition_value','points_reward','is_active'];
    protected $casts = ['is_active'=>'boolean'];

    public function students() {
        return $this->belongsToMany(User::class, 'student_badges', 'badge_id', 'student_id')
                    ->withPivot('earned_at','is_featured')->withTimestamps();
    }
}
