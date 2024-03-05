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
                'options' => 'Jakarta, Bandung, Surabaya, Medan',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Malaysia?',
                'correct_answers' => 'Kuala Lumpur',
                'options' => 'Kuala Lumpur, Penang, Johor Bahru, Malacca',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Singapore?',
                'correct_answers' => 'Singapore',
                'options' => 'Singapore, Kuala Lumpur, Jakarta, Bangkok',
                'quiz_id' => '1'
            ],
        ]);

        DB::table('subjective_questions')->insert([
            [
                'description' => 'What is the capital of Indonesia?',
                'correct_answers' => 'Jakarta',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Malaysia?',
                'correct_answers' => 'Kuala Lumpur',
                'quiz_id' => '1'
            ],
            [
                'description' => 'What is the capital of Singapore?',
                'correct_answers' => 'Singapore',
                'quiz_id' => '1'
            ],
        ]);

        DB::table('true_false_questions')->insert([
            [
                'description' => 'Jakarta is the capital of Indonesia',
                'correct_answer' => '1',
                'quiz_id' => '1'
            ],
            [
                'description' => 'Kuala Lumpur is the capital of Indonesia',
                'correct_answer' => '0',
                'quiz_id' => '1'
            ],
            [
                'description' => 'Singapore is the capital of Indonesia',
                'correct_answer' => '0',
                'quiz_id' => '1'
            ],
        ]);
    }
}
