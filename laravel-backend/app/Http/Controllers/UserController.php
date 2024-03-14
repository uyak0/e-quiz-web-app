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
        $user = User::where('remember_token', $request->remember_token)->first();
        if($user)
        {
            return response() -> json([
                'role' => $user->roles()->first()->name
            ]);
        }
    }
}
