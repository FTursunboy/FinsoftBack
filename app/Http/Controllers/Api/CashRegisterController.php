<?php

namespace App\Http\Controllers\Api;

use App\DTO\CashRegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashRegister\CashRegisterRequest;
use App\Http\Requests\Api\CashRegister\CashRegisterUpdateRequest;
use App\Http\Requests\Api\CashRegister\FilterRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CashRegisterResource;
use App\Models\CashRegister;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class CashRegisterController extends Controller
{
    use ApiResponse;

    public function __construct(public CashRegisterRepositoryInterface $repository)
    {

    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashRegisterResource::collection($this->repository->index($request->validated())));
    }

    public function show(CashRegister $cashRegister)
    {
        return $this->success(CashRegisterResource::make($cashRegister->load(['currency', 'organization'])));
    }

    public function store(CashRegisterRequest $request)
    {
        return $this->created($this->repository->store(CashRegisterDTO::fromRequest($request)));
    }

    public function update(CashRegister $cashRegister, CashRegisterUpdateRequest $request)
    {
        return $this->success(CashRegisterResource::make($this->repository->update($cashRegister, CashRegisterDTO::fromRequest($request))));
    }

    public function destroy(CashRegister $cashRegister)
    {
        return $this->deleted($cashRegister->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new CashRegister(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new CashRegister(), $request->validated()));
    }

    public function export(FilterRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }
}
