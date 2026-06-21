<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\{User, Attendance, QuizAttempt, AssignmentSubmission, LessonProgress, AcademicLevel, Classroom};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:counselor']);
    }

    public function index(Request $request)
    {
        $school = Auth::user()->school;

        $students = User::where('school_id', $school->id)
            ->role('student')
            ->with(['studentProfile.academicLevel', 'classrooms'])
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
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $classrooms = Classroom::where('school_id', $school->id)->with('academicLevel')->get();

        return view('counselor.students.index', compact('students', 'classrooms'));
    }

    public function show(User $student)
    {
        abort_unless($student->school_id === Auth::user()->school_id, 403);

        $student->load(['studentProfile.academicLevel', 'classrooms', 'gamification', 'parents']);

        $lessonProgress = LessonProgress::where('student_id', $student->id)
            ->with('lesson.unit.subject')
            ->latest()
            ->take(10)
            ->get();

        $quizResults = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'graded')
            ->with('quiz.subject')
            ->latest()
            ->take(10)
            ->get();

        $submissions = AssignmentSubmission::where('student_id', $student->id)
            ->with('assignment.subject')
            ->latest()
            ->take(10)
            ->get();

        $attendances = Attendance::where('student_id', $student->id)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get();

        $attendanceRate = $attendances->count() > 0
            ? round($attendances->where('status', 'present')->count() / $attendances->count() * 100, 1)
            : 0;

        $avgScore = round($quizResults->avg('percentage') ?? 0, 1);

        $reports = $student->performanceReports()
            ->with(['semester.academicYear', 'generatedBy'])
            ->latest()
            ->take(5)
            ->get();

        return view('counselor.students.show', compact(
            'student', 'lessonProgress', 'quizResults', 'submissions',
            'attendances', 'attendanceRate', 'avgScore', 'reports'
        ));
    }
}