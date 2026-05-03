<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Auth;

class MessageController extends Controller
{
    public function index()
    {
        $me = Auth::id();

        // Only show users we've actually had a conversation with
        $contactIds = Message::where('sender_id', $me)
            ->orWhere('receiver_id', $me)
            ->get()
            ->map(fn($m) => $m->sender_id === $me ? $m->receiver_id : $m->sender_id)
            ->unique()
            ->values();

        $contacts = User::whereIn('id', $contactIds)->get();

        return view('messages.index', compact('contacts'));
    }

    public function searchUsers(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', Auth::id())
            ->where('name', 'like', "%{$q}%")
            ->select('id', 'name', 'avatar')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'avatar_url' => $u->avatar_url,
                'initials'   => strtoupper(substr($u->name, 0, 1)),
            ]);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'Message Sent']);
    }

    public function openChatWithSeller($sellerId)
    {
        $currentUserId = auth()->id();
        $chatId = "{$sellerId}_{$currentUserId}";
        $seller = User::findOrFail($sellerId); // Assuming you have a User model

        return redirect()->route('messages.index', [
            'chat_id' => $chatId,
            'seller_name' => $seller->name
        ]);
    }
    
    public function fetchMessages(Request $request)
    {
        $chatId = $request->query('chat_id');
        list($receiverId, $senderId) = explode('_', $chatId);
    
        $messages = Message::where(function($query) use ($receiverId, $senderId) {
            $query->where(function($query) use ($receiverId, $senderId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function($query) use ($receiverId, $senderId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            });
        })
        ->orderBy('created_at', 'asc')
        ->with('sender')
        ->get();
    
        return response()->json($messages);
    }
    public function markAsRead(Request $request)
{
    $senderId = $request->input('sender_id');
    $currentUserId = Auth::id();

    // 1. Mark messages from this specific sender to me as read
    Message::where('sender_id', $senderId)
           ->where('receiver_id', $currentUserId)
           ->where('is_read', false)
           ->update(['is_read' => true]);

    // 2. Calculate new total unread count (from ALL senders)
    $newCount = Message::where('receiver_id', $currentUserId)
                       ->where('is_read', false)
                       ->count();

    return response()->json(['unread_count' => $newCount]);
}
}
