<?php

namespace Database\Factories;

use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Organization;
use App\Models\PriceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CounterpartyAgreementFactory extends Factory
{
    protected $model = CounterpartyAgreement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'contract_number' => $this->faker->randomNumber() . time(),
            'date' => $this->faker->date(),
            'organization_id' => Organization::factory(),
            'counterparty_id' => Counterparty::factory(),
            'contact_person' => $this->faker->name(),
            'currency_id' => Currency::factory(),
            'payment_id' => Currency::factory(),
            'price_type_id' => PriceType::factory(),
            'comment' => $this->faker->text()
        ];
    }
}
