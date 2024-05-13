<?php

namespace Database\Factories;

use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Organization;
use App\Models\Status;
use App\Models\Storage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{

    protected $model = Document::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'organization_id' => Organization::factory(),
            'date' => Carbon::parse($this->faker->date),
            'counterparty_id' => Counterparty::factory(),
            'counterparty_agreement_id' => CounterpartyAgreement::factory(),
            'storage_id' => Storage::factory(),
            'author_id' => User::factory(),
            'status_id' => $this->faker->randomElement([Status::CLIENT_RETURN, Status::CLIENT_PURCHASE, Status::PROVIDER_RETURN, Status::PROVIDER_PURCHASE]),
            'doc_number' => time() . rand(1, 1000000),
            'currency_id' => Currency::inRandomOrder()->first()->id
        ];
    }

    private function getLastDocumentNumber() :string
    {
        $lastRecord = Document::query()->orderBy('doc_number', 'desc')->first();

        if (! $lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int) $lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

}
