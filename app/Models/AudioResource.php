<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AudioResource extends Model
{
    protected $fillable = ['lesson_content_id','lesson_id','title','description','category','language','file_path','file_url','source_type','duration_seconds','thumbnail','transcript','order','is_active','show_transcript'];
    protected $casts = ['is_active'=>'boolean','show_transcript'=>'boolean'];

    public function lesson() { return $this->belongsTo(Lesson::class); }
    public function getPlayerHtmlAttribute(): string {
        if ($this->file_url && $this->source_type === 'soundcloud') return '<iframe width="100%" height="166" src="'.$this->file_url.'" frameborder="0"></iframe>';
        $src = $this->file_url ?: ($this->file_path ? asset('storage/'.$this->file_path) : '');
        return '<audio controls class="w-full"><source src="'.$src.'"></audio>';
    }
}
