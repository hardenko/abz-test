<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Position::factory()->create(['name' => 'Frontend developer']);
        Position::factory()->create(['name' => 'Backend developer']);
        Position::factory()->create(['name' => 'QA']);
        Position::factory()->create(['name' => 'PM']);
        Position::factory()->create(['name' => 'Team Leader']);
    }
}
