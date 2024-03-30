<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'name'  => fake()->name,
            'symbol_code'  => fake()->numberBetween(1, 10),
            'digital_code' => fake()->numberBetween(100, 999)
        ];
    }
}
