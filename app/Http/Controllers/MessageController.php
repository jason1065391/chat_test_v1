<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function fetchMessages()
    {
        return Message::orderBy('created_at', 'desc')->get();
    }

    public function sendMessage(Request $request)
    {
        try {
            $message = Message::create([
                'user_name' => $request->user_name,
                'message' => $request->message,
            ]);

            // 觸發事件
            broadcast(new MessageSent($message));

            return response()->json($message);
        } catch (\Exception $e) {
            // 返回錯誤信息
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
