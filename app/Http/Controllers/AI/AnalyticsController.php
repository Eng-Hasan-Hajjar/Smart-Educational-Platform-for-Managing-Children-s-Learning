<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\{User, StudentAnalytic, Subject, Attendance, LessonProgress, QuizAttempt, AssignmentSubmission, School};
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin|school_admin|counselor|teacher']);
    }

    /**
     * تحديث تحليلات كل طلاب مدرسة بعينها (Super Admin / School Admin)
     */
    public function refreshSchool(School $school)
    {
        $students = User::where('school_id', $school->id)->role('student')->get();

        foreach ($students as $student) {
            $this->analyzeStudentAcrossSubjects($student);
        }

        return back()->with('success', __('app.ai_school_refresh_success', ['count' => $students->count()]));
    }

    /**
     * تحديث تحليلات طالب واحد بعينه (Counselor / Teacher)
     */
    public function refreshForStudent(User $student)
    {
        abort_unless($student->hasRole('student'), 404);

        $rows = $this->analyzeStudentAcrossSubjects($student);

        return back()->with('success', __('app.ai_student_refresh_success', ['name' => $student->name]));
    }

    /**
     * المنطق المحلي (rule-based) لحساب مؤشرات الطالب لكل مادة يدرسها + صف عام (subject_id = null)
     */
    private function analyzeStudentAcrossSubjects(User $student): array
    {
        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        $subjectIds = Subject::whereIn('academic_level_id', function ($q) use ($student) {
                $q->select('academic_level_id')
                  ->from('classrooms')
                  ->whereIn('id', $student->classrooms()->pluck('classrooms.id'));
            })
            ->pluck('id');

        $results = [];

        // ─── تحليل لكل مادة على حدة ───
        foreach ($subjectIds as $subjectId) {
            $results[] = $this->buildAnalyticRow($student, $subjectId, $classroomIds);
        }

        // ─── تحليل عام (subject_id = null) يجمع كل المواد ───
        $results[] = $this->buildAnalyticRow($student, null, $classroomIds);

        return $results;
    }

    private function buildAnalyticRow(User $student, ?int $subjectId, $classroomIds): StudentAnalytic
    {
        // ── نسبة الحضور ──
        $attQuery = Attendance::where('student_id', $student->id);
        $totalAtt   = $attQuery->count();
        $presentAtt = (clone $attQuery)->where('status', 'present')->count();
        $attendanceRate = $totalAtt > 0 ? round($presentAtt / $totalAtt * 100, 2) : 0;

        // ── تقدم الدروس (مفلتر بالمادة إن وُجدت) ──
        $progressQuery = LessonProgress::where('student_id', $student->id)
            ->when($subjectId, fn($q) => $q->whereHas('lesson.unit', fn($u) => $u->where('subject_id', $subjectId)));

        $lessonsCompleted = (clone $progressQuery)->where('is_completed', true)->count();
        $totalLessons     = (clone $progressQuery)->count();
        $completionRate   = $totalLessons > 0 ? round($lessonsCompleted / $totalLessons * 100, 2) : 0;
        $timeSpentMinutes = (int) round((clone $progressQuery)->sum('time_spent_seconds') / 60);

        // ── الاختبارات ──
        $quizQuery = QuizAttempt::where('student_id', $student->id)
            ->where('status', 'graded')
            ->when($subjectId, fn($q) => $q->whereHas('quiz', fn($s) => $s->where('subject_id', $subjectId)));

        $quizzesTaken = (clone $quizQuery)->count();
        $avgQuizScore = (clone $quizQuery)->avg('percentage') ?? 0;

        // ── الواجبات ──
        $assignmentsSubmitted = AssignmentSubmission::where('student_id', $student->id)
            ->when($subjectId, fn($q) => $q->whereHas('assignment', fn($s) => $s->where('subject_id', $subjectId)))
            ->count();

        $averageScore = round($avgQuizScore, 2);

        // ── النشاط الأسبوعي (آخر 7 أيام) ──
        $weeklyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $weeklyActivity[$date] = LessonProgress::where('student_id', $student->id)
                ->whereDate('updated_at', $date)
                ->count();
        }

        // ── نقاط القوة والضعف (حسب أداء الاختبارات لكل وحدة/موضوع) ──
        $quizByUnit = (clone $quizQuery)
            ->with('quiz.lesson.unit')
            ->get()
            ->groupBy(fn($attempt) => $attempt->quiz->lesson?->unit?->title ?? __('app.general'));

        $strengthAreas = [];
        $weakAreas     = [];
        foreach ($quizByUnit as $unitTitle => $attempts) {
            $avg = $attempts->avg('percentage');
            if ($avg >= 80) {
                $strengthAreas[] = $unitTitle;
            } elseif ($avg < 60) {
                $weakAreas[] = $unitTitle;
            }
        }

        // ── وتيرة التعلم (حسب متوسط الوقت بالنسبة لعدد الدروس) ──
        $learningPace = 'average';
        if ($totalLessons > 0) {
            $avgMinutesPerLesson = $timeSpentMinutes / max($lessonsCompleted, 1);
            $learningPace = $avgMinutesPerLesson < 10 ? 'fast' : ($avgMinutesPerLesson > 25 ? 'slow' : 'average');
        }

        // ── مستوى الخطورة (Risk Level) ──
        $riskLevel = 'low';
        if ($attendanceRate < 60 || $averageScore < 50) {
            $riskLevel = 'high';
        } elseif ($attendanceRate < 80 || $averageScore < 70) {
            $riskLevel = 'medium';
        }

        return StudentAnalytic::updateOrCreate(
            ['student_id' => $student->id, 'subject_id' => $subjectId],
            [
                'average_score'             => $averageScore,
                'attendance_rate'           => $attendanceRate,
                'completion_rate'           => $completionRate,
                'total_time_spent_minutes'  => $timeSpentMinutes,
                'lessons_completed'         => $lessonsCompleted,
                'quizzes_taken'             => $quizzesTaken,
                'assignments_submitted'     => $assignmentsSubmitted,
                'weekly_activity'           => $weeklyActivity,
                'strength_areas'            => $strengthAreas,
                'weak_areas'                => $weakAreas,
                'learning_pace'             => $learningPace,
                'risk_level'                => $riskLevel,
                'last_analyzed_at'          => now(),
            ]
        );
    }
}