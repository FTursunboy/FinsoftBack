<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CheckingAccount\ProviderRefundDTO;
use App\DTO\CheckingAccount\WithdrawalDTO;
use App\Enums\CashOperationType;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\WithdrawalRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class WithdrawalRepository implements WithdrawalRepositoryInterface
{
    public $model = CheckingAccount::class;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::WithDraw);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'author', 'organizationBill'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(WithdrawalDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->orderUniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::WithDraw,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(WithdrawalDTO $dto, CheckingAccount $account)
    {
        return $account->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::WithDraw,
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
