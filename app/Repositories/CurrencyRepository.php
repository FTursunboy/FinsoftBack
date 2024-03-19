<?php

namespace App\Repositories;

use App\DTO\CurrencyDTO;
use App\DTO\ExchangeRateDTO;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\PriceType;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Currency::class;

    public function index(array $data) :LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['exchangeRates']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(CurrencyDTO $dto) :Currency
    {
        return $this->model::create([
            'name' => $dto->name,
            'digital_code' => $dto->digital_code,
            'symbol_code' => $dto->symbol_code,
        ]);
    }

    public function update(Currency $currency, CurrencyDTO $dto) :Currency
    {
        $currency->update([
            'name' => $dto->name,
            'digital_code' => $dto->digital_code,
            'symbol_code' => $dto->symbol_code,
        ]);

        return $currency;
    }

    public function delete(Currency $currency)
    {
        $currency->exchangeRates()->delete();

        PriceType::where('currency_id', $currency->id)->delete();

        $currency->delete();
    }

    public function addExchangeRate(Currency $currency, ExchangeRateDTO $dto)
    {
        $currency->exchangeRates()->create([
            'date' => Carbon::parse($dto->date),
            'value' => $dto->value,
        ]);
    }

    public function deleteExchangeRate(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();
    }

    public function updateExchangeRate(ExchangeRate $exchangeRate, ExchangeRateDTO $dto)
    {
        $exchangeRate->update([
            'date' => $dto->date,
            'value' => $dto->value,
        ]);

        return $exchangeRate;
    }

    public function getCurrencyExchangeRateByCurrencyRate(Currency $currency): Collection
    {
        return $currency->exchangeRates()->get();
    }

    public function search(string $search)
    {
        return $this->model::whereAny(['name', 'symbol_code', 'digital_code'], 'like', '%' . $search . '%');
    }

    public function filter($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', $data['name']);
        })
            ->when($data['digital_code'], function ($query) use ($data) {
                return $query->where('digital_code', 'like', '%' . $data['digital_code'] . '%');
            })
            ->when($data['symbol_code'], function ($query) use ($data) {
                return $query->where('symbol_code', 'like', '%' . $data['symbol_code'] . '%');
            });
    }
}
