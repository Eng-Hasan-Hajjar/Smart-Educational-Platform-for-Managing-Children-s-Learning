<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Subject, Lesson, LessonProgress};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:student']); }

    public function index(Request $request)
    {
        $student = Auth::user();
        $classroomIds = $student->classrooms()->pluck('classrooms.id');
        $levelIds = \App\Models\Classroom::whereIn('id', $classroomIds)->pluck('academic_level_id');

        $subjects = Subject::whereIn('academic_level_id', $levelIds)
            ->where('is_active', true)
            ->with(['units' => fn($q) => $q->orderBy('order'), 'units.publishedLessons' => fn($q) => $q->orderBy('order')])
            ->when($request->subject_id, fn($q) => $q->where('id', $request->subject_id))
            ->when($request->search, fn($q) => $q->whereHas('units.lessons', fn($l) => $l->where('title', 'like', "%{$request->search}%")))
            ->get();

        $progressMap = LessonProgress::where('student_id', $student->id)->pluck('progress_percentage', 'lesson_id')->toArray();

        return view('student.lessons.index', compact('subjects', 'progressMap'));
    }

    public function show(Lesson $lesson)
    {
        $student = Auth::user();
        $lesson->load(['contents' => fn($q) => $q->orderBy('order'), 'audioResources', 'quizzes' => fn($q) => $q->where('status','published')]);
        $lesson->increment('view_count');

        $progress = LessonProgress::firstOrCreate(
            ['student_id' => $student->id, 'lesson_id' => $lesson->id],
            ['started_at' => now(), 'progress_percentage' => 0]
        );

        $unitLessons = Lesson::where('unit_id', $lesson->unit_id)->where('status','published')->orderBy('order')->get();
        $currentIndex = $unitLessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $unitLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $unitLessons->count() - 1 ? $unitLessons[$currentIndex + 1] : null;

        return view('student.lessons.show', compact('lesson', 'progress', 'prevLesson', 'nextLesson'));
    }

    public function updateProgress(Request $request, Lesson $lesson)
    {
        $data = $request->validate(['progress_percentage' => 'required|integer|min:0|max:100', 'time_spent_seconds' => 'integer|min:0']);
        $progress = LessonProgress::where('student_id', Auth::id())->where('lesson_id', $lesson->id)->first();
        if (!$progress) return response()->json(['error' => 'Not found'], 404);

        $updates = ['progress_percentage' => max($progress->progress_percentage, $data['progress_percentage'])];
        if (isset($data['time_spent_seconds'])) $updates['time_spent_seconds'] = $progress->time_spent_seconds + $data['time_spent_seconds'];
        if ($updates['progress_percentage'] >= 100 && !$progress->is_completed) {
            $updates['is_completed'] = true;
            $updates['completed_at'] = now();
        }
        $progress->update($updates);
        return response()->json(['progress_percentage' => $progress->progress_percentage, 'is_completed' => $progress->is_completed]);
    }
}
