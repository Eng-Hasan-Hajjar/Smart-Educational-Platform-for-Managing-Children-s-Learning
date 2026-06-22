<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Assignment, AssignmentSubmission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:student']); }

    public function index(Request $request)
    {
        $student = Auth::user();
        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        $assignments = Assignment::whereIn('classroom_id', $classroomIds)
            ->where('status', 'published')
            ->with('subject')
            ->when($request->status, function($q) use ($request, $student) {
                if ($request->status === 'pending') $q->whereDoesntHave('submissions', fn($s) => $s->where('student_id', $student->id));
                elseif ($request->status === 'submitted') $q->whereHas('submissions', fn($s) => $s->where('student_id', $student->id)->where('status','submitted'));
                elseif ($request->status === 'graded') $q->whereHas('submissions', fn($s) => $s->where('student_id', $student->id)->where('status','graded'));
                elseif ($request->status === 'overdue') $q->where('due_date','<',now())->whereDoesntHave('submissions', fn($s) => $s->where('student_id', $student->id));
            })
            ->orderByDesc('due_date')
            ->paginate(15)->withQueryString();

        $submissionsMap = AssignmentSubmission::where('student_id', $student->id)
            ->whereIn('assignment_id', $assignments->pluck('id'))->pluck('status','assignment_id')->toArray();

        return view('student.assignments.index', compact('assignments', 'submissionsMap'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = Auth::user();
        abort_if(AssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', $student->id)->exists(), 403);

        $data = $request->validate([
            'text_answer' => 'nullable|string',
            'file' => 'nullable|file|max:' . ($assignment->max_file_size_mb * 1024),
        ]);

        $filePath = $fileName = $fileSize = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('submissions', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
        }

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id, 'student_id' => $student->id,
            'text_answer' => $data['text_answer'] ?? null,
            'file_path' => $filePath, 'file_name' => $fileName, 'file_size' => $fileSize,
            'is_late' => $assignment->isOverdue(),
        ]);

        return back()->with('success', __('student.assignment_submitted_success'));
    }
}
