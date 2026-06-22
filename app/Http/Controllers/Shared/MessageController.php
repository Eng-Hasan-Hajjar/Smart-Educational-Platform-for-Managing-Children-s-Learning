<?php
namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;
use App\Models\{Conversation, Message, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['participants' => fn($q) => $q->where('users.id','!=',Auth::id()), 'latestMessage'])
            ->withCount(['messages as unread_count' => fn($q) =>
                $q->whereNull('read_at')->where('sender_id','!=',Auth::id())
            ])
            ->latest('last_message_at')
            ->paginate(20);

        return view('shared.messages.index', compact('conversations'));
    }

    public function compose(Request $request)
    {
        $recipientId = $request->query('recipient_id');
        $recipient = $recipientId ? User::find($recipientId) : null;
        $contacts = $this->getAvailableContacts();
        return view('shared.messages.compose', compact('recipient','contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['recipient_id'=>'required|exists:users,id','subject'=>'nullable|string|max:255','body'=>'required|string']);

        $conversation = Conversation::betweenUsers(Auth::id(), $data['recipient_id'])->first();
        if (!$conversation) {
            $conversation = Conversation::create(['subject' => $data['subject'] ?? null]);
            $conversation->participants()->attach([Auth::id(), $data['recipient_id']]);
        }

        $conversation->messages()->create(['sender_id' => Auth::id(), 'body' => $data['body']]);
        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('messages.show', $conversation)->with('success', __('app.message_sent_success'));
    }

    public function show(Conversation $conversation)
    {
        abort_unless($conversation->participants->contains('id', Auth::id()), 403);
        $conversation->messages()->where('sender_id','!=',Auth::id())->whereNull('read_at')->update(['read_at' => now()]);
        $conversation->load(['messages.sender','participants']);
        $otherParticipant = $conversation->participants->firstWhere('id','!=',Auth::id());
        return view('shared.messages.show', compact('conversation','otherParticipant'));
    }

    public function sendReply(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->participants->contains('id', Auth::id()), 403);
        $data = $request->validate(['body'=>'required|string']);
        $conversation->messages()->create(['sender_id' => Auth::id(), 'body' => $data['body']]);
        $conversation->update(['last_message_at' => now()]);
        return back();
    }

    private function getAvailableContacts()
    {
        $user = Auth::user();
        return match(true) {
            $user->hasRole('parent')  => User::role('teacher')->where('school_id',$user->school_id)->get(),
            $user->hasRole('student') => User::role('teacher')->where('school_id',$user->school_id)->get(),
            $user->hasRole('teacher') => User::role(['parent','school_admin','counselor'])->where('school_id',$user->school_id)->get(),
            default => User::where('school_id',$user->school_id)->where('id','!=',$user->id)->get(),
        };
    }
}
