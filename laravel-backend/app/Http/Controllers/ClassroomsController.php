<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Classroom;
use App\Models\Student;

class ClassroomsController extends Controller
{
    public function studentClassroomList(int $studentId): JsonResponse
    {
        $user = Student::find($studentId)->user->first();
        $classrooms = $user->classrooms;
        return response() -> json($classrooms);
    }

    public function index(int $id): JsonResponse
    {
        $classroom=Classroom::find($id);
        return response() -> json($classroom);
    }

    public function quiz(int $id): JsonResponse
    {
        $classroom=Classroom::find($id);
        $quiz=$classroom->quiz;
        return response() -> json($quiz);
    }
}
