<?php

namespace Database\Factories;

use App\Models\Firing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FiringFactory extends Factory
{
    protected $model = Firing::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->word(),
            'date' => $this->faker->word(),
            'organization_id' => $this->faker->randomNumber(),
            'employee_id' => $this->faker->randomNumber(),
            'firing_date' => $this->faker->word(),
            'basis' => $this->faker->word(),
            'author_id' => $this->faker->randomNumber(),
            'comment' => $this->faker->word(),
        ];
    }
}
