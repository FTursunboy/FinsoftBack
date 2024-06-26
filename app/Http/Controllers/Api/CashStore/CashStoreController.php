<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\ClientPaymentRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Models\OperationType;
use App\Repositories\CashStore\CashStoreRepository;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

    public function destroy(CashStore $cashStore)
    {
        $cashStore->delete();

        return response()->json();
    }

    public function massDelete(IdRequest $request)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $repository)
    {
        return $this->success($repository->massRestore(new CashStore(), $request->validated()));
    }

    public function getOperationTypes(Request $request)
    {
        return $this->success(OperationType::where('type', $request->type)->get());
    }

}
