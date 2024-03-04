<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomsController extends Controller
{
    public function index(int $id = null)
    {
        if (!$id)
        {
            $classrooms=Classroom::all();
            return response() -> json($classrooms);
        }
        else
        {
            $classroom=Classroom::find($id);
            return response() -> json($classroom);
        }
    }

    public function quiz(int $id)
    {
        $classroom=Classroom::find($id);
        $quiz=$classroom->quiz;
        return response() -> json($quiz);
    }
}
