<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:counselor']);
    }

    public function index()
    {
        $school = Auth::user()->school;

        $stats = [
            'total_students'  => User::where('school_id', $school->id)->role('student')->count(),
            'at_risk'         => User::where('school_id', $school->id)->role('student')
                                    ->whereHas('studentProfile', fn($q) => $q->where('academic_status', 'at_risk'))
                                    ->count(),
            'needs_support'   => User::where('school_id', $school->id)->role('student')
                                    ->whereHas('studentProfile', fn($q) => $q->where('academic_status', 'needs_support'))
                                    ->count(),
            'excellent'       => User::where('school_id', $school->id)->role('student')
                                    ->whereHas('studentProfile', fn($q) => $q->where('academic_status', 'excellent'))
                                    ->count(),
        ];

        $atRiskStudents = User::where('school_id', $school->id)
            ->role('student')
            ->whereHas('studentProfile', fn($q) => $q->whereIn('academic_status', ['at_risk', 'needs_support']))
            ->with(['studentProfile.academicLevel', 'classrooms'])
            ->latest()
            ->take(8)
            ->get();

        return view('counselor.dashboard', compact('stats', 'atRiskStudents'));
    }
}