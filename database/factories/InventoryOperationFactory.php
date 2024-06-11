<?php

namespace Database\Factories;

use App\Models\InventoryOperation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InventoryOperationFactory extends Factory
{
    protected $model = InventoryOperation::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->word(),
            'status_id' => $this->faker->word(),
            'active' => $this->faker->word(),
            'organization_id' => $this->faker->word(),
            'storage_id' => $this->faker->word(),
            'author_id' => $this->faker->word(),
            'date' => Carbon::now(),
            'comment' => $this->faker->word(),
            'currency_id' => $this->faker->word(),
        ];
    }
}
