<?php

namespace App\Http\Controllers\Api;

use App\DTO\CurrencyDTO;
use App\DTO\ExchangeRateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Currency\CurrencyRequest;
use App\Http\Requests\Api\Currency\FilterRequest;
use App\Http\Requests\Api\ExchangeRequest;
use App\Http\Requests\Api\ExchangeUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\ExchangeRateResource;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    use ApiResponse;

    public function __construct(public CurrencyRepositoryInterface $repository){ }

    public function index(FilterRequest $request) :JsonResponse
    {
        return $this->paginate(CurrencyResource::collection($this->repository->index($request->validated())));
    }

    public function show(Currency $currency)
    {
        return $this->success(CurrencyResource::make($currency));
    }

    public function store(CurrencyRequest $request) :JsonResponse
    {
       return $this->created(CurrencyResource::make($this->repository->store(CurrencyDTO::fromRequest($request))));
    }

    public function update(Currency $currency, CurrencyRequest $request) :JsonResponse
    {
        return $this->success(CurrencyResource::make($this->repository->update($currency,  CurrencyDTO::fromRequest($request))));
    }

    public function addExchangeRate(Currency $currency, ExchangeRequest $request) :JsonResponse
    {
        return $this->created($this->repository->addExchangeRate($currency, ExchangeRateDTO::fromRequest($request)));
    }

    public function removeExchangeRate(ExchangeRate $exchangeRate) :JsonResponse
    {
        return $this->success($exchangeRate->delete());
    }

    public function updateExchange(ExchangeRate $exchangeRate, ExchangeUpdateRequest $request) :JsonResponse
    {
        return $this->success(ExchangeRateResource::make($this->repository->updateExchangeRate($exchangeRate, ExchangeRateDTO::fromRequest($request))));
    }

    public function getExchangeRateByCurrencyId(Currency $currency) :JsonResponse
    {
        return $this->success(ExchangeRateResource::make($this->repository->getCurrencyExchangeRateByCurrencyRate($currency)));
    }

    public function destroy(Currency $currency) :JsonResponse
    {
        return $this->success($this->repository->delete($currency));
    }

    public function restore(Currency $currency)
    {
        return $this->success($currency->restore());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($delete->massDelete(new Currency(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Currency(), $request->validated()));
    }

    public function massDeleteCurrencyRate(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($delete->massDelete(new ExchangeRate(), $request->validated()));
    }


    public function massRestoreCurrencyRate(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new ExchangeRate(), $request->validated()));
    }

    public function addDefaultCurrency(Currency $currency)
    {
        if (Currency::default()->exists()) {
            return $this->error('Уже есть дефолтная валюта');
        }

        return $this->success($this->repository->addDefaultCurrency($currency));
    }
}
