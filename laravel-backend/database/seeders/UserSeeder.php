<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classroom;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('password')
            ])->roles()->attach(random_int(1,2));
        }

        for ($i = 0; $i < User::count(); $i++) {
            $users = User::find($i+1);
            $user_id = $users->id;
            $user_role = $users->roles()->first();

            $newStudent = new Student(['user_id' => $user_id, 'points' => 0]);
            $newTeacher = new Teacher(['user_id' => $user_id]);

            if ($user_role->name == 'student') {
                $users->student()->save($newStudent);
                $this->command->info($newStudent);
            }
            else if ($user_role->name == 'teacher') {
                $users->teacher()->save($newTeacher);
            }
        }

        for ($i = 1; $i < User::count(); $i++) {
            $classroom = Classroom::find(random_int(1,3));
            $users = User::find($i);
            $users->classrooms()->attach($classroom);
        }

    }
}
