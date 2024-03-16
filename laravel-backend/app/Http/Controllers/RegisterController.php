<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $role = Role::where('name', $request->role)->first()->id;

        $isValidated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        if($isValidated)
        {
            DB::beginTransaction();
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $user->roles()->attach($role);

                if ($request->role == 'student') {
                    $user->student()->create([
                        'user_id' => $user->id,
                        'points' => 0,
                    ]);
                }
                else if ($request->role == 'teacher') {
                    $user->teacher()->create([
                        'user_id' => $user->id
                    ]);
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create user, error: ' . $e->getMessage()
                ], 400);
            }

            // auto login user
            Auth::attempt($request->only('email', 'password'));

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully created user!',
                'user_id' => $user->id,
                'role' => $user->roles()->first()->name
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error'
            ], 400);
        }

    }
}
