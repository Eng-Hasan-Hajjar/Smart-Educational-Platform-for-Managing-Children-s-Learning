<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = ['school_id','name','start_time','end_time','is_break','order'];
    protected $casts = ['is_break'=>'boolean'];

    public function school() { return $this->belongsTo(School::class); }
}
