<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    private function teacherSubjectIds()
    {
        return DB::table('teacher_subject_classroom')
            ->where('teacher_id', Auth::id())
            ->pluck('subject_id')
            ->unique();
    }

    public function index(Request $request)
    {
        $subjectIds = $this->teacherSubjectIds();
        $subjects   = Subject::whereIn('id', $subjectIds)->get();

        $units = Unit::whereIn('subject_id', $subjectIds)
            ->with(['subject', 'lessons'])
            ->withCount('lessons')
            ->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))
            ->orderBy('subject_id')
            ->orderBy('order')
            ->paginate(20)
            ->withQueryString();

        return view('teacher.units.index', compact('units', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::whereIn('id', $this->teacherSubjectIds())->get();
        return view('teacher.units.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|max:200',
            'name_en'     => 'nullable|string|max:200',
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_published'=> 'boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['order']        = $data['order'] ?? Unit::where('subject_id', $data['subject_id'])->max('order') + 1;

        Unit::create($data);

        return redirect()->route('teacher.units.index')
            ->with('success', __('app.created_successfully'));
    }

    public function edit(Unit $unit)
    {
        $subjects = Subject::whereIn('id', $this->teacherSubjectIds())->get();
        $unit->load('lessons');

        return view('teacher.units.edit', compact('unit', 'subjects'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|max:200',
            'name_en'     => 'nullable|string|max:200',
            'description' => 'nullable|string|max:1000',
            'order'       => 'nullable|integer|min:0',
            'is_published'=> 'boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $unit->update($data);

        return redirect()->route('teacher.units.index')
            ->with('success', __('app.updated_successfully'));
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('teacher.units.index')
            ->with('success', __('app.deleted_successfully'));
    }
}