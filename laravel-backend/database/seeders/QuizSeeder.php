<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classroom = Classroom::all()->pluck('id')->toArray();
        $faker = \Faker\Factory::create();

        DB::table('quizzes')->insert([
            'title' => 'Quiz 1',
            'due_date' => '2025-01-01 00:00:00',
            'classroom_id' => $faker->randomElement($classroom),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
