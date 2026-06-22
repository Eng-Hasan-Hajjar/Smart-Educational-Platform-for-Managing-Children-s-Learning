<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Quiz, QuizAttempt, StudentQuizAnswer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:student']); }

    public function show(Quiz $quiz)
    {
        abort_unless($quiz->isAvailable(), 404);
        $student = Auth::user();
        $existingAttempts = QuizAttempt::where('student_id', $student->id)->where('quiz_id', $quiz->id)->count();
        abort_if($existingAttempts >= $quiz->max_attempts, 403, __('student.max_attempts_reached'));

        $attempt = QuizAttempt::create([
            'student_id' => $student->id, 'quiz_id' => $quiz->id,
            'attempt_number' => $existingAttempts + 1, 'started_at' => now(), 'status' => 'in_progress',
        ]);
        $questions = $quiz->shuffle_questions ? $quiz->questions->shuffle() : $quiz->questions;

        return view('student.quizzes.show', compact('quiz', 'questions', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $student = Auth::user();
        $attempt = QuizAttempt::where('student_id', $student->id)->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')->latest()->firstOrFail();

        $totalMarks = 0;
        foreach ($quiz->questions as $question) {
            $selectedOptionId = $request->input("answers.{$question->id}");
            $textAnswer = $request->input("text_answers.{$question->id}");

            $isCorrect = false; $marks = 0;
            if ($selectedOptionId) {
                $correct = $question->correctOption;
                $isCorrect = $correct && $correct->id == $selectedOptionId;
                $marks = $isCorrect ? $question->marks : 0;
            }

            StudentQuizAnswer::updateOrCreate(
                ['student_id' => $student->id, 'quiz_id' => $quiz->id, 'question_id' => $question->id],
                ['selected_option_id' => $selectedOptionId, 'text_answer' => $textAnswer, 'is_correct' => $isCorrect, 'marks_obtained' => $marks, 'attempt_number' => $attempt->attempt_number]
            );
            $totalMarks += $marks;
        }

        $percentage = $quiz->total_marks > 0 ? round($totalMarks / $quiz->total_marks * 100, 2) : 0;
        $attempt->update([
            'total_marks_obtained' => $totalMarks, 'percentage' => $percentage,
            'is_passed' => $totalMarks >= $quiz->pass_marks, 'submitted_at' => now(),
            'time_taken_seconds' => now()->diffInSeconds($attempt->started_at), 'status' => 'graded',
        ]);

        return redirect()->route('student.quizzes.result', [$quiz, $attempt]);
    }

    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($attempt->student_id === Auth::id(), 403);
        $answers = StudentQuizAnswer::where('student_id', Auth::id())->where('quiz_id', $quiz->id)
            ->where('attempt_number', $attempt->attempt_number)->with('selectedOption')->get()->keyBy('question_id');
        $quiz->load('questions.options', 'questions.correctOption');

        return view('student.quizzes.result', compact('quiz', 'attempt', 'answers'));
    }
}
