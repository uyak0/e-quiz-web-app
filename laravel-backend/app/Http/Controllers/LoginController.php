<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
    * Login user and create token
    *
    * @param  [string] email
    * @param  [string] password
    * @param  [boolean] remember_me
    */

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email','password']);
        if(!Auth::attempt($credentials, $request->remember_me))
        {
            return response()->json([
                'message' => 'Invalid Credentials! Please try again'
            ],401);
        }

        $user = $request->user();

        return response()->json([
            'rememberToken' => $user->remember_token,
            'user_id' => $user->id,
            'role' => $user->roles()->first()->name
        ]);
    }
}
