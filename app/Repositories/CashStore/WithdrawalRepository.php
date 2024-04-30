<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\WithdrawalDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\WithdrawalRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class WithdrawalRepository implements WithdrawalRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::WITHDRAW);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'author', 'organizationBill'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(WithdrawalDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, WithdrawalDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }
}
