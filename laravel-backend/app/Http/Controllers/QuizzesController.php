<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Quiz;

class QuizzesController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();
        return response()->json($quizzes);
    }
}
