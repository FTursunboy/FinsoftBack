<?php

namespace Database\Factories;

use App\Models\CashStore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CashStoreFactory extends Factory
{
    protected $model = CashStore::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'doc_number' => $this->faker->word(),
            'date' => $this->faker->word(),
            'organization_id' => $this->faker->word(),
            'cashregister_id' => $this->faker->word(),
            'sum' => $this->faker->word(),
            'counterparty_id' => $this->faker->word(),
            'counterparty_agreement_id' => $this->faker->word(),
            'basis' => $this->faker->word(),
            'comment' => $this->faker->word(),
            'author_id' => $this->faker->word(),
            'orgnizationBill_id' => $this->faker->randomNumber(),
            'senderCashRegister_id' => $this->faker->randomNumber(),
            'employee_id' => $this->faker->randomNumber(),
            'balanceKey_id' => $this->faker->word(),
        ];
    }
}
