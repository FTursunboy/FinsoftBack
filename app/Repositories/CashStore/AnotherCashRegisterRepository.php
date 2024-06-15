<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\Enums\CashOperationType;
use App\Enums\MovementTypes;
use App\Events\CashStore\CashEvent;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Traits\DocNumberTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AnotherCashRegisterRepository implements AnotherCashRegisterRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::ANOTHER_CASH_REGISTER);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'author', 'senderCashRegister'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(AnotherCashRegisterDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'senderCashRegister_id' => $dto->sender_cash_register_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operationType_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id(),
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);
    }

    public function update(CashStore $cashStore, AnotherCashRegisterDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'senderCashRegister_id' => $dto->sender_cash_register_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);

        return $cashStore;
    }

}
