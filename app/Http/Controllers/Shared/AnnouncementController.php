<?php
namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()?->name;

        $announcements = Announcement::where('school_id', $user->school_id)
            ->forRole($role)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at','>=',now()))
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        // Track read status via session (no pivot table in schema)
        $readIds = session('read_announcements', []);

        return view('shared.announcements.index', compact('announcements','readIds'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasAnyRole(['super_admin','school_admin']), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:general,academic,urgent,event',
            'target_type' => 'required|in:all,teachers,students,parents,specific',
            'expires_at'  => 'nullable|date',
        ]);

        Announcement::create([
            'school_id'  => Auth::user()->school_id,
            'created_by' => Auth::id(),
            'title'      => $data['title'],
            'body'       => $data['body'],
            'type'       => $data['type'],
            'target_type'=> $data['target_type'],
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        return back()->with('success', __('app.announcement_created_success'));
    }

    public function markRead(Announcement $announcement)
    {
        $readIds = session('read_announcements', []);
        $readIds[] = $announcement->id;
        session(['read_announcements' => array_unique($readIds)]);
        return back();
    }
}
