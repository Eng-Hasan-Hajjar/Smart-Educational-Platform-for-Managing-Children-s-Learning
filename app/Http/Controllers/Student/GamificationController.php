<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Badge, PointTransaction, User};
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    public function __construct() { $this->middleware(['auth','role:student']); }

    public function index()
    {
        $student = Auth::user();
        $gamify = $student->gamification;
        if (!$gamify) {
            $gamify = \App\Models\GamificationPoint::create(['student_id' => $student->id]);
        }

        $earnedBadges = $student->badges()->orderByPivot('earned_at','desc')->get();
        $earnedBadgeIds = $earnedBadges->pluck('id');
        $lockedBadges = Badge::where('is_active', true)->whereNotIn('id', $earnedBadgeIds)->get();

        $classroomId = $student->classrooms()->first()?->id;
        $leaderboard = $classroomId
            ? User::whereHas('classrooms', fn($q) => $q->where('classrooms.id', $classroomId))
                ->role('student')->with('gamification')
                ->get()->sortByDesc(fn($u) => $u->gamification?->total_points ?? 0)->take(10)->values()
            : collect();

        $transactions = PointTransaction::where('student_id', $student->id)->latest()->take(20)->get();

        return view('student.achievements.index', compact('gamify', 'earnedBadges', 'lockedBadges', 'leaderboard', 'transactions'));
    }

    public function toggleFeatureBadge(Badge $badge)
    {
        $student = Auth::user();
        $pivot = $student->badges()->where('badge_id', $badge->id)->first();
        if ($pivot) {
            $student->badges()->updateExistingPivot($badge->id, ['is_featured' => !$pivot->pivot->is_featured]);
        }
        return back();
    }
}
