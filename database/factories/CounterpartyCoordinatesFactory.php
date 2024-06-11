<?php

namespace Database\Factories;

use App\Models\Counterparty;
use App\Models\CounterpartyCoordinates;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CounterpartyCoordinatesFactory extends Factory
{
    protected $model = CounterpartyCoordinates::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'location' => $this->faker->word(),

            'counterparty_id' => Counterparty::factory(),
        ];
    }
}
