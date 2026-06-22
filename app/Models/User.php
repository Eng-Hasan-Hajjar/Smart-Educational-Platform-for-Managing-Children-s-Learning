<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'school_id', 'name', 'name_en', 'email', 'username', 'national_id',
        'phone', 'avatar', 'gender', 'birth_date', 'address',
        'password', 'status', 'locale', 'last_login_at', 'last_login_ip',
        'email_verified_at', 'theme',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'birth_date'        => 'date',
        'password'          => 'hashed',
    ];

    // ============ RELATIONSHIPS ============

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }

    // Parent → Children
    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id')
                    ->withPivot('relation', 'is_primary')
                    ->withTimestamps();
    }

    // Student → Parents
    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
                    ->withPivot('relation', 'is_primary')
                    ->withTimestamps();
    }

    // Student → Classrooms
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student', 'student_id')
                    ->withPivot('enrolled_at', 'is_active', 'seat_number')
                    ->withTimestamps();
    }

    // Teacher → Subjects via pivot
    public function teachingSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject_classroom', 'teacher_id')
                    ->withPivot('classroom_id', 'academic_year_id', 'is_primary')
                    ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'student_id');
    }

    public function unitProgress()
    {
        return $this->hasMany(UnitProgress::class, 'student_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function analytics()
    {
        return $this->hasMany(StudentAnalytic::class, 'student_id');
    }

    public function gamification()
    {
        return $this->hasOne(GamificationPoint::class, 'student_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'student_badges', 'student_id')
                    ->withPivot('earned_at', 'is_featured')
                    ->withTimestamps();
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class, 'student_id');
    }

    public function learningPaths()
     {
        return $this->hasMany(LearningPath::class, 'student_id');
    }

    public function performanceReports()
    {
        return $this->hasMany(PerformanceReport::class, 'student_id');
    }
   

    // ============ HELPERS ============

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isSchoolAdmin(): bool
    {
        return $this->hasRole('school_admin');
    }

    public function isCounselor(): bool
    {
        return $this->hasRole('counselor');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isParent(): bool
    {
        return $this->hasRole('parent');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1E3A5F&color=fff&size=128';
    }

    /**
     * توجيه المستخدم للوحة التحكم المناسبة حسب دوره
     * تم تصحيح أسماء المسارات لتطابق routes/web.php المُسلَّم
     */
    public function getDashboardRoute(): string
    {
        if ($this->isSuperAdmin())   return route('admin.dashboard');
        if ($this->isSchoolAdmin())  return route('school-admin.dashboard');
        if ($this->isCounselor())    return route('counselor.dashboard');
        if ($this->isTeacher())      return route('teacher.dashboard');
        if ($this->isParent())       return route('parent.dashboard');
        if ($this->isStudent())      return route('student.dashboard');
        return route('login');
    }

    public function getUnreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }
}