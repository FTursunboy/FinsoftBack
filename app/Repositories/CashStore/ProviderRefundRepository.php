<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\ProviderRefundDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\ProviderRefundRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class ProviderRefundRepository implements ProviderRefundRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::ProviderRefund);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'counterpartyAgreement', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(ProviderRefundDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::ProviderRefund,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, ProviderRefundDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::ProviderRefund,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

}
