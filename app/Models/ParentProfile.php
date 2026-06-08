<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id', 'occupation', 'secondary_phone',
        'relation_to_child', 'receive_sms', 'receive_email',
    ];

    protected $casts = [
        'receive_sms'   => 'boolean',
        'receive_email' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
