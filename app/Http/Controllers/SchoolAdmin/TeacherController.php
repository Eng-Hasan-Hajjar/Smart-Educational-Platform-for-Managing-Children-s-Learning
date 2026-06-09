<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $teachers = User::where('school_id', $school->id)
            ->role('teacher')
            ->with(['teacherProfile'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('school-admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('school-admin.teachers.create');
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8',
            'phone'            => 'nullable|string|max:30',
            'gender'           => 'nullable|in:male,female',
            'avatar'           => 'nullable|image|max:2048',
            'specialization'   => 'nullable|string|max:100',
            'qualification'    => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'bio'              => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'phone'     => $data['phone'] ?? null,
            'gender'    => $data['gender'] ?? null,
            'avatar'    => $avatarPath,
            'school_id' => $school->id,
            'status'    => 'active',
        ]);

        $user->assignRole('teacher');

        TeacherProfile::create([
            'user_id'          => $user->id,
            'specialization'   => $data['specialization'] ?? null,
            'qualification'    => $data['qualification'] ?? null,
            'experience_years' => $data['experience_years'] ?? 0,
            'bio'              => $data['bio'] ?? null,
            'is_available'     => true,
        ]);

        return redirect()->route('school.teachers.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(User $teacher)
    {
        abort_if($teacher->school_id !== auth()->user()->school_id, 403);

        return view('school-admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        abort_if($teacher->school_id !== auth()->user()->school_id, 403);

        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $teacher->id,
            'phone'            => 'nullable|string|max:30',
            'avatar'           => 'nullable|image|max:2048',
            'specialization'   => 'nullable|string|max:100',
            'qualification'    => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'bio'              => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('avatar')) {
            if ($teacher->avatar) Storage::disk('public')->delete($teacher->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $teacher->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'phone'  => $data['phone'] ?? null,
            'avatar' => $data['avatar'] ?? $teacher->avatar,
            'status' => $data['status'],
        ]);

        $teacher->teacherProfile()->updateOrCreate(
            ['user_id' => $teacher->id],
            [
                'specialization'   => $data['specialization'] ?? null,
                'qualification'    => $data['qualification'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'bio'              => $data['bio'] ?? null,
            ]
        );

        return redirect()->route('school.teachers.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function destroy(User $teacher)
    {
        abort_if($teacher->school_id !== auth()->user()->school_id, 403);
        $teacher->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }

    // تعيين معلم لمادة وفصل
    public function assign(Request $request, User $teacher)
    {
        abort_if($teacher->school_id !== auth()->user()->school_id, 403);

        $data = $request->validate([
            'subject_id'      => 'required|exists:subjects,id',
            'classroom_id'    => 'required|exists:classrooms,id',
            'academic_year_id'=> 'required|exists:academic_years,id',
        ]);

        \DB::table('teacher_subject_classroom')->updateOrInsert(
            [
                'teacher_id'       => $teacher->id,
                'subject_id'       => $data['subject_id'],
                'classroom_id'     => $data['classroom_id'],
                'academic_year_id' => $data['academic_year_id'],
            ],
            ['is_primary' => true, 'created_at' => now(), 'updated_at' => now()]
        );

        return back()->with('success', 'تم تعيين المعلم بنجاح ✅');
    }
}