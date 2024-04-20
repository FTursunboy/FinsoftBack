<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\ClientPaymentDTO;
use App\DTO\LoginDTO;
use App\DTO\WithdrawalDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface WithdrawalRepositoryInterface
{
    public function index(array $data);
    public function store(WithdrawalDTO $dto);
}
