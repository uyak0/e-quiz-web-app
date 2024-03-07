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

            $users = User::find($i+1);
            $users->roles()->attach(random_int(1,2));

        }
        for ($i = 1; $i < User::count(); $i++) {
            $classroom = Classroom::find(random_int(1,3));
            $users = User::find($i);
            $users->classrooms()->attach($classroom);
        }

    }
}
