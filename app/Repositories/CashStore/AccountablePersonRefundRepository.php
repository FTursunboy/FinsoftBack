<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AccountablePersonRefundRepository implements AccountablePersonRefundRepositoryInterface
{

    public $model = CashStore::class;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::AccountablePersonRefund);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(AccountablePersonRefundDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->orderUniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cash_register_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::AccountablePersonRefund,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, AccountablePersonRefundDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cash_register_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

    public function orderUniqueNumber(): string
    {
        $lastRecord = CashStore::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

}