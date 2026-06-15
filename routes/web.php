<?php

use Illuminate\Support\Facades\Route;

// ════════════════════════════════════════════════════════════
//  AUTH ROUTES
// ════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login',                  [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',                 [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password',        [\App\Http\Controllers\Auth\AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password',       [\App\Http\Controllers\Auth\AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [\App\Http\Controllers\Auth\AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::get('/', fn () => redirect()->route('login'));

// ════════════════════════════════════════════════════════════
//  LANGUAGE SWITCHER (متاح لكل الزوار والمستخدمين)
// ════════════════════════════════════════════════════════════
Route::get('/language/{locale}', [\App\Http\Controllers\Shared\LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'ar|en');


// ════════════════════════════════════════════════════════════
//  SUPER ADMIN ROUTES — prefix: /admin
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('schools', \App\Http\Controllers\Admin\SchoolController::class);
    Route::resource('users',   \App\Http\Controllers\Admin\UserController::class);

    // إحصاءات وتحليلات النظام الكلي
    Route::get('/analytics', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('analytics');

    // تشغيل تحديث AI لمدرسة كاملة
    Route::post('/ai/refresh-school/{school}', [\App\Http\Controllers\AI\AnalyticsController::class, 'refreshSchool'])->name('ai.refresh-school');
});


// ════════════════════════════════════════════════════════════
//  SCHOOL ADMIN ROUTES — prefix: /school
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:school_admin'])->prefix('school')->name('school.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\SchoolAdmin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('teachers',   \App\Http\Controllers\SchoolAdmin\TeacherController::class);
    Route::resource('students',   \App\Http\Controllers\SchoolAdmin\StudentController::class);
    Route::resource('classrooms', \App\Http\Controllers\SchoolAdmin\ClassroomController::class);
    Route::resource('schedules',  \App\Http\Controllers\SchoolAdmin\ScheduleController::class)->except(['show', 'edit', 'update']);
    Route::resource('subjects',   \App\Http\Controllers\SchoolAdmin\SubjectController::class);

    Route::get('/reports', [\App\Http\Controllers\SchoolAdmin\ReportController::class, 'index'])->name('reports.index');

    // تعيين معلم لمادة + فصل
    Route::post('/teachers/{teacher}/assign', [\App\Http\Controllers\SchoolAdmin\TeacherController::class, 'assign'])->name('teachers.assign');

    // تفعيل/تعطيل حساب طالب
    Route::patch('/students/{student}/toggle', [\App\Http\Controllers\SchoolAdmin\StudentController::class, 'toggleStatus'])->name('students.toggle');

    // تحديث تحليلات الذكاء الاصطناعي لكل طلاب المدرسة
    Route::post('/ai/refresh', [\App\Http\Controllers\AI\AnalyticsController::class, 'refreshSchool'])->name('ai.refresh');
});


// ════════════════════════════════════════════════════════════
//  COUNSELOR ROUTES — prefix: /counselor
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:counselor'])->prefix('counselor')->name('counselor.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Counselor\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/students',           [\App\Http\Controllers\Counselor\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [\App\Http\Controllers\Counselor\StudentController::class, 'show'])->name('students.show');

    Route::get('/reports',           [\App\Http\Controllers\Counselor\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create',    [\App\Http\Controllers\Counselor\ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports',          [\App\Http\Controllers\Counselor\ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}',  [\App\Http\Controllers\Counselor\ReportController::class, 'show'])->name('reports.show');

    // إنشاء توصيات AI لطالب معين
    Route::post('/ai/recommend/{student}', [\App\Http\Controllers\AI\RecommendationController::class, 'generate'])->name('ai.recommend');
    Route::post('/ai/analyze/{student}',   [\App\Http\Controllers\AI\AnalyticsController::class, 'refreshForStudent'])->name('ai.analyze');
});


