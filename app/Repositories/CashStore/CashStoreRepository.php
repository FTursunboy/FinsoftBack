<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class CashStoreRepository implements CashStoreRepositoryInterface
{

    public $model = CashStore::class;

    public function index(array $data, string $type)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('type', $type);

        $query = $query->filter($filteredParams);

        return $query->with([
            'organization', 'cashRegister', 'counterparty', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'responsiblePerson', 'operationType'
        ])->paginate($filteredParams['itemsPerPage']);
    }
}
