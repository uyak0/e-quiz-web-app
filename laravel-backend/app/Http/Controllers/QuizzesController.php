<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Collection;

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
        $subjectiveFields = ['question_no', 'description', 'correct_answers', 'case_sensitive'];

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
            $sortedQuestions
        ]);
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
