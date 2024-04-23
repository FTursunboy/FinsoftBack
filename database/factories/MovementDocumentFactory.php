<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\MovementDocument;
use App\Models\Storage;
use App\Models\User;
use App\Traits\DocNumberTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MovementDocumentFactory extends Factory
{
    use DocNumberTrait;
    protected $model = MovementDocument::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'uuid' => $this->faker->uuid(),
            'doc_number' => $this->uniqueNumber(),
            'date' => Carbon::now(),
            'organization_id' => $this->faker->randomNumber(),
            'sender_storage_id' => Storage::factory(),
            'recipient_storage_id' => Storage::factory(),
            'author_id' => User::factory(),
            'comment' => $this->faker->word(),
        ];
    }

}
