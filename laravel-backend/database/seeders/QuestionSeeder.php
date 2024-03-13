<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('multi_choice_questions')->insert([
            [
                'description' => 'What is the capital of Indonesia?',
                'correct_answers' => 'Jakarta',
                'options' => json_encode('Jakarta, Bandung, Surabaya, Medan'),
                'question_no' => '1',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Malaysia?',
                'correct_answers' => 'Kuala Lumpur',
                'options' => json_encode('Kuala Lumpur, Penang, Johor Bahru, Malacca'),
                'question_no' => '2',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Singapore?',
                'correct_answers' => 'Singapore',
                'options' => json_encode('Singapore, Kuala Lumpur, Jakarta, Bangkok'),
                'question_no' => '3',
                'quiz_id' => '1'
            ],
        ]);

        DB::table('subjective_questions')->insert([
            [
                'description' => 'What is the capital of Indonesia?',
                'correct_answers' => 'Jakarta',
                'question_no' => '4',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Malaysia?',
                'correct_answers' => 'Kuala Lumpur',
                'question_no' => '5',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Singapore?',
                'correct_answers' => 'Singapore',
                'question_no' => '6',
                'quiz_id' => '1'
            ],
        ]);

        DB::table('true_false_questions')->insert([
            [
                'description' => 'Jakarta is the capital of Indonesia',
                'correct_answer' => '1',
                'question_no' => '7',
                'quiz_id' => '1'
            ],
            [
                'description' => 'Kuala Lumpur is the capital of Indonesia',
                'correct_answer' => '0',
                'question_no' => '8',
                'quiz_id' => '1'
            ],
            [
                'description' => 'Singapore is the capital of Indonesia',
                'correct_answer' => '0',
                'question_no' => '9',
                'quiz_id' => '1'
            ],
        ]);
    }
}
