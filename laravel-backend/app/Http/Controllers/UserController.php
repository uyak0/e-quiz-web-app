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
            'status' => 'error',
        ]);
    }
}
