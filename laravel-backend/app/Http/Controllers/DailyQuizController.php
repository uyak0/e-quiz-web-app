<?php

namespace App\Http\Controllers;

use App\Models\DailyQuiz;
use App\Models\UserQuizAnswers;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Symfony\Component\HttpFoundation\JsonResponse;

class DailyQuizController extends Controller
{
    public function newDailyQuiz(): JsonResponse
    {
        $student_id = auth()->user()->student->id;
        $completedQuizzes = UserQuizAnswers::where('user_id', auth()->user()->id)->get();   // list of all completed quizes of a student
        $completedDailyQuizzes = DailyQuiz::where('student_id', $student_id)     // list of all dailyquizzes a student has that is completed
            ->where('is_completed', true)->get();
        $latestDailyQuiz = DailyQuiz::where('student_id', $student_id)->latest();

        if ($latestDailyQuiz->exists()) {   // if the latest daily quiz is not completed, then return an error message
            if (!$latestDailyQuiz->first()->is_completed) {
                return response()->json([
                    'message' => 'You have an uncompleted daily quiz.'
                ], 400);
            }
        }

        foreach ($completedQuizzes as $completedQuiz) {     // if there exists a quiz_id in completedQuizzes that has not existed in dailyQuizzes, then create a new dailyQuiz
            if (!$completedDailyQuizzes->where('quiz_id', $completedQuiz->quiz_id)->exists()) {
                $dailyQuiz = new DailyQuiz();
                $dailyQuiz->student_id = $student_id;
                $dailyQuiz->quiz_id = $completedQuiz->quiz_id;
                $dailyQuiz->save();
            }
        }

        return response()->json([
            'message' => 'Daily quiz created successfully.',
            'daily_quiz' => $dailyQuiz
        ]);
    }
}
