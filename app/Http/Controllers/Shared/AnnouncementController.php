<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()?->name;

        $announcements = Announcement::where('school_id', $user->school_id)
            ->where(function ($q) use ($role) {
                $q->whereJsonContains('target_roles', $role)
                  ->orWhereNull('target_roles');
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        $readIds = $user->readAnnouncements()->pluck('announcement_id')->toArray();

        return view('shared.announcements.index', compact('announcements', 'readIds'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-announcements'); // اختياري حسب Policy عندك

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'target_roles' => 'nullable|array',
            'priority'     => 'required|in:normal,important,urgent',
            'expires_at'   => 'nullable|date',
        ]);

        Announcement::create([
            'school_id'    => Auth::user()->school_id,
            'created_by'   => Auth::id(),
            'title'        => $data['title'],
            'body'         => $data['body'],
            'target_roles' => $data['target_roles'] ?? null,
            'priority'     => $data['priority'],
            'expires_at'   => $data['expires_at'] ?? null,
        ]);

        return back()->with('success', __('app.announcement_created_success'));
    }

    public function markRead(Announcement $announcement)
    {
        Auth::user()->readAnnouncements()->syncWithoutDetaching([$announcement->id]);

        return back();
    }
}