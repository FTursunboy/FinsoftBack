<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\PositionController;
use App\Models\CashRegister;
use App\Models\Category;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeStorage;
use App\Models\ExchangeRate;
use App\Models\Good;
use App\Models\GoodGroup;
use App\Models\Organization;
use App\Models\OrganizationBill;
use App\Models\Position;
use App\Models\PriceType;
use App\Models\Storage;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Category::factory(5)->create();
        Employee::factory(5)->create();
        CounterpartyAgreement::factory(5)->create();
        Counterparty::factory(5)->create();
        Currency::factory(5)->create();
        Document::factory(5)->create();
        Good::factory(5)->create();
        Organization::factory(5)->create();
        Position::factory(5)->create();
        PriceType::factory(5)->create();
        Storage::factory(5)->create();
        Unit::factory(5)->create();
        User::factory(5)->create();
        CashRegister::factory(5)->create();
        EmployeeStorage::factory(5)->create();
        ExchangeRate::factory(5)->create();
        OrganizationBill::factory(5)->create();

    }
}
