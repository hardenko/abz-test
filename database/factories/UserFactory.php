<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+380'.$this->faker->numerify('#########'),
            'position_id' => Position::inRandomOrder()->first(),
            'photo' => 'https://i.pravatar.cc/70?u=' . $this->faker->unique()->uuid,
        ];
    }
}
