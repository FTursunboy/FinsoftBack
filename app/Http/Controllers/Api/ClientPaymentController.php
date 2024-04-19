<?php

namespace App\Http\Controllers\Api;

use App\DTO\ClientPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\ClientPaymentRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Traits\ApiResponse;

class ClientPaymentController extends Controller
{
    use ApiResponse;

    public function __construct(public CashStoreRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', CashStore::class);

        return CashStoreResource::collection($this->repository->index($request->validated()));
    }

    public function store(ClientPaymentRequest $request)
    {
        $this->authorize('create', CashStore::class);

        return $this->success($this->repository->clientPayment(ClientPaymentDTO::fromRequest($request)));
    }

    public function show(CashStore $cashStore)
    {
        $this->authorize('view', $cashStore);

        return new CashStoreResource($cashStore);
    }

    public function update(ClientPaymentRequest $request, CashStore $cashStore)
    {
        $this->authorize('update', $cashStore);

        $cashStore->update($request->validated());

        return new CashStoreResource($cashStore);
    }

    public function destroy(CashStore $cashStore)
    {
        $this->authorize('delete', $cashStore);

        $cashStore->delete();

        return response()->json();
    }
}
