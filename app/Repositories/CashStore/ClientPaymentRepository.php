<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Enums\MovementTypes;
use App\Events\CashStore\CashEvent;
use App\Events\CashStore\CounterpartySettlementEvent;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Services\CashStore\CounterpartySettlementService;
use App\Traits\DocNumberTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ClientPaymentRepository implements ClientPaymentRepositoryInterface
{
    public $model = CashStore::class;

    use DocNumberTrait;

    public function index(array $data)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['organization', 'cashRegister', 'counterparty', 'counterpartyAgreement', 'author', 'currency'])->paginate($filteredParams['itemsPerPage']);
    }

    public function clientPayment(ClientPaymentDTO $dto)
    {
        return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $dto->date,
            'cashRegister_id' => $dto->cash_register_id,
            'organization_id' => $dto->organization_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operationType_id' => $dto->operation_type_id,
            'type' => $dto->type,
            'author_id' => Auth::id(),
            'sender' => $dto->sender,
            'recipient' => $dto->recipient,
        ]);
    }

    public function update(CashStore $cashStore, ClientPaymentDTO $dto)
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
                CounterpartySettlementEvent::dispatch($cashStore, MovementTypes::Outcome);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }
}
