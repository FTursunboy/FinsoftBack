<?php

namespace App\Repositories\CheckingAccount;

use App\DTO\CheckingAccount\OtherExpensesDTO;
use App\Enums\CashOperationType;
use App\Models\BalanceArticle;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\OtherExpensesRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OtherExpensesRepository implements OtherExpensesRepositoryInterface
{

    public $model = CheckingAccount::class;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', CashOperationType::OtherExpenses);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'organizationBill', 'counterparty', 'author', 'employee'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(OtherExpensesDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->orderUniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'checking_account_id' => $dto->checking_account_id,
            'sum' => $dto->sum,
            'balance_article_id' => $dto->balance_article_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => CashOperationType::OtherExpenses,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function balanceArticle(array $data)
    {
        $filteredParams = BalanceArticle::filterData($data);

        return BalanceArticle::paginate($filteredParams['itemsPerPage']);
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
