<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\TestRunner\TestResult\Collector;

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

    public function updateName(Request $request): JsonResponse
    {
        if (auth()->user()->roles->first()->name) {
            $classroom = Classroom::find($request->classroom_id);
            $classroom->name = $request->name;
            $classroom->save();

            return response() -> json([
                'status' => 'success',
                'message' => 'Classroom name updated successfully!'
            ]);
        }
        else {
            return response() -> json([
                'status' => 'error',
                'message' => 'You are not authorized to update the classroom name!'
            ]);
        }
    }

    public function updateDescription(Request $request): JsonResponse
    {
        $classroom = Classroom::find($request->classroom_id);
        $classroom->description = $request->description;
        $classroom->save();

        return response() -> json([
            'status' => 'success',
            'message' => 'Classroom description updated successfully!'
        ]);
    }

    public function deleteClassroom(int $id)
    {
        Classroom::find($id)->users()->detach();
        Classroom::destroy($id);
        return response() -> json([
            'status' => 'success',
            'message' => 'Classroom deleted successfully!'
        ]);
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
                'classroom_id' => $classroom->id,
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

    public function topStudents(int $id): JsonResponse
    {
        $classroom = Classroom::find($id);
        $users = $classroom->users;

        $students = $users->filter(function ($user) {
            return $user->student !== null;
        })->map(function ($user) {
            $student = $user->student;
            $studentPoints = $student->points; // Assuming 'value' is the column that stores the points

            return [
                'name' => $student->user->name,
                'points' => $studentPoints,
            ];
        });

        return response()->json($students);
    }
}
