<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\SalaryPaymentDTO;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\SalaryPaymentRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Support\Facades\Auth;

class SalaryPaymentRepository implements SalaryPaymentRepositoryInterface
{

    use DocNumberTrait;

    public $model = CashStore::class;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::SALARY_PAYMENT);
dd($query->get());
        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(SalaryPaymentDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'month_id' => $dto->month_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id()
        ]);
    }

    public function update(CashStore $cashStore, SalaryPaymentDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'employee_id' => $dto->employee_id,
            'month_id' => $dto->month_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
        ]);

        return $cashStore;
    }

}
