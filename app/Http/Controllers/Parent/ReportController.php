<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\{User, PerformanceReport, StudentAnalytic};
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:parent']);
    }

    public function show(User $student)
    {
        abort_unless(
            Auth::user()->children()->where('users.id', $student->id)->exists(),
            403
        );

        $reports = PerformanceReport::where('student_id', $student->id)
            ->where('is_sent_to_parent', true)
            ->with(['semester.academicYear', 'generatedBy'])
            ->latest()
            ->get();

        $analytics = StudentAnalytic::where('student_id', $student->id)
            ->with('subject')
            ->get();

        return view('parent.children.reports', compact('student', 'reports', 'analytics'));
    }
}