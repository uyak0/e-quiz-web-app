<?php

namespace App\Http\Controllers;

use App\Models\UserQuizAnswers;
use Illuminate\Http\Request;

class UserQuizAnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userQuizAnswers = UserQuizAnswers::all();
        return response()->json($userQuizAnswers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userQuizAnswers = UserQuizAnswers::create([
            'quiz_id' => $request->quiz_id,
            'user_id' => $request->user_id,
            'user_answers' => $request->user_answers->json()
        ]);
        return response()->json($userQuizAnswers, 201); 
    }
}
