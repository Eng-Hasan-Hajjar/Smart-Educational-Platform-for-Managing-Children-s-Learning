<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\AiRecommendation;
use App\Models\GamificationPoint;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function index()
    {
        $student = Auth::user();
        $profile = $student->studentProfile;

        $gamify = GamificationPoint::firstOrCreate(
            ['student_id' => $student->id],
            ['total_points' => 0, 'level' => 1, 'level_title' => 'مبتدئ']
        );

        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        // جدول اليوم
        $todaySchedules = Schedule::whereIn('classroom_id', $classroomIds)
            ->where('day_of_week', now()->dayOfWeek)
            ->where('is_active', true)
            ->with(['subject', 'teacher', 'timeSlot'])
            ->orderBy('time_slot_id')
            ->get();

        // دروس قيد التقدم
        $inProgressLessons = $student->lessonProgress()
            ->where('is_completed', false)
            ->where('progress_percentage', '>', 0)
            ->with('lesson.unit.subject')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        // واجبات معلقة
        $pendingAssignments = Assignment::whereIn('classroom_id', $classroomIds)
            ->where('status', 'published')
            ->where('due_date', '>=', now())
            ->whereDoesntHave('submissions', fn($q) => $q->where('student_id', $student->id))
            ->with('subject')
            ->orderBy('due_date')
            ->take(4)
            ->get();

        // توصيات الذكاء الاصطناعي
        $recommendations = AiRecommendation::where('student_id', $student->id)
            ->active()
            ->with('recommendable')
            ->take(3)
            ->get();

        // آخر الشارات
        $recentBadges = $student->badges()
            ->orderByPivot('earned_at', 'desc')
            ->take(3)
            ->get();

        $stats = [
            'lessons_done'   => $student->lessonProgress()->where('is_completed', true)->count(),
            'quizzes_done'   => $student->quizAttempts()->where('status', 'graded')->count(),
            'avg_score'      => round($student->quizAttempts()->where('status', 'graded')->avg('percentage') ?? 0, 1),
            'total_points'   => $gamify->total_points,
            'level'          => $gamify->level,
            'level_title'    => $gamify->level_title,
            'level_progress' => $gamify->level_progress,
        ];

        return view('student.dashboard', compact(
            'student', 'profile', 'gamify', 'todaySchedules',
            'inProgressLessons', 'pendingAssignments',
            'recommendations', 'recentBadges', 'stats'
        ));
    }
}