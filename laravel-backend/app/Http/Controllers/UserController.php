<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 400);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 400);
    }

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


