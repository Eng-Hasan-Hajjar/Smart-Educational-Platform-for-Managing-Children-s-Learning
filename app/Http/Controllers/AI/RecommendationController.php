<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\{User, AiRecommendation, LearningPath, StudentAnalytic, Lesson, Quiz, LessonProgress};
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * توليد توصيات (ai_recommendations) + مسار تعلّم (learning_paths) لطالب بعينه
     * يُستدعى من الموجه التربوي أو المعلم أو مسارات /ai/recommend
     */
    public function generate(User $student)
    {
        abort_unless($student->hasRole('student'), 404);

        // الطالب نفسه يُسمح له بإنشاء توصياته فقط
        if (Auth::user()->hasRole('student')) {
            abort_unless(Auth::id() === $student->id, 403);
        }

        $analytics = StudentAnalytic::where('student_id', $student->id)
            ->whereNotNull('subject_id')
            ->get();

        $createdCount = 0;

        foreach ($analytics as $analytic) {
            $createdCount += $this->generateForSubjectAnalytic($student, $analytic);
        }

        return back()->with('success', __('app.ai_recommendations_generated', ['count' => $createdCount]));
    }

    /**
     * عرض توصيات الطالب الحالي (الطالب نفسه)
     */
    public function index()
    {
        $student = Auth::user();
        abort_unless($student->hasRole('student'), 403);

        $recommendations = AiRecommendation::where('student_id', $student->id)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->with('recommendable')
            ->latest()
            ->paginate(15);

        $learningPaths = LearningPath::where('student_id', $student->id)
            ->with('subject')
            ->get();

        return view('student.recommendations.index', compact('recommendations', 'learningPaths'));
    }

    /**
     * توليد توصيات + مسار تعلّم لمادة واحدة بناءً على صف student_analytics
     */
    private function generateForSubjectAnalytic(User $student, StudentAnalytic $analytic): int
    {
        $count = 0;
        $subjectId = $analytic->subject_id;

        // ── 1. تحذير (warning) عند الخطورة العالية ──
        if ($analytic->risk_level === 'high') {
            $targetLesson = $this->nextUnfinishedLesson($student, $subjectId);
            if ($targetLesson) {
                AiRecommendation::updateOrCreate(
                    [
                        'student_id'          => $student->id,
                        'type'                => 'warning',
                        'recommendable_type'  => Lesson::class,
                        'recommendable_id'    => $targetLesson->id,
                    ],
                    [
                        'reason'           => __('app.ai_reason_high_risk'),
                        'confidence_score' => 0.90,
                        'expires_at'       => now()->addDays(14),
                    ]
                );
                $count++;
            }
        }

        // ── 2. ثناء (praise) عند الأداء الممتاز ──
        if ($analytic->average_score >= 90 && $analytic->attendance_rate >= 90) {
            $targetLesson = $this->nextUnfinishedLesson($student, $subjectId)
                ?? Lesson::whereHas('unit', fn($q) => $q->where('subject_id', $subjectId))->first();

            if ($targetLesson) {
                AiRecommendation::updateOrCreate(
                    [
                        'student_id'          => $student->id,
                        'type'                => 'praise',
                        'recommendable_type'  => Lesson::class,
                        'recommendable_id'    => $targetLesson->id,
                    ],
                    [
                        'reason'           => __('app.ai_reason_excellent'),
                        'confidence_score' => 0.95,
                        'expires_at'       => now()->addDays(7),
                    ]
                );
                $count++;
            }
        }

        // ── 3. مراجعة (review) لنقاط الضعف ──
        foreach (($analytic->weak_areas ?? []) as $weakUnitTitle) {
            $weakLesson = Lesson::whereHas('unit', function ($q) use ($subjectId, $weakUnitTitle) {
                    $q->where('subject_id', $subjectId)->where('title', $weakUnitTitle);
                })
                ->where('status', 'published')
                ->first();

            if ($weakLesson) {
                AiRecommendation::updateOrCreate(
                    [
                        'student_id'          => $student->id,
                        'type'                => 'review',
                        'recommendable_type'  => Lesson::class,
                        'recommendable_id'    => $weakLesson->id,
                    ],
                    [
                        'reason'           => __('app.ai_reason_weak_area', ['unit' => $weakUnitTitle]),
                        'confidence_score' => 0.80,
                        'expires_at'       => now()->addDays(21),
                    ]
                );
                $count++;
            }

            // اختبار مرتبط للمراجعة أيضاً
            $weakQuiz = Quiz::where('subject_id', $subjectId)
                ->whereHas('lesson.unit', fn($q) => $q->where('title', $weakUnitTitle))
                ->where('status', 'published')
                ->first();

            if ($weakQuiz) {
                AiRecommendation::updateOrCreate(
                    [
                        'student_id'          => $student->id,
                        'type'                => 'quiz',
                        'recommendable_type'  => Quiz::class,
                        'recommendable_id'    => $weakQuiz->id,
                    ],
                    [
                        'reason'           => __('app.ai_reason_practice_quiz', ['unit' => $weakUnitTitle]),
                        'confidence_score' => 0.75,
                        'expires_at'       => now()->addDays(14),
                    ]
                );
                $count++;
            }
        }

        // ── 4. الدرس التالي المنطقي (lesson) ──
        $nextLesson = $this->nextUnfinishedLesson($student, $subjectId);
        if ($nextLesson) {
            AiRecommendation::updateOrCreate(
                [
                    'student_id'          => $student->id,
                    'type'                => 'lesson',
                    'recommendable_type'  => Lesson::class,
                    'recommendable_id'    => $nextLesson->id,
                ],
                [
                    'reason'           => __('app.ai_reason_continue_learning'),
                    'confidence_score' => 0.70,
                    'expires_at'       => now()->addDays(10),
                ]
            );
            $count++;
        }

        // ── 5. بناء/تحديث مسار التعلّم (learning_paths) ──
        if ($subjectId) {
            $this->buildLearningPath($student, $subjectId, $analytic);
        }

        return $count;
    }

    /**
     * يبني قائمة steps مرتبة (دروس غير مكتملة أولاً مرتّبة بالـ order، ثم اختبارات الوحدات الضعيفة)
     */
    private function buildLearningPath(User $student, int $subjectId, StudentAnalytic $analytic): void
    {
        $completedLessonIds = LessonProgress::where('student_id', $student->id)
            ->where('is_completed', true)
            ->pluck('lesson_id');

        $remainingLessons = Lesson::whereHas('unit', fn($q) => $q->where('subject_id', $subjectId))
            ->where('status', 'published')
            ->whereNotIn('id', $completedLessonIds)
            ->orderBy('order')
            ->get();

        $steps = $remainingLessons->map(fn($lesson) => [
            'type' => 'lesson',
            'id'   => $lesson->id,
            'title'=> $lesson->title,
        ])->toArray();

        // إدراج اختبارات مراجعة لنقاط الضعف في بداية المسار (أولوية أعلى)
        $reviewQuizzes = Quiz::where('subject_id', $subjectId)
            ->where('status', 'published')
            ->whereHas('lesson.unit', fn($q) => $q->whereIn('title', $analytic->weak_areas ?? []))
            ->get()
            ->map(fn($quiz) => [
                'type'  => 'quiz',
                'id'    => $quiz->id,
                'title' => $quiz->title,
            ])->toArray();

        $steps = array_merge($reviewQuizzes, $steps);

        if (empty($steps)) {
            return;
        }

        LearningPath::updateOrCreate(
            ['student_id' => $student->id, 'subject_id' => $subjectId],
            [
                'steps'             => $steps,
                'current_step'      => 0,
                'progress'          => 0,
                'is_completed'      => false,
                'algorithm_version' => 'v1',
                'generated_at'      => now(),
                'completed_at'      => null,
            ]
        );
    }

    private function nextUnfinishedLesson(User $student, ?int $subjectId)
    {
        $completedLessonIds = LessonProgress::where('student_id', $student->id)
            ->where('is_completed', true)
            ->pluck('lesson_id');

        return Lesson::when($subjectId, fn($q) => $q->whereHas('unit', fn($u) => $u->where('subject_id', $subjectId)))
            ->where('status', 'published')
            ->whereNotIn('id', $completedLessonIds)
            ->orderBy('order')
            ->first();
    }
}