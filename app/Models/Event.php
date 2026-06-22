<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['school_id','created_by','title','description','start_datetime','end_datetime','location','meeting_link','type','color','is_public','target_audience'];
    protected $casts = ['start_datetime'=>'datetime','end_datetime'=>'datetime','is_public'=>'boolean','target_audience'=>'array'];

    public function school()    { return $this->belongsTo(School::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
