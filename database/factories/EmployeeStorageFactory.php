<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeStorage>
 */
class EmployeeStorageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'storage_id' => Storage::factory(),
            'employee_id' => Employee::factory(),
            'from' => Carbon::now(),
            'to' => Carbon::now()
        ];
    }
}
