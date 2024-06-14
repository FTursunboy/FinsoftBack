<?php

namespace App\Console\Commands;

use App\Http\Resources\CashRegisterResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\OrganizationBillResource;
use App\Http\Resources\PositionResource;
use App\Http\Resources\PriceTypeResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use App\Models\CashRegister;
use App\Models\Counterparty;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Good;
use App\Models\Group;
use App\Models\OrganizationBill;
use App\Models\Position;
use App\Models\PriceType;
use App\Models\Storage;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Console\Command;

class ExportTOJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $units = UnitResource::collection(Unit::get());
        $organizationBill = OrganizationBillResource::collection(OrganizationBill::with(['currency', 'organization'])->get());
        $users = UserResource::collection(User::with(['organization', 'group'])->get());
        $counterparties = CounterpartyResource::collection(Counterparty::with(['roles', 'cpAgreements'])->get());
        $employees = EmployeeResource::collection(Employee::with(['group', 'position' ])->get());
        $positions = PositionResource::collection(Position::get());
        $priceTypes = PriceTypeResource::collection(PriceType::with(['currency'])->get());
        $currency = CurrencyResource::collection(Currency::with(['exchangeRates'])->get());
        $storage = StorageResource::collection(Storage::with(['organization', 'group', 'employeeStorage'])->get());
        $cashRegisters = CashRegisterResource::collection(CashRegister::with(['currency', 'responsiblePerson', 'organization'])->get());
        $goods = GoodResource::collection(Good::with(['location', 'barcodes', 'goodGroup', 'unit', 'images'])->get());
        $groups = GroupResource::collection(Group::with(['users', 'storages', 'employees'])->get());

        $data = [
            'units' => $units,
            'organizationBill' => $organizationBill,
            'users' => $users,
            'counterparties' => $counterparties,
            'employees' => $employees,
            'positions' => $positions,
            'priceTypes' => $priceTypes,
            'currency' => $currency,
            'storage' => $storage,
            'cashRegisters' => $cashRegisters,
            'goods' => $goods,
            'groups' => $groups
        ];

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents('datwa.json', $jsonData);
    }

}
