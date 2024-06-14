<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CheckingAccount\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\CashStoreRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class ClientPaymentRepository implements CashStoreRepositoryInterface
{
    public $model = CheckingAccount::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function clientPayment(ClientPaymentDTO $dto)
    {
       return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operationType_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(ClientPaymentDTO $dto, CheckingAccount $account)
    {
        $account->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
            'operationType_id' => $dto->operation_type_id,
        ]);
    }

}
