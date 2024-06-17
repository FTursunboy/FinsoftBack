<?php

namespace Database\Factories;

use App\Models\ServiceGoods;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ServiceGoodsFactory extends Factory
{
    protected $model = ServiceGoods::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'good_id' => $this->faker->randomNumber(),
            'service_id' => $this->faker->word(),
            'type' => $this->faker->word(),
            'price' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomNumber(),
        ];
    }
}
