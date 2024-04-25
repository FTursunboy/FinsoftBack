<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class AnotherCashRegisterRepository implements AnotherCashRegisterRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::AnotherCashRegister);

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
            'operation_type' => CashOperationType::AnotherCashRegister,
            'type' => $dto->type,
            'author_id' => Auth::id()
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
            'operation_type' => CashOperationType::AnotherCashRegister,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

}
