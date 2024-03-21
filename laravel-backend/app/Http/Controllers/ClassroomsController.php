<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClassroomsController extends Controller
{
    private function findId(string $classroomCode): int
    {
        $classroom = Classroom::where('code', $classroomCode)->first();
        if ($classroom == null) {
            return 0;
        }
        else {
            return $classroom->id;
        }
    }

    public function createClassroom(Request $request): JsonResponse
    {
        $role = auth()->user()->roles()->first()->name;
        if (Auth::check()) {
            if ($role != 'teacher') {
                return response() -> json([
                    'status' => 'error',
                    'message' => 'You are not authorized to create a classroom!'
                ]);
            }

            $code = hash('crc32', $request->classroom_name);
            $classroom = Classroom::create([
                'name' => $request->classroom_name,
                'description' => $request->classroom_desc,
                'code' => $code,
            ]);

            $this->joinClassroom(auth()->user()->id, $classroom->code);

            return response() -> json([
                'status' => 'success',
                'message' => 'Classroom created successfully!',
            ], 201);
        }
    }

    public function joinClassroom(int $userId, string $classroomCode): JsonResponse
    {
        $classroomId = $this->findId($classroomCode);
        $classroomJoined = User::find($userId)->classrooms()->where('classroom_id', $classroomId)->exists();

        if ($classroomJoined) {
            return response()->json([
                'status' => 'already joined',
                'message' => 'You already joined this classroom!'
            ]);
        }
        else if ($classroomId == 0) {
            return response()->json([
                'status' => 'classroom not found',
                'message' => 'Classroom not found! Check the code and try again'
            ]);
        }
        else if (!$classroomJoined){
            $classroom = Classroom::find($classroomId);
            $user = User::find($userId);

            $user->classrooms()->attach($classroom);

            return response()->json([
                'status' => 'success',
                'classroom_id' => $classroomId,
                'message' => 'Classroom joined successfully'
            ]);
        }
    }

    public function userClassrooms(int $userId): JsonResponse
    {
        $user = User::find($userId);
        $classrooms = $user->classrooms;
        return response() -> json($classrooms);
    }

    public function index(int $id): JsonResponse
    {
        $classroom=Classroom::find($id);
        return response() -> json($classroom);
    }

    public function classroomQuizzes(int $classroomId): JsonResponse
    {
        $classroom = Classroom::find($classroomId);
        $quizzes = $classroom->quizzes;
        return response() -> json($quizzes);
    }
}
