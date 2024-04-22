<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Hiring;
use App\Models\Organization;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class HiringFactory extends Factory
{
    protected $model = Hiring::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'data' => $this->faker->date(),
            'doc_number' => $this->faker->word(),
            'employee_id' => Employee::factory(),
            'salary' => $this->faker->randomNumber(),
            'hiring_date' => $this->faker->date,
            'department_id' => Department::factory(),
            'basis' => $this->faker->word(),
            'position_id' => Position::factory(),
            'organization_id' => Organization::factory()
        ];
    }
}
