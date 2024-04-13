<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\MovementDocument;
use App\Models\Storage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MovementDocumentFactory extends Factory
{
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

    public function uniqueNumber(): string
    {
        $lastRecord = Document::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

}
