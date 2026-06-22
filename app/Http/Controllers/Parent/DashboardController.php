<?php
namespace App\Http\Controllers\Parent;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:parent']); }

    public function index()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['studentProfile.academicLevel','classrooms','gamification'])->get();
        $classroomIds = $children->flatMap(fn($c) => $c->classrooms->pluck('id'))->unique();

        $upcomingAssignments = Assignment::whereIn('classroom_id', $classroomIds)
            ->where('status','published')->where('due_date','>=',now()->subDays(1))
            ->with(['subject','classroom'])->orderBy('due_date')->take(6)->get();

        $unreadNotifications = $parent->unreadNotifications()->take(6)->get();

        return view('parent.dashboard', compact('children','upcomingAssignments','unreadNotifications'));
    }
}
