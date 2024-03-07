<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;

class ClassroomsController extends Controller
{
    public function index(int $studentId)
    {
        $student = Student::find($studentId)->first();
        $user =

        $classrooms = $user->classrooms()->where('user_id', $user)->get();
        return response() -> json($classrooms);
    }

    public function quiz(int $id)
    {
        $classroom=Classroom::find($id);
        $quiz=$classroom->quiz;
        return response() -> json($quiz);
    }
}
