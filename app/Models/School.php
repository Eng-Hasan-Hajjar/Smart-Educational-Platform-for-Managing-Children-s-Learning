<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'name_en', 'slug', 'description', 'logo', 'cover_image',
        'email', 'phone', 'address', 'city', 'country', 'website',
        'status', 'subscription_plan', 'subscription_expires_at',
        'max_students', 'max_teachers', 'settings',
    ];

    protected $casts = [
        'subscription_expires_at' => 'date',
        'settings'                => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function currentAcademicYear()
    {
        return $this->hasOne(AcademicYear::class)->where('is_current', true);
    }

    public function academicLevels()
    {
        return $this->hasMany(AcademicLevel::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function teachers()
    {
        return $this->users()->whereHas('roles', fn($q) => $q->where('name', 'teacher'));
    }

    public function students()
    {
        return $this->users()->whereHas('roles', fn($q) => $q->where('name', 'student'));
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-school.png');
    }

    public function isSubscriptionActive(): bool
    {
        return $this->status === 'active' &&
               ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
    }
}