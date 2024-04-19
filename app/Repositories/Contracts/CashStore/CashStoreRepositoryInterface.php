<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\ClientPaymentDTO;
use App\DTO\LoginDTO;
use App\DTO\WithdrawalDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface CashStoreRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);
    public function withdrawal(WithdrawalDTO $DTO);
}
