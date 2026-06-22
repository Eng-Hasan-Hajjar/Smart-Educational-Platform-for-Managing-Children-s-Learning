<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GamificationPoint extends Model
{
    protected $fillable = ['student_id','total_points','weekly_points','monthly_points','level','level_title','points_history'];
    protected $casts = ['points_history'=>'array'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }

    public function getLevelProgressAttribute(): int {
        $thresholds = [0,100,300,600,1000,1500,2200,3000,4000,5500,7500,10000];
        $level = min($this->level, count($thresholds)-1);
        $current = $thresholds[$level-1] ?? 0;
        $next    = $thresholds[$level] ?? $thresholds[count($thresholds)-1];
        if ($next <= $current) return 100;
        return min(100, (int) round(($this->total_points - $current) / ($next - $current) * 100));
    }
}
