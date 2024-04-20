<?php

namespace App\Http\Controllers;

use App\Models\DailyQuiz;
use App\Models\UserQuizAnswers;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Laravel\Prompts\error;

class DailyQuizController extends Controller
{
    public function newDailyQuiz(): JsonResponse
    {
        $student_id = auth()->user()->student->id;
        $completedQuizzes = UserQuizAnswers::where('user_id', auth()->user()->id)->get();   // list of all completed quizes of a student
        $completedDailyQuizzes = DailyQuiz::where('student_id', $student_id)     // list of all dailyquizzes a student has that is completed
            ->where('is_completed', true)->get();
        $latestDailyQuiz = DailyQuiz::where('student_id', $student_id)->latest()->first();

        if ($latestDailyQuiz) {
            if ($latestDailyQuiz->is_completed == false) {   // if the latest daily quiz is not completed, then return an error message
                return response()->json([
                    'message' => 'Uncompleted daily quiz',
                    'daily_quiz' => $latestDailyQuiz
                ]);
            }
            else if ($latestDailyQuiz->created_at->diffInDays() < 1) {   // if the latest daily quiz was created less than a day ago, then return an error message
                return response()->json([
                    'message' => 'Daily quiz for today already completed.',
                ], 400);
            }
        }

        $dailyQuiz = new DailyQuiz();

        foreach ($completedQuizzes as $completedQuiz) {     // if there exists a quiz_id in completedQuizzes that has not existed in dailyQuizzes, then create a new dailyQuiz
            if ($completedDailyQuizzes->where('quiz_id', $completedQuiz->quiz_id)) {
                $dailyQuiz->student_id = $student_id;
                $dailyQuiz->quiz_id = $completedQuiz->quiz_id;
                $dailyQuiz->save();
            }
            else {
                return response()->json([
                    'message' => 'You have no new daily quiz.'
                ], 400);
                break;
            }
        }

        return response()->json([
            'message' => 'Daily quiz created successfully.',
            'daily_quiz' => $dailyQuiz
        ]);
    }
}
