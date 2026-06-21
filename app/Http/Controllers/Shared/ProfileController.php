<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('shared.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'phone'                 => 'nullable|string|max:30',
            'avatar'                => 'nullable|image|max:2048',
            'current_password'      => 'nullable|required_with:new_password|current_password',
            'new_password'          => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
        }

        $user->update(collect($data)->only([
            'name', 'email', 'phone', 'avatar', 'password',
        ])->toArray());

        return back()->with('success', __('app.profile_updated_success'));
    }
}