<?php

namespace App\Repositories\CheckingAccount;

use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\CheckingAccountRepositoryInterface;

class CheckingAccountRepository implements CheckingAccountRepositoryInterface
{
    public $model = CheckingAccount::class;

    public function index(array $data, string $type)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('type', $type);

        $query = $query->filter($filteredParams);

        return $query->with([
            'organization', 'checkingAccount', 'counterparty', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'operationType'
        ])->paginate($filteredParams['itemsPerPage']);
    }


}
