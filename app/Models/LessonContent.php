<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LessonContent extends Model
{
    protected $fillable = ['lesson_id','type','title','body','file_path','file_url','file_name','file_size','mime_type','duration_seconds','thumbnail','source_type','embed_code','is_downloadable','autoplay','order','is_required'];
    protected $casts = ['is_downloadable'=>'boolean','autoplay'=>'boolean','is_required'=>'boolean'];

    public function lesson() { return $this->belongsTo(Lesson::class); }

    public function isText(): bool  { return $this->type === 'text'; }
    public function isVideo(): bool { return $this->type === 'video'; }
    public function isAudio(): bool { return $this->type === 'audio'; }
    public function isImage(): bool { return $this->type === 'image'; }

    public function getMediaUrlAttribute(): string {
        return $this->file_url ?: ($this->file_path ? asset('storage/'.$this->file_path) : '');
    }
    public function getEmbedHtmlAttribute(): ?string {
        if (!$this->file_url) return null;
        if ($this->source_type === 'youtube') {
            $id = last(explode('/', str_replace('watch?v=','', $this->file_url)));
            return '<iframe class="w-full aspect-video rounded-2xl" src="https://www.youtube.com/embed/'.$id.'" allowfullscreen></iframe>';
        }
        return $this->embed_code;
    }
    public function getFileSizeHumanAttribute(): string {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024) return $bytes.' B';
        if ($bytes < 1048576) return round($bytes/1024,1).' KB';
        return round($bytes/1048576,1).' MB';
    }
}
