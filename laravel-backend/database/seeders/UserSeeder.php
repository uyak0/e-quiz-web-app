<?php

namespace Database\Seeders;

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
            ]);
        }

        DB::table('users')->insert([
            [
                'name' => 'student1',
                'email' => 'email@email.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'teacher1',
                'email' => 'teacher@email.com',
                'password' => Hash::make('password'),
            ]
        ]);

        $classrooms = Classroom::all();
        foreach($classrooms as $classroom) {
            $users = User::all()->random(5);
            $classroom->users()->attach($users);
        }
    }
}
