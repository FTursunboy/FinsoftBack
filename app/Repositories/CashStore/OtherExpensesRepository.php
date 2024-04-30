<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\DTO\CashStore\OtherExpensesDTO;
use App\Enums\CashOperationType;
use App\Models\BalanceArticle;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use App\Repositories\Contracts\CashStore\OtherExpensesRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class OtherExpensesRepository implements OtherExpensesRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::OTHER_INCOMES);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'organizationBill', 'counterparty', 'author', 'employee'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(OtherExpensesDTO $dto)
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
            'operation_type_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, OtherExpensesDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'organizationBill_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'balance_article_id' => $dto->balance_article_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

    public function balanceArticle(array $data)
    {
        $filteredParams = BalanceArticle::filterData($data);

        return BalanceArticle::paginate($filteredParams['itemsPerPage']);
    }
}
