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
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ClientPaymentController extends Controller
{
    use ApiResponse;

    public function __construct(public ClientPaymentRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', CashStore::class);

        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(ClientPaymentRequest $request)
    {
        $this->authorize('create', CashStore::class);

        return $this->created($this->repository->clientPayment(ClientPaymentDTO::fromRequest($request)));
    }

    public function show(CashStore $cashStore)
    {
        $this->authorize('view', $cashStore);

        return new CashStoreResource($cashStore);
    }

    public function update(CashStore $cashStore, ClientPaymentRequest $request)
    {
        return $this->success(CashStoreResource::make($this->repository->update($cashStore, ClientPaymentDTO::fromRequest($request))));
    }

    public function destroy(CashStore $cashStore)
    {
        $this->authorize('delete', $cashStore);

        $cashStore->delete();

        return response()->json();
    }

    public function getOperationTypes(Request $request)
    {
        return $this->success(OperationType::where('type', $request->type)->get());
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }
}
