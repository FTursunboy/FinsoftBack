<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\ClientPaymentRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\CashStore\CashStoreRepository;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Traits\ApiResponse;

class CashStoreController extends Controller
{
    use ApiResponse;

    public function __construct(public CashStoreRepositoryInterface $repository) {}

    public function index(FilterRequest $request, string $type)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated(), $type)));
    }

    public function show(CashStore $cashStore)
    {
       return $this->success(CashStoreResource::make($cashStore->load('organization', 'cashRegister', 'counterparty', 'counterpartyAgreement', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'balanceArticle', 'month', 'operationType')));
    }

    public function destroy(CashStore $cashStore)
    {
        $cashStore->delete();

        return response()->json();
    }

    public function getOperationTypes() {
        return $this->success(OperationType::get());
    }
}
