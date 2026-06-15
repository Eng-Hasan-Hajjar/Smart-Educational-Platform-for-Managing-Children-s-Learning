<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\{User, Classroom, Subject, Lesson, Attendance, Event};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index()
    {
        $school = Auth::user()->school;

        $totalAttendance   = Attendance::whereHas('classroom', fn($q) => $q->where('school_id', $school->id))->count();
        $presentAttendance = Attendance::whereHas('classroom', fn($q) => $q->where('school_id', $school->id))
                                ->where('status', 'present')->count();

        $stats = [
            'students'        => User::where('school_id', $school->id)->role('student')->count(),
            'teachers'        => User::where('school_id', $school->id)->role('teacher')->count(),
            'classrooms'      => Classroom::where('school_id', $school->id)->count(),
            'subjects'        => Subject::where('school_id', $school->id)->where('is_active', true)->count(),
            'lessons'         => Lesson::whereHas('teacher', fn($q) => $q->where('school_id', $school->id))
                                    ->where('status', 'published')->count(),
            'attendance_rate' => $totalAttendance > 0 ? round($presentAttendance / $totalAttendance * 100) : 0,
        ];

        $recentStudents = User::where('school_id', $school->id)
            ->role('student')
            ->with('studentProfile.academicLevel')
            ->latest()
            ->take(5)
            ->get();

        $upcomingEvents = Event::where('school_id', $school->id)
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->take(5)
            ->get();

        // عدد الطلاب الذين يحتاجون متابعة
        $atRiskCount = User::where('school_id', $school->id)
            ->role('student')
            ->whereHas('studentProfile', fn($q) => $q->whereIn('academic_status', ['at_risk', 'needs_support']))
            ->count();

        // إشغال الفصول
        $classroomsOverview = Classroom::where('school_id', $school->id)
            ->where('is_active', true)
            ->withCount('students')
            ->with('academicLevel')
            ->take(6)
            ->get();

        return view('school-admin.dashboard', compact(
            'stats', 'recentStudents', 'upcomingEvents', 'atRiskCount', 'classroomsOverview'
        ));
    }
}