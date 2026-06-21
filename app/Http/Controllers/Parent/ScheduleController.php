<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\{User, Schedule};
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:parent']);
    }

    public function show(User $student)
    {
        abort_unless(
            Auth::user()->children()->where('users.id', $student->id)->exists(),
            403
        );

        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        $schedules = Schedule::whereIn('classroom_id', $classroomIds)
            ->where('is_active', true)
            ->with(['subject', 'teacher', 'timeSlot'])
            ->orderBy('time_slot_id')
            ->get()
            ->groupBy('day_of_week');

        return view('parent.children.schedule', compact('student', 'schedules'));
    }
}