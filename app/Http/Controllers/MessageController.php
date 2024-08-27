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
        $users = User::where('id', '!=', Auth::id())->get();
        $messages = Message::where(function($query) {
            $query->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return view('messages.index', compact('users', 'messages'));
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
        })->orderBy('created_at', 'asc')->get();

    return response()->json($messages);
    }
}
