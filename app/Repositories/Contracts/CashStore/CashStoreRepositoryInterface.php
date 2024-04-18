<?php

namespace App\Repositories\Contracts;

use App\DTO\ClientPaymentDTO;
use App\DTO\LoginDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface CashStoreRepositoryInterface
{
    public function clientPayment(ClientPaymentDTO $dto);

}
