<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index(Request $request)
    {
        $schools = School::query()
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->withCount(['users as students_count' => fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', 'student'))])
            ->withCount(['users as teachers_count' => fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', 'teacher'))])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:150',
            'name_en'                 => 'nullable|string|max:150',
            'email'                   => 'required|email|unique:schools,email',
            'phone'                   => 'nullable|string|max:30',
            'city'                    => 'nullable|string|max:100',
            'country'                 => 'nullable|string|max:100',
            'description'             => 'nullable|string',
            'website'                 => 'nullable|url',
            'subscription_plan'       => 'required|in:basic,premium,enterprise',
            'subscription_expires_at' => 'nullable|date',
            'max_students'            => 'required|integer|min:1',
            'max_teachers'            => 'required|integer|min:1',
            'status'                  => 'required|in:active,inactive',
            'logo'                    => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name'] . '-' . Str::random(5));

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }

        School::create($data);

        return redirect()->route('admin.schools.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $data = $request->validate([
            'name'                    => 'required|string|max:150',
            'name_en'                 => 'nullable|string|max:150',
            'email'                   => 'required|email|unique:schools,email,' . $school->id,
            'phone'                   => 'nullable|string|max:30',
            'city'                    => 'nullable|string|max:100',
            'country'                 => 'nullable|string|max:100',
            'description'             => 'nullable|string',
            'website'                 => 'nullable|url',
            'subscription_plan'       => 'required|in:basic,premium,enterprise',
            'subscription_expires_at' => 'nullable|date',
            'max_students'            => 'required|integer|min:1',
            'max_teachers'            => 'required|integer|min:1',
            'status'                  => 'required|in:active,inactive,suspended',
            'logo'                    => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo) Storage::disk('public')->delete($school->logo);
            $data['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }

        $school->update($data);

        return redirect()->route('admin.schools.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function destroy(School $school)
    {
        if ($school->logo) Storage::disk('public')->delete($school->logo);
        $school->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }
}