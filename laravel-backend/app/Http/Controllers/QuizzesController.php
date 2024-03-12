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

    public function store(Request $request): JsonResponse
    {
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'classroom_id' => $request->classroom_id,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'message' => 'Quiz created successfully',
        ]);
    }
}
