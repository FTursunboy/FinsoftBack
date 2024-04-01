<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashRegister>
 */
class CashRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'currency_id' => Currency::factory(),
            'organization_id' => Organization::factory(),
            'responsible_person_id' => Employee::factory()
        ];
    }
}
