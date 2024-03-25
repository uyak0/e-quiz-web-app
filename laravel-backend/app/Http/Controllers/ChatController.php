<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
   public function store(Request $request)
   {
    if (!$request-> message_content) {
        return response()->json(data: ['status' => false], status: 500);
    }

    $chat = new Chat();
    $chat-> sender_id = auth() -> user() -> id;
    $chat -> receiver_id = $request -> receiver_id;
    $chat -> message = $request -> message_content;
    $chat -> save();

    $updatedChat = Chat::with(['sender', 'receiver']) ->find($chat->id);

    return response()->json(data:['status'=>true, 'chat'=>$updatedChat], status:201);

   }
}
