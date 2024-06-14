<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\WithdrawalDTO;
use App\Enums\CashOperationType;
use App\Enums\MovementTypes;
use App\Events\CashStore\CashEvent;
use App\Events\CashStore\CounterpartySettlementEvent;
use App\Events\CashStore\OrganizationBillEvent;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\WithdrawalRepositoryInterface;
use App\Traits\DocNumberTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class WithdrawalRepository implements WithdrawalRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('operation_type', OperationType::WITHDRAW);

        $query = $query->filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'author', 'organizationBill'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(WithdrawalDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operationType_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id(),
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);
    }

    public function update(CashStore $cashStore, WithdrawalDTO $dto)
    {
        $cashStore->update([
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cash_register_id,
            'sum' => $dto->sum,
            'organizationBill_id' => $dto->organization_bill_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'type' => $dto->type,
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);

        return $cashStore;
    }

    public function approve(array $ids)
    {
        try {
            foreach ($ids['ids'] as $id) {
                $cashStore = CashStore::find($id);

                $cashStore->update(
                    ['active' => true]
                );

                CashEvent::dispatch($cashStore, MovementTypes::Income);
                OrganizationBillEvent::dispatch($cashStore, MovementTypes::Outcome);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }
}
