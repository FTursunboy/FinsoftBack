<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\PriceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceTypeFactory extends Factory
{
    protected $model = PriceType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'currency_id' => Currency::inRandomOrder()->first()->id,
            'description' => fake()->text
        ];
    }
}
