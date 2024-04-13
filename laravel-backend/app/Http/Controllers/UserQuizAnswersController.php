<?php

namespace App\Http\Controllers;

use App\Models\UserQuizAnswers;
use App\Models\Student;
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
            'user_answers' => $request->user_answers,
            'rewarded' => false 
        ]);

        return response()->json($userQuizAnswers, 201);
    }

    public function get(Request $request)
    {
        $userQuizAnswers = UserQuizAnswers::
            where('quiz_id', $request->quiz_id)
            ->where('user_id', auth()->user()->id)
            ->latest()->first();
        return response()->json([
            'user_answers' => $userQuizAnswers->user_answers,
            'timestamp' => $userQuizAnswers->created_at
        ]);
    }

    public function rewardPoints(Request $request)
    {
        if (UserQuizAnswers::where('quiz_id', $request->quiz_id)
                            ->where('user_id', auth()->user()->id)
                            ->where('rewarded', false)
                            ->latest()
                            ->exists()) {
            Student::where('user_id', auth()->user()->id)->increment('points', $request->correct_answers * 100);
            UserQuizAnswers::where('quiz_id', $request->quiz_id)
                            ->where('user_id', auth()->user()->id)
                            ->latest()->first()->update(['rewarded' => true]);
            return response()->json(['message' => 'Points rewarded']);
        }
        else {
            return response()->json(['message' => 'Points already awarded']);
        }
    }
}
