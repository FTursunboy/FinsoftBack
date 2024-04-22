<?php

namespace Database\Factories;

use App\Models\EmployeeMovement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeMovementFactory extends Factory
{
    protected $model = EmployeeMovement::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->word(),
            'date' => $this->faker->word(),
            'employee_id' => $this->faker->randomNumber(),
            'salary' => $this->faker->randomFloat(),
            'position' => $this->faker->randomNumber(),
            'movement_date' => $this->faker->word(),
            'schedule' => $this->faker->word(),
            'basis' => $this->faker->word(),
        ];
    }
}
