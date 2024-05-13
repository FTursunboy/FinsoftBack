<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    public function definition(): array
    {
        $currencySuffixes = ['tjws', 'fadsfsdfas', 'ffs'];

        return [
            'name' => $currencySuffixes[array_rand($currencySuffixes)],
            'symbol_code' => $currencySuffixes[array_rand($currencySuffixes)],
            'digital_code' => fake()->numberBetween(100, 999)
        ];

    }
}
