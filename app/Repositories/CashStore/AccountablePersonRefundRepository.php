<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class AccountablePersonRefundRepository implements AccountablePersonRefundRepositoryInterface
{

    use DocNumberTrait;

    public $model = CashStore::class;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::ACCOUNTABLE_PERSON_REFUND);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(AccountablePersonRefundDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operationType_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id(),
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
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
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);

        return $cashStore;
    }

}
