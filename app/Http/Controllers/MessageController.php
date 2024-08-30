<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    /**
     * Fetch messages between the user and the selected user.
     */
    public function fetchMessages(Request $request)
    {
        $userId = $request->query('user_id');
        $messages = Message::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($messages);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $message = Message::create($validated);

        // Broadcast the message to the channel
        broadcast(new MessageSent($message));

        return response()->json($message);
    }
}
