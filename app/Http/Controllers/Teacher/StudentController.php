<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{User, Attendance, LessonProgress, QuizAttempt, AssignmentSubmission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index(Request $request)
    {
        $teacher = Auth::user();

        $classroomIds = DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        $students = User::whereHas('classrooms', fn($q) =>
                $q->whereIn('classrooms.id', $classroomIds))
            ->role('student')
            ->with(['studentProfile.academicLevel', 'classrooms', 'gamification'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->whereHas('studentProfile', fn($p) =>
                    $p->where('academic_status', $request->status)
                )
            )
            ->when($request->classroom_id, fn($q) =>
                $q->whereHas('classrooms', fn($c) =>
                    $c->where('classrooms.id', $request->classroom_id)
                )
            )
            ->distinct()
            ->paginate(15)
            ->withQueryString();

        $classrooms = \App\Models\Classroom::whereIn('id', $classroomIds)
            ->with('academicLevel')
            ->get();

        return view('teacher.students.index', compact('students', 'classrooms'));
    }

    public function show(User $student)
    {
        $teacher = Auth::user();

        // التأكد من أن الطالب في أحد فصول المعلم
        $classroomIds = DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        abort_unless(
            $student->classrooms()->whereIn('classrooms.id', $classroomIds)->exists(),
            403
        );

        $student->load([
            'studentProfile.academicLevel',
            'classrooms',
            'gamification',
            'badges.badge',
            'analytics.subject',
        ]);

        // تقدم الدروس مجمّعاً حسب المادة
        $lessonProgress = LessonProgress::where('student_id', $student->id)
            ->with('lesson.unit.subject')
            ->get();

        $progressBySubject = $lessonProgress
            ->groupBy(fn($p) => $p->lesson->unit->subject->name ?? __('app.other'))
            ->map(fn($group) => [
                'completed' => $group->where('is_completed', true)->count(),
                'total'     => $group->count(),
                'pct'       => $group->count() > 0
                    ? round($group->where('is_completed', true)->count() / $group->count() * 100)
                    : 0,
                'time_spent'=> round($group->sum('time_spent_seconds') / 60),
            ]);

        // آخر نتائج الاختبارات
        $quizResults = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'graded')
            ->with('quiz.subject')
            ->latest()
            ->take(10)
            ->get();

        // الواجبات
        $submissions = AssignmentSubmission::where('student_id', $student->id)
            ->with('assignment.subject')
            ->latest()
            ->take(10)
            ->get();

        // الحضور آخر 30 يوم
        $attendances = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();

        $attendanceRate = $attendances->count() > 0
            ? round($attendances->where('status', 'present')->count() / $attendances->count() * 100, 1)
            : 0;

        return view('teacher.students.show', compact(
            'student',
            'progressBySubject',
            'quizResults',
            'submissions',
            'attendances',
            'attendanceRate'
        ));
    }
}