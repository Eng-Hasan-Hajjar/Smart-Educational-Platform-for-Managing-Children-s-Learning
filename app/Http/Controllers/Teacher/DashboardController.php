<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{Lesson, Quiz, Assignment, AssignmentSubmission, Schedule, User,TeacherProfile};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct()
    {
    //   $this->middleware(['auth', 'role:teacher']);
    }

    public function index()
    {
        $teacher = Auth::user();

        $classroomIds = DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        $studentsCount = User::whereHas('classrooms', fn($q) => $q->whereIn('classrooms.id', $classroomIds))
            ->role('student')
            ->distinct()
            ->count();

        $stats = [
            'lessons'             => Lesson::where('teacher_id', $teacher->id)->count(),
            'students'            => $studentsCount,
            'assignments'         => Assignment::where('teacher_id', $teacher->id)->count(),
            'quizzes'             => Quiz::where('teacher_id', $teacher->id)->count(),
            'pending_submissions' => AssignmentSubmission::whereHas('assignment', fn($q) =>
                                        $q->where('teacher_id', $teacher->id))
                                        ->where('status', 'submitted')
                                        ->count(),
        ];

        // جدول اليوم
        $todaySchedules = Schedule::whereIn('classroom_id', $classroomIds)
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', now()->dayOfWeek)
            ->where('is_active', true)
            ->with(['subject', 'classroom', 'timeSlot'])
            ->orderBy('time_slot_id')
            ->get();

        // آخر الدروس   
        $recentLessons = Lesson::where('teacher_id', $teacher->id)
            ->with('unit.subject')
            ->latest()
            ->take(5)
            ->get();

        // تسليمات بانتظار التصحيح
        $recentSubmissions = AssignmentSubmission::whereHas('assignment', fn($q) =>
                $q->where('teacher_id', $teacher->id))
            ->where('status', 'submitted')
            ->with(['student', 'assignment'])
            ->latest()
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'stats', 'todaySchedules', 'recentLessons', 'recentSubmissions'
        ));
    }
}