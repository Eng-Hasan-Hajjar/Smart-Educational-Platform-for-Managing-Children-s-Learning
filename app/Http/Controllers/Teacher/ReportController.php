<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{User, Classroom, Attendance, LessonProgress, QuizAttempt, AssignmentSubmission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
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

        $classrooms = Classroom::whereIn('id', $classroomIds)
            ->with('academicLevel')
            ->get();

        $report           = [];
        $selectedClassroom = null;

        if ($request->classroom_id) {
            $selectedClassroom = Classroom::find($request->classroom_id);

            $students = User::whereHas('classrooms', fn($q) =>
                    $q->where('classrooms.id', $request->classroom_id))
                ->role('student')
                ->with('studentProfile')
                ->get();

            foreach ($students as $student) {

                // نسبة الحضور
                $allAtt    = Attendance::where('student_id', $student->id)
                    ->where('classroom_id', $request->classroom_id)->count();
                $presentAtt = Attendance::where('student_id', $student->id)
                    ->where('classroom_id', $request->classroom_id)
                    ->where('status', 'present')->count();
                $attendanceRate = $allAtt > 0 ? round($presentAtt / $allAtt * 100, 1) : 0;

                // متوسط الدرجات
                $avgScore = QuizAttempt::where('student_id', $student->id)
                    ->where('status', 'graded')
                    ->avg('percentage') ?? 0;

                // دروس مكتملة
                $lessonsDone = LessonProgress::where('student_id', $student->id)
                    ->where('is_completed', true)->count();

                // واجبات مسلّمة
                $assignmentsDone = AssignmentSubmission::where('student_id', $student->id)->count();

                $report[] = [
                    'student'          => $student,
                    'attendance_rate'  => $attendanceRate,
                    'avg_score'        => round($avgScore, 1),
                    'lessons_done'     => $lessonsDone,
                    'assignments_done' => $assignmentsDone,
                ];
            }
        }

        return view('teacher.reports.index', compact(
            'classrooms', 'report', 'selectedClassroom'
        ));
    }

    public function student(User $student, Request $request)
    {
        $teacher = Auth::user();

        $classroomIds = DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        abort_unless(
            $student->classrooms()->whereIn('classrooms.id', $classroomIds)->exists(),
            403
        );

        $student->load(['studentProfile.academicLevel', 'classrooms', 'gamification']);

        $progress = LessonProgress::where('student_id', $student->id)
            ->with('lesson.unit.subject')
            ->latest()
            ->take(20)
            ->get();

        $quizAttempts = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'graded')
            ->with('quiz.subject')
            ->latest()
            ->take(15)
            ->get();

        $submissions = AssignmentSubmission::where('student_id', $student->id)
            ->with('assignment.subject')
            ->latest()
            ->take(15)
            ->get();

        $attendances = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();

        return view('teacher.reports.student', compact(
            'student', 'progress', 'quizAttempts', 'submissions', 'attendances'
        ));
    }
}