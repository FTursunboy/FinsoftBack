<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CheckingAccount\AccountablePersonRefundDTO;
use App\Enums\CashOperationType;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\AccountablePersonRefundRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AccountablePersonRefundRepository implements AccountablePersonRefundRepositoryInterface
{

    public $model = CheckingAccount::class;

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
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::AccountablePersonRefund,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(AccountablePersonRefundDTO $dto, CheckingAccount $account)
    {
        $account->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::AccountablePersonRefund,
            'type' => $dto->type
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
