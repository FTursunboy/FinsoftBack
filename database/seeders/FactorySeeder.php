<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\PositionController;
use App\Models\CashRegister;
use App\Models\Category;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Department;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeStorage;
use App\Models\ExchangeRate;
use App\Models\Good;
use App\Models\GoodGroup;
use App\Models\Hiring;
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
//        Document::factory(200)->create();
        Category::factory(1)->create();
        Employee::factory(1)->create();
        CounterpartyAgreement::factory(1)->create();
        Counterparty::factory(1)->create();
    //    Currency::factory(1)->create();

        Good::factory(6)->create();
        Organization::factory(1)->create();
        Position::factory(1)->create();
        PriceType::factory(1)->create();
        Storage::factory(1)->create();
        Unit::factory(1)->create();
        User::factory(1)->create();
        CashRegister::factory(1)->create();
        EmployeeStorage::factory(1)->create();
        ExchangeRate::factory(1)->create();
        OrganizationBill::factory(1)->create();
        Department::factory(1)->create();
//        Hiring::factory(1)->create();
    }
}
