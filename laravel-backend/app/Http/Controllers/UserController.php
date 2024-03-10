<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function getUser(int $id): JsonResponse
    {
        $user = User::find($id);
        return response() -> json($user);
    }
}
