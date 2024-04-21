<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Notifications\NewQuizAssigned;

class QuizzesController extends Controller
{
    /**
     * Maps the given questions to a specific format.
     *
     * @param Collection $questions The questions to map.
     * @param string $type The type of the questions.
     * @param array $fields The fields to include in the mapped questions.
     * @return Collection The mapped questions.
     *
     * @example
     * $multiChoiceFields = ['question_no', 'description', 'options', 'correct_answers'];
     * $multiChoiceQuestions = mapQuestions($quiz->multiChoiceQuestions, 'multi_choice', $multiChoiceFields);
     * // Returns a collection of multi-choice questions with the specified fields.
     */
    private function mapQuestions($questions, $type, $fields): Collection
    {
        return $questions->map(function ($question) use ($type, $fields) {
            $mappedQuestion = ['type' => $type];
            foreach ($fields as $field) {
                $mappedQuestion[$field] = $question->$field;
            }
            return $mappedQuestion;
        });
    }

    public function index(Request $request): JsonResponse
    {
        $quiz = Quiz::find($request->quiz_id);

        $multiChoiceFields = ['question_no', 'description', 'options', 'correct_answers'];
        $trueFalseFields = ['question_no', 'description', 'correct_answer'];
        $subjectiveFields = ['question_no', 'description', 'case_sensitive', 'correct_answers'];

        $multiChoiceQuestions = $this->mapQuestions($quiz->multiChoiceQuestions, 'multi_choice', $multiChoiceFields);
        $trueFalseQuestions = $this->mapQuestions($quiz->trueFalseQuestions, 'true_false', $trueFalseFields);
        $subjectiveQuestions = $this->mapQuestions($quiz->subjectiveQuestions, 'subjective', $subjectiveFields);

        $questions = new Collection();
        $questions = $questions->merge($multiChoiceQuestions);
        $questions = $questions->merge($trueFalseQuestions);
        $questions = $questions->merge($subjectiveQuestions);

        $sortedQuestions = $questions->sortBy('question_no')->values()->all();
        return response()->json([
            'quiz_name' => $quiz->title,
            'due_date' => $quiz->due_date,
            $sortedQuestions
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $quiz = Quiz::create([
                'title' => $request->title,
                'due_date' => $request->due_date,
                'classroom_id' => $request->classroom_id,
            ]);

            foreach($request->questions as $index=>$question) {
                switch($question['questionType']) {
                    case 'mcq':
                        $options = $question['options'];
                        $correctAnswers = $question['correctAnswers'];
                        $allOptions = array_merge($options, $correctAnswers);

                        $quiz->multiChoiceQuestions()->create([
                            'question_no' => $index + 1,
                            'description' => $question['questionDesc'],
                            'options' => implode(', ',$allOptions),
                            'correct_answers' => implode(', ',$question['correctAnswers']),
                        ]);
                    break;
                    case 'tfq':
                        $quiz->trueFalseQuestions()->create([
                            'question_no' => $index + 1,
                            'description' => $question['questionDesc'],
                            'correct_answer' => $question['correctAnswer'],
                        ]);
                    break;
                    case 'sub':
                        $quiz->subjectiveQuestions()->create([
                            'question_no' => $index + 1,
                            'description' => $question['questionDesc'],
                            'correct_answers' => $question['correctAnswer'],
                            'case_sensitive' => $question['caseSensitive'],
                        ]);
                    break;
                }
            }

            $classroom = Classroom::find($request->classroom_id);
            $classroomUsers = $classroom->users;
            $classroomStudents = [];
            $classroomStudents = $classroomUsers->filter(function ($user) {
                return $user->roles->contains('name', 'student');
            });

            Notification::send($classroomStudents, new NewQuizAssigned($quiz));
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the quiz',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz created successfully',
        ]);
    }
}
