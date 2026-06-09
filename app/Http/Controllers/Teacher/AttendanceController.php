<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index(Request $request)
    {
        $teacher = Auth::user();

        $classroomIds = \DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        $classrooms = Classroom::whereIn('id', $classroomIds)
            ->with('academicLevel')
            ->get();

        $selectedClassroom = null;
        $students          = collect();
        $existingAttendance = [];
        $date = $request->get('date', today()->toDateString());

        if ($request->classroom_id) {
            $selectedClassroom = Classroom::find($request->classroom_id);

            $students = $selectedClassroom->students()
                ->with('studentProfile')
                ->orderBy('name')
                ->get();

            $existingAttendance = Attendance::where('classroom_id', $request->classroom_id)
                ->whereDate('date', $date)
                ->pluck('status', 'student_id')
                ->toArray();
        }

        return view('teacher.attendance.index', compact(
            'classrooms', 'selectedClassroom', 'students', 'date', 'existingAttendance'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'date'         => 'required|date',
            'student_ids'  => 'required|array',
            'status'       => 'required|array',
        ]);

        foreach ($data['student_ids'] as $studentId) {
            $status = $data['status'][$studentId] ?? 'present';

            Attendance::updateOrCreate(
                [
                    'student_id'   => $studentId,
                    'classroom_id' => $data['classroom_id'],
                    'date'         => $data['date'],
                ],
                [
                    'teacher_id' => Auth::id(),
                    'status'     => $status,
                ]
            );

            // إشعار ولي الأمر إذا كان الطالب غائباً
            if ($status === 'absent') {
                $student = User::find($studentId);
                foreach ($student->parents as $parent) {
                    $parent->notify(
                        new \App\Notifications\StudentAbsentNotification($student, $data['date'])
                    );
                }
            }
        }

        return back()->with('success', 'تم حفظ الحضور بنجاح ✅');
    }

    public function report(Request $request)
    {
        $teacher = Auth::user();

        $classroomIds = \DB::table('teacher_subject_classroom')
            ->where('teacher_id', $teacher->id)
            ->pluck('classroom_id')
            ->unique();

        $classrooms = Classroom::whereIn('id', $classroomIds)->get();

        $report = [];

        if ($request->classroom_id) {
            $classroom = Classroom::find($request->classroom_id);
            $students  = $classroom->students()->with('studentProfile')->get();

            foreach ($students as $student) {
                $records = Attendance::where('student_id', $student->id)
                    ->where('classroom_id', $request->classroom_id)
                    ->when($request->month, fn($q) => $q->whereMonth('date', $request->month))
                    ->get();

                $report[] = [
                    'student'         => $student,
                    'total'           => $records->count(),
                    'present'         => $records->where('status', 'present')->count(),
                    'absent'          => $records->where('status', 'absent')->count(),
                    'late'            => $records->where('status', 'late')->count(),
                    'excused'         => $records->where('status', 'excused')->count(),
                    'attendance_rate' => $records->count() > 0
                        ? round($records->where('status', 'present')->count() / $records->count() * 100, 1)
                        : 0,
                ];
            }
        }

        return view('teacher.attendance.report', compact('classrooms', 'report'));
    }
}