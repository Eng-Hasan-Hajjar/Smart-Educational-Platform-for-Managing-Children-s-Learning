<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index(Request $request)
    {
        $school     = auth()->user()->school;
        $classrooms = Classroom::where('school_id', $school->id)
            ->where('is_active', true)->with('academicLevel')->get();

        $schedules = collect();

        if ($request->classroom_id) {
            $schedules = Schedule::where('classroom_id', $request->classroom_id)
                ->where('is_active', true)
                ->with(['subject', 'teacher', 'timeSlot'])
                ->get()
                ->groupBy('day_of_week');
        }

        return view('school-admin.schedules.index', compact('classrooms', 'schedules'));
    }

    public function create()
    {
        $school     = auth()->user()->school;
        $classrooms = Classroom::where('school_id', $school->id)->where('is_active', true)->get();
        $subjects   = Subject::where('school_id', $school->id)->where('is_active', true)->get();
        $teachers   = User::where('school_id', $school->id)->role('teacher')->get();
        $timeSlots  = TimeSlot::where('school_id', $school->id)->orderBy('order')->get();
        $years      = AcademicYear::where('school_id', $school->id)->where('is_current', true)->get();

        return view('school-admin.schedules.create', compact(
            'classrooms', 'subjects', 'teachers', 'timeSlots', 'years'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'time_slot_id'     => 'required|exists:time_slots,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week'      => 'required|integer|min:0|max:6',
            'room'             => 'nullable|string|max:50',
            'is_online'        => 'boolean',
            'meeting_link'     => 'nullable|url',
        ]);

        $data['is_active'] = true;

        // التحقق من عدم وجود تعارض
        $conflict = Schedule::where('classroom_id', $data['classroom_id'])
            ->where('time_slot_id', $data['time_slot_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('academic_year_id', $data['academic_year_id'])
            ->where('is_active', true)
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'time_slot_id' => 'هذه الحصة محجوزة بالفعل لهذا الفصل في هذا اليوم.'
            ])->withInput();
        }

        Schedule::create($data);

        return redirect()->route('school.schedules.index', ['classroom_id' => $data['classroom_id']])
            ->with('success', __('app.save') . ' ✅');
    }

    public function destroy(Schedule $schedule)
    {
        abort_if(
            Classroom::find($schedule->classroom_id)?->school_id !== auth()->user()->school_id,
            403
        );

        $schedule->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }
}