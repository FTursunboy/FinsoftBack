<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{

    protected $model = Organization::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' => fake()->text(),
            'description' => fake()->text(),
            'INN' => time(),
            'director_id' => Employee::factory(),
            'chief_accountant_id' => Employee::factory(),
        ];
    }

}
