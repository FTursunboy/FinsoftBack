<?php

namespace App\Http\Controllers\Api;

use App\DTO\CounterpartyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Counterparty\CounterpartyRequest;
use App\Http\Requests\Api\Counterparty\CounterpartyUpdateRequest;
use App\Http\Requests\Api\Counterparty\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CounterpartyResource;
use App\Models\Counterparty;
use App\Repositories\Contracts\CounterpartyRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CounterpartyController extends Controller
{
    use ApiResponse;

    public function __construct(public CounterpartyRepositoryInterface $repository)
    {
        $this->authorizeResource(Counterparty::class, 'counterparty');
    }

    public function index(FilterRequest $request) :JsonResponse
    {
        return $this->paginate(CounterpartyResource::collection($this->repository->index($request->validated())));
    }

    public function show(Counterparty $counterparty)
    {
        return $this->success(CounterpartyResource::make($counterparty));
    }

    public function store(CounterpartyRequest $request) :JsonResponse
    {
        return $this->created($this->repository->store(CounterpartyDTO::fromRequest($request)));
    }

    public function update(Counterparty $counterparty, CounterpartyUpdateRequest $request) :JsonResponse
    {
        return $this->success(CounterpartyResource::make($this->repository->update($counterparty, CounterpartyDTO::fromRequest($request))));
    }

    public function destroy(Counterparty $counterparty)
    {
        return $this->deleted($this->repository->delete($counterparty));
    }

    public function restore(Counterparty $counterparty)
    {
        return $this->success($counterparty->restore());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($delete->massDelete(new Counterparty(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Counterparty(), $request->validated()));
    }
}

