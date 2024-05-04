<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

public function definition(): array
{
    $currencySuffixes = ['tjs', 'usd', 'rub', 'eur', 'gbp', 'jpy', 'cad', 'aud', 'chf', 'cny', 'sek', 'nok', 'dkk', 'sgd', 'nzd', 'hkd', 'krw', 'inr', 'brl', 'mxn', 'try', 'zar', 'thb', 'idr', 'php', 'myr', 'rub', 'huf', 'czk', 'pln'];


    return [
        'name'  => $currencySuffixes[array_rand($currencySuffixes)],
        'symbol_code'  => $currencySuffixes[array_rand($currencySuffixes)],
        'digital_code' => fake()->numberBetween(100, 999)
    ];

}
