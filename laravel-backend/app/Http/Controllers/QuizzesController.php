<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizzesController extends Controller
{
    public function index(): JsonResponse
    {
        $quizzes = Quiz::all();
        return response()->json($quizzes);
    }
}
