<?php
namespace App\Models;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Announcement extends Model
{
    use SoftDeletes;
    protected $fillable = ['school_id','created_by','title','body','attachment','type','target_type','target_ids','send_notification','send_email','publish_at','expires_at','is_pinned'];
    protected $casts = ['target_ids'=>'array','send_notification'=>'boolean','send_email'=>'boolean','publish_at'=>'datetime','expires_at'=>'datetime','is_pinned'=>'boolean'];

    public function school()    { return $this->belongsTo(School::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeForRole($q, $role) {
        return $q->where(function($q) use ($role) {
            $q->where('target_type', 'all')
              ->orWhere('target_type', match($role) {
                  'teacher'      => 'teachers',
                  'student'      => 'students',
                  'parent'       => 'parents',
                  'school_admin' => 'all',
                  'counselor'    => 'all',
                  default        => 'all',
              });
        });
    }
}
