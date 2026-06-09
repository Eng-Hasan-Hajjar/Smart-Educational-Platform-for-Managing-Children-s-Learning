<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\AcademicLevel;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index(Request $request)
    {
        $school = auth()->user()->school;

        $classrooms = Classroom::where('school_id', $school->id)
            ->with(['academicLevel', 'academicYear'])
            ->withCount('students')
            ->when($request->level_id, fn($q) =>
                $q->where('academic_level_id', $request->level_id)
            )
            ->paginate(12)
            ->withQueryString();

        $levels = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();

        return view('school-admin.classrooms.index', compact('classrooms', 'levels'));
    }

    public function create()
    {
        $school  = auth()->user()->school;
        $levels  = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();
        $years   = AcademicYear::where('school_id', $school->id)->orderByDesc('id')->get();

        return view('school-admin.classrooms.create', compact('levels', 'years'));
    }

    public function store(Request $request)
    {
        $school = auth()->user()->school;

        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'section'           => 'nullable|string|max:10',
            'academic_level_id' => 'required|exists:academic_levels,id',
            'academic_year_id'  => 'required|exists:academic_years,id',
            'capacity'          => 'required|integer|min:1|max:100',
            'room_number'       => 'nullable|string|max:20',
            'description'       => 'nullable|string',
        ]);

        $data['school_id']  = $school->id;
        $data['is_active']  = true;

        Classroom::create($data);

        return redirect()->route('school.classrooms.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(Classroom $classroom)
    {
        abort_if($classroom->school_id !== auth()->user()->school_id, 403);

        $school = auth()->user()->school;
        $levels = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();
        $years  = AcademicYear::where('school_id', $school->id)->orderByDesc('id')->get();

        return view('school-admin.classrooms.edit', compact('classroom', 'levels', 'years'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        abort_if($classroom->school_id !== auth()->user()->school_id, 403);

        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'section'     => 'nullable|string|max:10',
            'capacity'    => 'required|integer|min:1|max:100',
            'room_number' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $classroom->update($data);

        return redirect()->route('school.classrooms.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function destroy(Classroom $classroom)
    {
        abort_if($classroom->school_id !== auth()->user()->school_id, 403);
        $classroom->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }
}