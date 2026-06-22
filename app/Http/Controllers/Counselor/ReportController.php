<?php
namespace App\Http\Controllers\Counselor;
use App\Http\Controllers\Controller;
use App\Models\{PerformanceReport, User, Semester, StudentAnalytic};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:counselor']); }

    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $reports = PerformanceReport::whereHas('student', fn($q) => $q->where('school_id',$school->id))
            ->where('generated_by', Auth::id())
            ->with(['student','semester.academicYear'])
            ->when($request->search, fn($q) => $q->whereHas('student', fn($s) => $s->where('name','like',"%{$request->search}%")))
            ->latest()->paginate(15)->withQueryString();

        return view('counselor.reports.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $school = Auth::user()->school;
        $students = User::where('school_id',$school->id)->role('student')->orderBy('name')->get();
        $semesters = Semester::whereHas('academicYear', fn($q) => $q->where('school_id',$school->id))->orderByDesc('id')->get();

        $selectedStudent = null; $subjectsData = [];
        if ($request->student_id) {
            $selectedStudent = User::find($request->student_id);
            $subjectsData = StudentAnalytic::where('student_id',$request->student_id)->whereNotNull('subject_id')
                ->with('subject')->get()->map(fn($a) => ['subject'=>$a->subject->name??'—','average_score'=>$a->average_score])->toArray();
        }

        return view('counselor.reports.create', compact('students','semesters','selectedStudent','subjectsData'));
    }

    public function store(Request $request)
    {
        // Re-send existing report to parent
        if ($request->has('report_id')) {
            $report = PerformanceReport::findOrFail($request->report_id);
            $report->update(['is_sent_to_parent'=>true,'sent_at'=>now()]);
            return redirect()->route('counselor.reports.show',$report)->with('success',__('counselor.report_sent_success'));
        }

        $data = $request->validate([
            'student_id'      => 'required|exists:users,id',
            'semester_id'     => 'required|exists:semesters,id',
            'type'            => 'required|in:weekly,monthly,semester,annual,counselor',
            'counselor_notes' => 'required|string',
            'recommendations' => 'nullable|string',
            'send_to_parent'  => 'boolean',
        ]);

        $subjectsData = StudentAnalytic::where('student_id',$data['student_id'])->whereNotNull('subject_id')
            ->with('subject')->get()->map(fn($a) => ['subject'=>$a->subject->name??'—','average_score'=>$a->average_score])->toArray();

        $report = PerformanceReport::create([
            'student_id'       => $data['student_id'],
            'semester_id'      => $data['semester_id'],
            'generated_by'     => Auth::id(),
            'type'             => $data['type'],
            'subjects_data'    => $subjectsData,
            'counselor_notes'  => $data['counselor_notes'],
            'recommendations'  => $data['recommendations'] ?? null,
            'is_sent_to_parent'=> $request->boolean('send_to_parent'),
            'sent_at'          => $request->boolean('send_to_parent') ? now() : null,
        ]);

        return redirect()->route('counselor.reports.show',$report)->with('success',__('counselor.report_created_success'));
    }

    public function show(PerformanceReport $report)
    {
        abort_unless($report->student->school_id === Auth::user()->school_id, 403);
        $report->load(['student','semester.academicYear','generatedBy']);
        return view('counselor.reports.show', compact('report'));
    }
}
