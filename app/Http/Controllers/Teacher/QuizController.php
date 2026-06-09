<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuestionOption;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index(Request $request)
    {
        $teacher = Auth::user();

        $quizzes = Quiz::where('teacher_id', $teacher->id)
            ->with(['subject', 'lesson'])
            ->withCount('questions')
            ->withCount('attempts')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        $teacher  = Auth::user();
        $subjects = $teacher->teachingSubjects()->get();

        return view('teacher.quizzes.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lesson_id'                => 'nullable|exists:lessons,id',
            'unit_id'                  => 'nullable|exists:units,id',
            'subject_id'               => 'nullable|exists:subjects,id',
            'title'                    => 'required|string|max:200',
            'description'              => 'nullable|string',
            'instructions'             => 'nullable|string',
            'type'                     => 'required|in:lesson_quiz,unit_test,midterm,final,practice',
            'total_marks'              => 'required|integer|min:1',
            'pass_marks'               => 'required|integer|min:1',
            'duration_minutes'         => 'nullable|integer|min:1',
            'max_attempts'             => 'required|integer|min:1',
            'shuffle_questions'        => 'boolean',
            'shuffle_options'          => 'boolean',
            'show_results_immediately' => 'boolean',
            'show_correct_answers'     => 'boolean',
            'available_from'           => 'nullable|date',
            'available_until'          => 'nullable|date|after_or_equal:available_from',
        ]);

        $data['teacher_id'] = Auth::id();
        $data['status']     = $request->input('status', 'draft');

        $quiz = Quiz::create($data);

        return redirect()->route('teacher.quizzes.edit', $quiz)
            ->with('success', 'تم إنشاء الاختبار، أضف الأسئلة الآن ✅');
    }

    public function edit(Quiz $quiz)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);
        $quiz->load(['questions.options', 'subject']);

        return view('teacher.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'title'                    => 'required|string|max:200',
            'description'              => 'nullable|string',
            'instructions'             => 'nullable|string',
            'total_marks'              => 'required|integer|min:1',
            'pass_marks'               => 'required|integer|min:1',
            'duration_minutes'         => 'nullable|integer|min:1',
            'max_attempts'             => 'required|integer|min:1',
            'shuffle_questions'        => 'boolean',
            'shuffle_options'          => 'boolean',
            'show_results_immediately' => 'boolean',
            'show_correct_answers'     => 'boolean',
            'available_from'           => 'nullable|date',
            'available_until'          => 'nullable|date',
        ]);

        $data['status'] = $request->input('status', $quiz->status);
        $quiz->update($data);

        return back()->with('success', __('app.save') . ' ✅');
    }

    public function togglePublish(Quiz $quiz)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);

        $quiz->update([
            'status' => $quiz->status === 'published' ? 'draft' : 'published'
        ]);

        return back()->with('success', 'تم تحديث حالة النشر ✅');
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'question_text'   => 'required|string',
            'type'            => 'required|in:mcq,true_false,fill_blank,short_answer',
            'marks'           => 'required|integer|min:1',
            'explanation'     => 'nullable|string',
            'question_image'  => 'nullable|image|max:2048',
            'options'         => 'required_if:type,mcq,true_false|array|min:2',
            'options.*.text'  => 'required|string',
            'correct_option'  => 'required_if:type,mcq,true_false|integer',
            'correct_answer'  => 'required_if:type,fill_blank|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('quizzes/questions', 'public');
        }

        $question = QuizQuestion::create([
            'quiz_id'        => $quiz->id,
            'question_text'  => $data['question_text'],
            'type'           => $data['type'],
            'marks'          => $data['marks'],
            'explanation'    => $data['explanation'] ?? null,
            'question_image' => $imagePath,
            'order'          => $quiz->questions()->count(),
        ]);

        if (in_array($data['type'], ['mcq', 'true_false'])) {
            foreach ($request->input('options', []) as $i => $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct'  => ($i == $request->input('correct_option')),
                    'order'       => $i,
                ]);
            }
        } elseif ($data['type'] === 'fill_blank') {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $data['correct_answer'],
                'is_correct'  => true,
                'order'       => 0,
            ]);
        }

        return back()->with('success', 'تم إضافة السؤال ✅');
    }

    public function destroyQuestion(Quiz $quiz, QuizQuestion $q)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);
        $q->options()->delete();
        $q->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }

    public function destroy(Quiz $quiz)
    {
        abort_if($quiz->teacher_id !== Auth::id(), 403);
        $quiz->delete();

        return redirect()->route('teacher.quizzes.index')
            ->with('success', __('app.delete') . ' ✅');
    }
}