<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Student;

class ClassroomsController extends Controller
{
    public function index(int $studentId)
    {
        $classrooms = Classroom::with('students')->where('student_id', $studentId)->get();
        return response() -> json($classrooms);
    }

    public function quiz(int $id)
    {
        $classroom=Classroom::find($id);
        $quiz=$classroom->quiz;
        return response() -> json($quiz);
    }
}
