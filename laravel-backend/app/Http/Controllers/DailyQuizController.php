<?php

namespace App\Http\Controllers;

use App\Models\DailyQuiz;
use App\Models\UserQuizAnswers;
use Illuminate\Http\Request;
use App\Models\Quiz;

class DailyQuizController extends Controller
{
    public function newDailyQuiz(Request $request)
    {
        $student_id = auth()->user()->student->id;
        $completedQuizzes = UserQuizAnswers::where('user_id', auth()->user()->id)->get();
        $dailyQuizzes = DailyQuiz::where('student_id', $student_id)
            ->where('is_completed', true)->latest();


        $dailyQuiz = new DailyQuiz();
        $dailyQuiz->student_id = $student_id;
        $dailyQuiz->quiz_id = $request->quiz_id;
        $dailyQuiz->save();

        return response()->json([
            'message' => 'Daily quiz created successfully.'
        ]);
    }
}
