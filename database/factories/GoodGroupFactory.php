<?php

namespace Database\Factories;

use App\Models\GoodGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodGroupFactory extends Factory
{
    protected $model = GoodGroup::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'is_good' => rand(0, 1),
            'is_service' => rand(0, 1)
        ];
    }
}
