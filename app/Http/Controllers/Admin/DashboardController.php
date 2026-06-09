<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $stats = [
            'schools'        => School::count(),
            'active_schools' => School::where('status', 'active')->count(),
            'students'       => User::role('student')->count(),
            'teachers'       => User::role('teacher')->count(),
            'parents'        => User::role('parent')->count(),
            'lessons'        => Lesson::where('status', 'published')->count(),
            'quizzes'        => Quiz::where('status', 'published')->count(),
        ];

        $recentSchools = School::latest()->take(6)->get();

        // مستخدمون جدد آخر 7 أيام
        $newUsersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();

        // رسم بياني — مستخدمون جدد شهرياً
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // مدارس حسب حالة الاشتراك
        $subscriptionStats = School::selectRaw('subscription_plan, COUNT(*) as count')
            ->groupBy('subscription_plan')
            ->pluck('count', 'subscription_plan')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'recentSchools',
            'newUsersThisWeek',
            'monthlyUsers',
            'subscriptionStats'
        ));
    }
}