<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    public function definition(): array
    {
        static $names = [
            'Frontend developer',
            'Backend developer',
            'QA',
            'PM',
            'Team Leader',
        ];

        return [
            'name' => array_shift($names),
        ];
    }
}
