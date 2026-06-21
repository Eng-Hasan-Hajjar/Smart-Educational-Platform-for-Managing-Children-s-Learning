<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('shared.notifications.index', compact('notifications'));
    }

    public function markRead($notification)
    {
        $notif = Auth::user()->notifications()->findOrFail($notification);
        $notif->markAsRead();

        return back();
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', __('app.all_marked_read'));
    }

    public function destroy($notification)
    {
        $notif = Auth::user()->notifications()->findOrFail($notification);
        $notif->delete();

        return back();
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}