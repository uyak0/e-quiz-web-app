<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Quiz;

class QuizzesController extends Controller
{
    public function index(int $classroom_id)
    {
        $classroom = Classroom::find($classroom_id);
        $quizzes = $classroom->quizzes;

        return view('quizzes.index', compact('classroom', 'quizzes'));
    }
}
