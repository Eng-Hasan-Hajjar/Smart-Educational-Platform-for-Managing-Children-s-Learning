<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id', 'specialization', 'qualification', 'experience_years',
        'bio', 'cv_file', 'rating', 'total_ratings', 'social_links', 'is_available',
    ];

    protected $casts = [
        'social_links'  => 'array',
        'is_available'  => 'boolean',
        'rating'        => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCvFileUrlAttribute(): ?string
    {
        return $this->cv_file ? asset('storage/' . $this->cv_file) : null;
    }
}