<?php
namespace App\Models;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['unit_id','teacher_id','title','description','thumbnail','order','duration_minutes','status','is_free','allow_download','published_at','tags','objectives','view_count'];
    protected $casts = ['tags'=>'array','objectives'=>'array','published_at'=>'datetime','is_free'=>'boolean','allow_download'=>'boolean'];

    public function unit()     { return $this->belongsTo(Unit::class); }
    public function teacher()  { return $this->belongsTo(User::class, 'teacher_id'); }
    public function contents() { return $this->hasMany(LessonContent::class)->orderBy('order'); }
    public function audioResources() { return $this->hasMany(AudioResource::class)->orderBy('order'); }
    public function quizzes()  { return $this->hasMany(Quiz::class); }
    public function progress() { return $this->hasMany(LessonProgress::class); }

    public function getThumbnailUrlAttribute(): string {
        return $this->thumbnail ? asset('storage/'.$this->thumbnail) : asset('images/default-lesson.png');
    }
    public function getTranslatedTitleAttribute(): string { return $this->title; }
    public function scopePublished($q) { return $q->where('status','published'); }
}
