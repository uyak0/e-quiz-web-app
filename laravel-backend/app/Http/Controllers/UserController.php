<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(int $id): JsonResponse
    {
        $user = User::find($id);
        return response() -> json($user);
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
}
