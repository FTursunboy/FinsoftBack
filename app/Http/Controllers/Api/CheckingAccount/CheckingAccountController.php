<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\ClientPaymentRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CashStore;
use App\Models\CheckingAccount;
use App\Models\OperationType;
use App\Repositories\CashStore\CashStoreRepository;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Repositories\Contracts\CheckingAccount\CheckingAccountRepositoryInterface;
use App\Traits\ApiResponse;

class CheckingAccountController extends Controller
{
    use ApiResponse;

    public function __construct(public CheckingAccountRepositoryInterface $repository) {}

    public function index(FilterRequest $request, string $type)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated(), $type)));
    }

    public function show(CheckingAccount $checkingAccount)
    {
       return $this->success(CheckingAccountResource::make($checkingAccount->load('organization', 'checkingAccount', 'counterparty', 'counterpartyAgreement', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'operationType')));
    }


    public function destroy(CheckingAccount $checkingAccount)
    {
        $checkingAccount->delete();

        return response()->json();
    }
}
