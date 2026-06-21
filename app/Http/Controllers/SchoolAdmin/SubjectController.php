<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Subject, AcademicLevel};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:school_admin']);
    }

    public function index(Request $request)
    {
        $school = Auth::user()->school;

        $subjects = Subject::where('school_id', $school->id)
            ->withCount(['units'])
            ->with('academicLevel')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            ->when($request->level_id, fn($q) =>
                $q->where('academic_level_id', $request->level_id)
            )
            ->orderBy('academic_level_id')
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        $levels = AcademicLevel::where('school_id', $school->id)->orderBy('order')->get();

        return view('school-admin.subjects.index', compact('subjects', 'levels'));
    }

    public function create()
    {
        $levels = AcademicLevel::where('school_id', Auth::user()->school_id)->orderBy('order')->get();
        return view('school-admin.subjects.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'name_en'            => 'nullable|string|max:255',
            'code'               => 'nullable|string|max:50',
            'description'        => 'nullable|string',
            'icon'               => 'nullable|string|max:10',
            'color'              => 'nullable|string|max:7',
            'academic_level_id'  => 'required|exists:academic_levels,id',
            'weekly_hours'       => 'required|integer|min:1|max:20',
            'order'              => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
        ]);

        Subject::create([
            'school_id'         => Auth::user()->school_id,
            'academic_level_id' => $data['academic_level_id'],
            'name'              => $data['name'],
            'name_en'           => $data['name_en'] ?? null,
            'code'              => $data['code'] ?? null,
            'description'       => $data['description'] ?? null,
            'icon'              => $data['icon'] ?? '📖',
            'color'             => $data['color'] ?? '#2196F3',
            'weekly_hours'      => $data['weekly_hours'],
            'order'             => $data['order'] ?? 0,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return redirect()->route('school-admin.subjects.index')
            ->with('success', __('schooladmin.subject_created_success'));
    }

    public function edit(Subject $subject)
    {
        abort_unless($subject->school_id === Auth::user()->school_id, 403);

        $levels = AcademicLevel::where('school_id', Auth::user()->school_id)->orderBy('order')->get();

        return view('school-admin.subjects.edit', compact('subject', 'levels'));
    }

    public function update(Request $request, Subject $subject)
    {
        abort_unless($subject->school_id === Auth::user()->school_id, 403);

        $data = $request->validate([
            'name'               => 'required|string|max:255',
            'name_en'            => 'nullable|string|max:255',
            'code'               => 'nullable|string|max:50',
            'description'        => 'nullable|string',
            'icon'               => 'nullable|string|max:10',
            'color'              => 'nullable|string|max:7',
            'academic_level_id'  => 'required|exists:academic_levels,id',
            'weekly_hours'       => 'required|integer|min:1|max:20',
            'order'              => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
        ]);

        $subject->update([
            'academic_level_id' => $data['academic_level_id'],
            'name'              => $data['name'],
            'name_en'           => $data['name_en'] ?? null,
            'code'              => $data['code'] ?? null,
            'description'       => $data['description'] ?? null,
            'icon'              => $data['icon'] ?? $subject->icon,
            'color'             => $data['color'] ?? $subject->color,
            'weekly_hours'      => $data['weekly_hours'],
            'order'             => $data['order'] ?? $subject->order,
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('school-admin.subjects.index')
            ->with('success', __('schooladmin.subject_updated_success'));
    }

    public function destroy(Subject $subject)
    {
        abort_unless($subject->school_id === Auth::user()->school_id, 403);

        if ($subject->units()->exists()) {
            return back()->with('error', __('schooladmin.subject_has_content_error'));
        }

        $subject->delete();

        return redirect()->route('school-admin.subjects.index')
            ->with('success', __('schooladmin.subject_deleted_success'));
    }
}