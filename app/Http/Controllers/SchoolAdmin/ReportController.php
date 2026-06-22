<?php
namespace App\Http\Controllers\SchoolAdmin;
use App\Http\Controllers\Controller;
use App\Models\{User, Classroom};
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:school_admin']); }

    public function index()
    {
        $school = Auth::user()->school;
        $classrooms = Classroom::where('school_id',$school->id)->with('academicLevel')->withCount('students')->get();

        $atRiskStudents = User::where('school_id',$school->id)->role('student')
            ->whereHas('studentProfile', fn($q) => $q->whereIn('academic_status',['at_risk','needs_support']))
            ->with(['studentProfile','classrooms'])->take(10)->get();

        $topStudents = User::where('school_id',$school->id)->role('student')
            ->whereHas('studentProfile', fn($q) => $q->where('academic_status','excellent'))
            ->with('studentProfile')->take(10)->get();

        return view('school-admin.reports.index', compact('classrooms','atRiskStudents','topStudents'));
    }
}
