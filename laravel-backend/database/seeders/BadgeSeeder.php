<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First step',
                'description' => 'You took the first step!',
                'condition' => 'Complete your first quiz'
            ],
            [
                'name' => 'Newbie',
                'description' => 'You are getting there!',
                'condition' => 'Get 500 points'

            ],
            [
                'name' => 'Intermediate',
                'description' => 'You are getting better!',
                'condition' => 'Get 1000 points'
            ],
            [
                'name' => 'Expert',
                'description' => 'You are an expert!',
                'condition' => 'Get 2000 points'
            ]
        ];

        foreach ($badges as $badge) {
            \App\Models\Badge::create($badge);
        }
    }
}
