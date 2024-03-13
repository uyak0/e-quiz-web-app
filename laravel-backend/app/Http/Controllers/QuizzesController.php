<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Collection;

class QuizzesController extends Controller
{
    public function index(int $id): JsonResponse
    {
        $quiz = Quiz::find($id);
        $multiChoiceQuestions = $quiz->multiChoiceQuestions;
        $trueFalseQuestions = $quiz->trueFalseQuestions;
        $subjectiveQuestions = $quiz->subjectiveQuestions;

        $questions = new Collection();
        $questions = $questions->merge($multiChoiceQuestions);
        $questions = $questions->merge($trueFalseQuestions);
        $questions = $questions->merge($subjectiveQuestions);

        $sortedQuestions = $questions->sortBy('question_no')->values()->all();
        return response()->json($sortedQuestions);
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
