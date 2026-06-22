<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['subject','last_message_at'];
    protected $casts = ['last_message_at'=>'datetime'];

    public function participants() { return $this->belongsToMany(User::class, 'conversation_user')->withPivot('last_read_at')->withTimestamps(); }
    public function messages()     { return $this->hasMany(Message::class)->orderBy('created_at'); }
    public function latestMessage() { return $this->hasOne(Message::class)->latest(); }

    public function scopeBetweenUsers($q, $user1, $user2) {
        return $q->whereHas('participants', fn($p) => $p->where('user_id', $user1))
                 ->whereHas('participants', fn($p) => $p->where('user_id', $user2));
    }
}
