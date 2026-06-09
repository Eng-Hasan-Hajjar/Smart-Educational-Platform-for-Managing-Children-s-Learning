<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index(Request $request)
    {
        $teacher = Auth::user();

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'classroom'])
            ->withCount('submissions')
            ->withCount(['submissions as graded_count' => fn($q) => $q->where('status', 'graded')])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $teacher    = Auth::user();
        $subjects   = $teacher->teachingSubjects()->get();
        $classrooms = Classroom::whereHas('teachers', fn($q) => $q->where('users.id', $teacher->id))
            ->get();

        return view('teacher.assignments.create', compact('subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lesson_id'              => 'nullable|exists:lessons,id',
            'subject_id'             => 'required|exists:subjects,id',
            'classroom_id'           => 'required|exists:classrooms,id',
            'title'                  => 'required|string|max:200',
            'description'            => 'required|string',
            'instructions'           => 'nullable|string',
            'total_marks'            => 'required|integer|min:1',
            'due_date'               => 'required|date|after:now',
            'submission_type'        => 'required|in:text,file,both',
            'allow_late_submission'  => 'boolean',
            'late_penalty_percent'   => 'nullable|integer|min:0|max:100',
            'max_file_size_mb'       => 'nullable|integer|min:1|max:100',
            'attachment'             => 'nullable|file|max:20480',
        ]);

        $data['teacher_id'] = Auth::id();
        $data['status']     = $request->input('status', 'draft');

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('assignments/attachments', 'public');
        }

        Assignment::create($data);

        return redirect()->route('teacher.assignments.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(Assignment $assignment)
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $teacher    = Auth::user();
        $subjects   = $teacher->teachingSubjects()->get();
        $classrooms = Classroom::whereHas('teachers', fn($q) => $q->where('users.id', $teacher->id))->get();

        return view('teacher.assignments.edit', compact('assignment', 'subjects', 'classrooms'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'title'                 => 'required|string|max:200',
            'description'           => 'required|string',
            'instructions'          => 'nullable|string',
            'total_marks'           => 'required|integer|min:1',
            'due_date'              => 'required|date',
            'allow_late_submission' => 'boolean',
            'late_penalty_percent'  => 'nullable|integer|min:0|max:100',
            'attachment'            => 'nullable|file|max:20480',
            'status'                => 'required|in:draft,published,closed',
        ]);

        if ($request->hasFile('attachment')) {
            if ($assignment->attachment) Storage::disk('public')->delete($assignment->attachment);
            $data['attachment'] = $request->file('attachment')->store('assignments/attachments', 'public');
        }

        $assignment->update($data);

        return redirect()->route('teacher.assignments.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function submissions(Assignment $assignment)
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->with('student')
            ->latest()
            ->paginate(20);

        return view('teacher.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'marks_obtained'   => 'required|numeric|min:0|max:' . $assignment->total_marks,
            'teacher_feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'marks_obtained'   => $data['marks_obtained'],
            'teacher_feedback' => $data['teacher_feedback'] ?? null,
            'graded_at'        => now(),
            'graded_by'        => Auth::id(),
            'status'           => 'graded',
        ]);

        // إشعار الطالب
        $submission->student->notify(
            new \App\Notifications\AssignmentGradedNotification($submission)
        );

        return back()->with('success', 'تم التصحيح ✅');
    }

    public function destroy(Assignment $assignment)
    {
        abort_if($assignment->teacher_id !== Auth::id(), 403);
        $assignment->delete();

        return redirect()->route('teacher.assignments.index')
            ->with('success', __('app.delete') . ' ✅');
    }
}