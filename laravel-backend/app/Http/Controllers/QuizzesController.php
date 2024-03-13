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
        $questions = $quizzes->questions;
        return response()->json($quizzes);
    }

    public function store(Request $request): JsonResponse
    {
        $quiz = Quiz::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ])->classroom()->save($request->classroom_id);

        return response()->json([
            'message' => 'Quiz created successfully',
        ]);
    }
}
