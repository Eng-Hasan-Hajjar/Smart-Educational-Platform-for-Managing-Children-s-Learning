<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index(Request $request)
    {
        $users = User::query()
            ->with(['roles', 'school'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn($q) =>
                $q->whereHas('roles', fn($r) => $r->where('name', $request->role))
            )
            ->when($request->school_id, fn($q) =>
                $q->where('school_id', $request->school_id)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $schools = School::orderBy('name')->get(['id', 'name']);

        return view('admin.users.index', compact('users', 'schools'));
    }

    public function create()
    {
        $schools = School::orderBy('name')->get(['id', 'name']);
        $roles   = Role::all();

        return view('admin.users.create', compact('schools', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'role'                  => 'required|exists:roles,name',
            'school_id'             => 'nullable|exists:schools,id',
            'phone'                 => 'nullable|string|max:30',
            'gender'                => 'nullable|in:male,female',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'school_id' => $data['school_id'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'gender'    => $data['gender'] ?? null,
            'status'    => 'active',
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('admin.users.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(User $user)
    {
        $schools = School::orderBy('name')->get(['id', 'name']);
        $roles   = Role::all();

        return view('admin.users.edit', compact('user', 'schools', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:8|confirmed',
            'role'      => 'required|exists:roles,name',
            'school_id' => 'nullable|exists:schools,id',
            'phone'     => 'nullable|string|max:30',
            'status'    => 'required|in:active,inactive,banned',
        ]);

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'school_id' => $data['school_id'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'status'    => $data['status'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', __('app.save') . ' ✅');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }
}