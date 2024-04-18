<?php

namespace App\Repositories\CashStore;

use App\DTO\ClientPaymentDTO;
use App\Models\CashStore;
use App\Models\OrderDocument;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;

class ClientPaymentRepository implements CashStoreRepositoryInterface
{

    public $model = CashStore::class;

    public function clientPayment(ClientPaymentDTO $dto)
    {
        $this->model::create([
            'doc_number' => $this->orderUniqueNumber(),
            'date' => $dto->date,
            'organization_id' => $dto->organization_id,
            'cashRegister_id' => $dto->cashRegister_id,
            'sum' => $dto->sum,
            'counterparty_id' => $dto->counterparty_id,
            'counterparty_agreement_id' => $dto->counterparty_agreement_id,
            'basis' => $dto->basis,
            'comment' => $dto->comment,
            'operation_type' => $dto->operation_type,
            'type' => $dto->type
        ]);
    }

    public function orderUniqueNumber(): string
    {
        $lastRecord = CashStore::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

}
