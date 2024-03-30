<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Good;
use App\Models\GoodGroup;
use App\Models\Storage;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodFactory extends Factory
{
    protected $model = Good::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'vendor_code' => fake()->numberBetween(1000, 99999999),
            'description' => fake()->text(),
            'unit_id' => Unit::factory(),
            'barcode' => time() . fake()->numberBetween(1292992,939393939),
            'storage_id' => Storage::factory(),
            'good_group_id' => GoodGroup::factory()
        ];
    }
}
