<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classroom = Classroom::all();
        DB::table('classrooms')->insert([
            [
                'name' => 'Classroom 1',
                'description' => 'This is classroom 1',
                'created_at' => now(),
                'updated_at' => now(),
                'code' => hash('crc32', 'Classroom 1'),
            ],
            [
                'name' => 'Classroom 2',
                'description' => 'This is classroom 2',
                'created_at' => now(),
                'updated_at' => now(),
                'code' => hash('crc32', 'Classroom 2'),
            ],
            [
                'name' => 'Classroom 3',
                'description' => 'This is classroom 3',
                'created_at' => now(),
                'updated_at' => now(),
                'code' => hash('crc32', 'Classroom 3'),
            ],
        ]);
    }
}
