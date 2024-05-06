<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\SalaryDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SalaryDocumentFactory extends Factory
{
    protected $model = SalaryDocument::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->numberBetween(10000000, 999999999),
            'date' => Carbon::now(),
            'organization_id' => Organization::factory(),
            'month_id' => $this->faker->numberBetween(1, 12),
            'author_id' => User::factory(),
            'comment' => $this->faker->word(),
        ];
    }
}
