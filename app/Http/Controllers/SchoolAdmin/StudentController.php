<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\AcademicLevel;
use App\Models\Classroom;
use App\Models\GamificationPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index(Request $request)
    {
        $school = auth()->user()->school;
        $levels = AcademicLevel::where('school_id', $school->id)->get();

        $students = User::where('school_id', $school->id)
            ->role('student')
            ->with(['studentProfile.academicLevel', 'classrooms'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->level_id, fn($q) =>
                $q->whereHas('studentProfile', fn($p) =>
                    $p->where('academic_level_id', $request->level_id)
                )
            )
            ->when($request->status, fn($q) =>
                $q->whereHas('studentProfile', fn($p) =>
                    $p->where('academic_status', $request->status)
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('school-admin.students.index', compact('students', 'levels'));
    }

    public function create()
    {
        $school    = auth()->user()->school;
        $levels    = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();
        $classrooms = Classroom::where('school_id', $school->id)
            ->where('is_active', true)
            ->with('academicLevel')
            ->get();

        return view('school-admin.students.create', compact('levels', 'classrooms'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'phone'             => 'nullable|string|max:30',
            'gender'            => 'nullable|in:male,female',
            'birth_date'        => 'nullable|date',
            'avatar'            => 'nullable|image|max:2048',
            'academic_level_id' => 'required|exists:academic_levels,id',
            'classroom_id'      => 'nullable|exists:classrooms,id',
            'student_number'    => 'nullable|string|max:50',
            'blood_type'        => 'nullable|string|max:5',
            'enrollment_date'   => 'nullable|date',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'phone'      => $data['phone'] ?? null,
            'gender'     => $data['gender'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'avatar'     => $avatarPath,
            'school_id'  => $school->id,
            'status'     => 'active',
        ]);

        $user->assignRole('student');

        StudentProfile::create([
            'user_id'           => $user->id,
            'academic_level_id' => $data['academic_level_id'],
            'student_number'    => $data['student_number'] ?? 'STU-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'blood_type'        => $data['blood_type'] ?? null,
            'enrollment_date'   => $data['enrollment_date'] ?? today(),
            'academic_status'   => 'average',
        ]);

        GamificationPoint::create([
            'student_id'    => $user->id,
            'total_points'  => 0,
            'level'         => 1,
            'level_title'   => 'مبتدئ',
        ]);

        if (!empty($data['classroom_id'])) {
            $classroom = Classroom::find($data['classroom_id']);
            $classroom->students()->syncWithoutDetaching([
                $user->id => ['enrolled_at' => today()->toDateString(), 'is_active' => true]
            ]);
        }

        return redirect()->route('school.students.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(User $student)
    {
        abort_if($student->school_id !== auth()->user()->school_id, 403);
        $school     = auth()->user()->school;
        $levels     = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();
        $classrooms = Classroom::where('school_id', $school->id)->where('is_active', true)->get();

        return view('school-admin.students.edit', compact('student', 'levels', 'classrooms'));
    }

    public function update(Request $request, User $student)
    {
        abort_if($student->school_id !== auth()->user()->school_id, 403);

        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email,' . $student->id,
            'phone'             => 'nullable|string|max:30',
            'avatar'            => 'nullable|image|max:2048',
            'academic_level_id' => 'required|exists:academic_levels,id',
            'status'            => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('avatar')) {
            if ($student->avatar) Storage::disk('public')->delete($student->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $student->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'phone'  => $data['phone'] ?? null,
            'avatar' => $data['avatar'] ?? $student->avatar,
            'status' => $data['status'],
        ]);

        $student->studentProfile()->update([
            'academic_level_id' => $data['academic_level_id'],
        ]);

        return redirect()->route('school.students.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function toggleStatus(User $student)
    {
        abort_if($student->school_id !== auth()->user()->school_id, 403);

        $student->update([
            'status' => $student->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'تم تحديث الحالة ✅');
    }

    public function destroy(User $student)
    {
        abort_if($student->school_id !== auth()->user()->school_id, 403);
        $student->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }
}