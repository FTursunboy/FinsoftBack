<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\CheckingAccountRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CheckingAccountRepository implements CheckingAccountRepositoryInterface
{

    public $model = CheckingAccount::class;

    public function index(array $data, string $type)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('type', $type);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'checkingAccount', 'counterparty', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee'])->paginate($filteredParams['itemsPerPage']);
    }
}