// ════════════════════════════════════════════════════════════
//  TEACHER ROUTES — prefix: /teacher
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // الوحدات التعليمية
    Route::resource('units', \App\Http\Controllers\Teacher\UnitController::class);

    // الدروس
    Route::resource('lessons', \App\Http\Controllers\Teacher\LessonController::class);
    Route::post('/lessons/{lesson}/content',             [\App\Http\Controllers\Teacher\LessonController::class, 'uploadContent'])->name('lessons.content.upload');
    Route::delete('/lessons/{lesson}/content/{content}', [\App\Http\Controllers\Teacher\LessonController::class, 'deleteContent'])->name('lessons.content.delete');
    Route::patch('/lessons/{lesson}/publish',            [\App\Http\Controllers\Teacher\LessonController::class, 'togglePublish'])->name('lessons.publish');

    // الاختبارات
    Route::resource('quizzes', \App\Http\Controllers\Teacher\QuizController::class);
    Route::post('/quizzes/{quiz}/questions',       [\App\Http\Controllers\Teacher\QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::delete('/quizzes/{quiz}/questions/{q}', [\App\Http\Controllers\Teacher\QuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');
    Route::patch('/quizzes/{quiz}/publish',        [\App\Http\Controllers\Teacher\QuizController::class, 'togglePublish'])->name('quizzes.publish');

    // الواجبات
    Route::resource('assignments', \App\Http\Controllers\Teacher\AssignmentController::class);
    Route::get('/assignments/{assignment}/submissions',         [\App\Http\Controllers\Teacher\AssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::post('/assignments/{assignment}/grade/{submission}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'grade'])->name('assignments.grade');

    // الحضور والغياب
    Route::get('/attendance',        [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance',       [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [\App\Http\Controllers\Teacher\AttendanceController::class, 'report'])->name('attendance.report');

    // طلابي
    Route::get('/students',           [\App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'show'])->name('students.show');

    // التقارير
    Route::get('/reports',           [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{student}', [\App\Http\Controllers\Teacher\ReportController::class, 'student'])->name('reports.student');
});


// ════════════════════════════════════════════════════════════
//  PARENT ROUTES — prefix: /parent
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Parent\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/children',                    [\App\Http\Controllers\Parent\ChildController::class, 'index'])->name('children.index');
    Route::get('/children/{student}',          [\App\Http\Controllers\Parent\ChildController::class, 'show'])->name('children.show');
    Route::get('/children/{student}/schedule', [\App\Http\Controllers\Parent\ScheduleController::class, 'show'])->name('children.schedule');
    Route::get('/children/{student}/reports',  [\App\Http\Controllers\Parent\ReportController::class, 'show'])->name('children.reports');
});


// ════════════════════════════════════════════════════════════
//  STUDENT ROUTES — prefix: /student
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');

    // الدروس
    Route::get('/lessons',                    [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}',           [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/{lesson}/progress', [\App\Http\Controllers\Student\LessonController::class, 'updateProgress'])->name('lessons.progress');

    // الاختبارات
    Route::get('/quizzes/{quiz}',                  [\App\Http\Controllers\Student\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit',          [\App\Http\Controllers\Student\QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/result/{attempt}', [\App\Http\Controllers\Student\QuizController::class, 'result'])->name('quizzes.result');

    // الواجبات
    Route::get('/assignments',                      [\App\Http\Controllers\Student\QuizController::class, 'assignments'])->name('assignments.index');
    Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\Student\QuizController::class, 'submitAssignment'])->name('assignments.submit');

    // الإنجازات والشارات
    Route::get('/achievements',             [\App\Http\Controllers\Student\GamificationController::class, 'index'])->name('achievements.index');
    Route::patch('/badges/{badge}/feature', [\App\Http\Controllers\Student\GamificationController::class, 'toggleFeatureBadge'])->name('badges.feature');

    // توصيات الذكاء الاصطناعي
    Route::get('/recommendations', [\App\Http\Controllers\AI\RecommendationController::class, 'index'])->name('recommendations.index');
});


// ════════════════════════════════════════════════════════════
//  SHARED ROUTES (لكل الأدوار المسجَّلة)
// ════════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    // الإشعارات
    Route::get('/notifications',              [\App\Http\Controllers\Shared\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',   [\App\Http\Controllers\Shared\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',    [\App\Http\Controllers\Shared\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}',      [\App\Http\Controllers\Shared\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/count',        [\App\Http\Controllers\Shared\NotificationController::class, 'unreadCount'])->name('notifications.count');

    // الرسائل
    Route::get('/messages',                       [\App\Http\Controllers\Shared\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/compose',               [\App\Http\Controllers\Shared\MessageController::class, 'compose'])->name('messages.compose');
    Route::post('/messages',                      [\App\Http\Controllers\Shared\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}',        [\App\Http\Controllers\Shared\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}/reply', [\App\Http\Controllers\Shared\MessageController::class, 'sendReply'])->name('messages.reply');

    // الإعلانات
    Route::get('/announcements',                  [\App\Http\Controllers\Shared\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements',                 [\App\Http\Controllers\Shared\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::post('/announcements/{announcement}/read', [\App\Http\Controllers\Shared\AnnouncementController::class, 'markRead'])->name('announcements.read');

    // الملف الشخصي
    Route::get('/profile', [\App\Http\Controllers\Shared\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Shared\ProfileController::class, 'update'])->name('profile.update');

    // إعدادات الواجهة (Dark/Light mode)
    Route::post('/theme/{mode}', [\App\Http\Controllers\Shared\ThemeController::class, 'switch'])
        ->name('theme.switch')
        ->where('mode', 'light|dark');
});


// ════════════════════════════════════════════════════════════
//  AI ROUTES (مشتركة بين الأدوار الإدارية)
// ════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:super_admin|school_admin|counselor|teacher'])->prefix('ai')->name('ai.')->group(function () {
    Route::post('/analyze/{student}',   [\App\Http\Controllers\AI\AnalyticsController::class, 'refreshForStudent'])->name('analyze');
    Route::post('/recommend/{student}', [\App\Http\Controllers\AI\RecommendationController::class, 'generate'])->name('recommend');
});