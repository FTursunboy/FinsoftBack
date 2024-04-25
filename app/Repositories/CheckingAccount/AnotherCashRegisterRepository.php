<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CheckingAccount\AccountablePersonRefundDTO;
use App\DTO\CheckingAccount\AnotherCashRegisterDTO;
use App\Enums\CashOperationType;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\AnotherCashRegisterRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AnotherCashRegisterRepository implements AnotherCashRegisterRepositoryInterface
{
    public $model = CheckingAccount::class;

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
            'doc_number' => $this->orderUniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'senderCashRegister_id' => $dto->sender_cash_register_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::AnotherCashRegister,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(AnotherCashRegisterDTO $dto, CheckingAccount $account)
    {
        $account->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'senderCashRegister_id' => $dto->sender_cash_register_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::AnotherCashRegister,
            'type' => $dto->type,
        ]);
    }

    public function orderUniqueNumber(): string
    {
        $lastRecord = CheckingAccount::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }
}
