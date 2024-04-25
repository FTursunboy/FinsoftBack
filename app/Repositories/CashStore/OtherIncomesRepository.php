<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\OtherIncomesDTO;
use App\Enums\CashOperationType;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\OtherIncomesRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class OtherIncomesRepository implements OtherIncomesRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::OtherIncomes);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'organizationBill', 'counterparty', 'author', 'employee'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(OtherIncomesDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'balance_article_id' => $dto->balance_article_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::OtherIncomes,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, OtherIncomesDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'organizationBill_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'balance_article_id' => $dto->balance_article_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::OtherIncomes,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

}
