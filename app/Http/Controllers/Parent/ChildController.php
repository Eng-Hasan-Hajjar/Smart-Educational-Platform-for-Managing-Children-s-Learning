<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:parent']);
    }

    public function index()
    {
        $children = Auth::user()->children()
            ->with(['studentProfile.academicLevel', 'classrooms', 'gamification'])
            ->get();

        return view('parent.children.index', compact('children'));
    }

    public function show(User $student)
    {
        abort_unless(
            Auth::user()->children()->where('users.id', $student->id)->exists(),
            403
        );

        $student->load([
            'studentProfile.academicLevel',
            'classrooms',
            'gamification',
            'badges.badge',
        ]);

        $lessonProgress = $student->lessonProgress()
            ->with('lesson.unit.subject')
            ->latest()
            ->take(8)
            ->get();

        $quizResults = $student->quizAttempts()
            ->where('status', 'graded')
            ->with('quiz.subject')
            ->latest()
            ->take(8)
            ->get();

        $submissions = $student->assignmentSubmissions()
            ->with('assignment.subject')
            ->latest()
            ->take(8)
            ->get();

        $attendances = $student->attendances()
            ->where('date', '>=', now()->subDays(14))
            ->orderBy('date', 'desc')
            ->get();

        $attendanceRate = $attendances->count() > 0
            ? round($attendances->where('status', 'present')->count() / $attendances->count() * 100, 1)
            : 0;

        return view('parent.children.show', compact(
            'student', 'lessonProgress', 'quizResults', 'submissions', 'attendances', 'attendanceRate'
        ));
    }
}