<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationBill>
 */
class OrganizationBillFactory extends Factory
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
            'currency_id' => Currency::inRandomOrder()->first()->id,
            'organization_id' => Organization::factory(),
            'bill_number' => time(),
            'date' => Carbon::now(),
            'comment' => fake()->text
        ];
    }
}
