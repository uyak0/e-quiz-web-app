<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\Student;

class ClassroomsController extends Controller
{
    public function joinClassroom(int $studentId, int $classroomId): JsonResponse
    {
        $classroom = Classroom::find($classroomId)->first();
        $student = Student::find($studentId)->first();
        $user = $student->user();

        $user->classrooms()->attach($classroom);
        return response()->json(['message' => 'Classroom joined successfully']);
    }

    public function studentClassroomList(int $studentId): JsonResponse
    {
        $student = Student::find($studentId);
        $user = $student->user->first();
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
