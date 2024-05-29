<?php

namespace Database\Factories;

use App\Models\ReportCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReportCardFactory extends Factory
{
    protected $model = ReportCard::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->word(),
            'date' => $this->faker->word(),
            'comment' => $this->faker->word(),
        ];
    }
}
