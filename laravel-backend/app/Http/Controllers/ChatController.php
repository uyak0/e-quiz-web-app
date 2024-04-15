<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{

    public function index(Request $request)
    {
        $messagesQuery = Chat::getMessagesQueryBetweenTwoUsers($request, auth()->user()->id, $request->receiver_id);

        // display top 10 messages for user
        $result = $messagesQuery->orderBy('created_at', 'DESC')
                        ->limit($request->limit ?? 20)
                        ->get();

        if($result->count()) {
            foreach ($result as $msg) {
                if((int) $msg->receiver_id === auth()->user()->id) {
                    $msg->update(['seen' => 0]);
                }
            }
        }

        return response()->json(data: ['status' => true, 'messages' => $result]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'message_content' => 'required|string',
            'receiver_id' => 'required|exists:users,id', // Ensure receiver_id exists in the users table
        ]);

        // Create a new Chat instance with validated data
        $chat = new Chat();
        $chat->sender_id = auth()->user()->id;
        $chat->receiver_id = $validatedData['receiver_id'];
        $chat->message = $validatedData['message_content'];
        $chat->save();

        // Fetch the updated chat with sender and receiver details
        $updatedChat = Chat::with(['sender', 'receiver'])->find($chat->id);

        broadcast(new MessageSent(auth()->user(), $chat))->toOthers();
        return response()->json(['status' => true, 'chat' => $updatedChat], 201);
    }

    public function update($id)
    {
        $chat = Chat::find($id);

        $chat ->seen = 0;

        $chat->save();

        return response()->json(['status' => true, 'chat' => $chat]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file', // Validate the file
            'receiver_id' => 'required|exists:users,id', 
        ]);

        $file = $request->file('file');
        $receiverId = $request->receiver_id;

        // Define the file storage path
        $path = $file->store('public/uploads');

        // Generate a URL to access the file
        $url = Storage::url($path); 

        $chat = new Chat();
        $chat->sender_id = auth()->user()->id;
        $chat->receiver_id = $receiverId;
        $chat->message = $url; // Store the URL or a reference to the file in the message
        $chat->save();

        broadcast(new MessageSent(auth()->user(), $chat))->toOthers();

        return response()->json(['status' => true, 'chat' => $chat], 201);
    }

    
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt', // Validate the document
            'receiver_id' => 'required|exists:users,id', 
        ]);

        $document = $request->file('document');
        $receiverId = $request->receiver_id;

        $path = $document->store('public/documents');

        $url = Storage::url($path); 

        $originalFileName = $document->getClientOriginalName(); // Get the original file name

        $chat = new Chat();
        $chat->sender_id = auth()->user()->id;
        $chat->receiver_id = $receiverId;
        $chat->message = json_encode(['url' => $url, 'originalFileName' => $originalFileName]);
        $chat->save();

        broadcast(new MessageSent(auth()->user(), $chat))->toOthers();

        return response()->json(['status' => true, 'chat' => $chat], 201);
    }

}
