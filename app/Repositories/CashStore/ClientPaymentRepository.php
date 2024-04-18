<?php

namespace App\Repositories\CashStore;

use App\DTO\ClientPaymentDTO;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;

class ClientPaymentRepository implements CashStoreRepositoryInterface
{

    public $model = CashStore::class;


    public function clientPayment(ClientPaymentDTO $dto)
    {
        dd($dto);
    }
}
