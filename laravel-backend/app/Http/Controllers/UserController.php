<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request): JsonResponse
    {
        $user = User::find($request->id);
        return response() -> json($user);
    }

    public function getUserRole(): JsonResponse
    {
        $user = auth()->user();
        return response() -> json($user->roles()->first()->name);
    }

    public function isAuthenticated(Request $request): JsonResponse
    {
        if(Auth::check())
        {
            $user = auth()->user();
            return response() -> json([
                'status' => true,
                'role' => $user->roles()->first()->name,
                'id' => $user->id
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }

    public function inClassroom(Request $request): JsonResponse
    {
        $user = auth()->user();
        $classroom = $user->classrooms()->where('classroom_id', $request->classroom_id)->first();
        if($classroom)
        {
            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }

    public function getOnlineUsers(){
        if(!auth()->check()) {
            return response()->json(data:['users'=>[]]);
        }

        $user = User::with('unseenMessages')->where('id', "!=", auth()->user()->id)->get();
        
        return response()->json(data: ['users'=> $user]);
    }

    public function updateMode(Request $request)
    {
        $request->validate(User::modeValidationRules());

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->mode = $request->mode;
        $user->save();

        return back()->with('success', 'Mode updated successfully.');
    }
}


