<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\User;

class ClassroomsController extends Controller
{
    public function joinClassroom(int $userId, int $classroomId): JsonResponse
    {
        $classroom = Classroom::find($classroomId);
        $user = User::find($userId);

        $user->classrooms()->attach($classroom);
        return response()->json(['message' => 'Classroom joined successfully']);
    }

    public function studentClassroomList(int $userId): JsonResponse
    {
        $user = User::find($userId);
        $classrooms = $user->classrooms;
        return response() -> json($classrooms);
    }

    public function index(string $classroomCode): JsonResponse
    {
        $classroom=Classroom::where('code', $classroomCode)->first();
        return response() -> json($classroom);
    }

    public function quiz(int $id): JsonResponse
    {
        $classroom=Classroom::find($id);
        $quiz=$classroom->quiz;
        return response() -> json($quiz);
    }
}
