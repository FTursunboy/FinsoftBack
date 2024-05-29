<?php

namespace App\Repositories\Contracts;

use App\DTO\CurrencyDTO;
use App\DTO\ExchangeRateDTO;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\CurrencyIndexRequest;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Collection;
use Spiral\Tokenizer\InterfacesInterface;

interface CurrencyRepositoryInterface extends IndexInterface
{
    public function store(CurrencyDTO $dto) :Currency;

    public function update(Currency $currency, CurrencyDTO $dto) :Currency;

    public function addExchangeRate(Currency $currency, ExchangeRateDTO $dto);

    public function deleteExchangeRate(ExchangeRate $exchangeRate);

    public function updateExchangeRate(ExchangeRate $exchangeRate, ExchangeRateDTO $dto);

    public function getCurrencyExchangeRateByCurrencyRate(Currency $currency) :Collection;

    public function delete(Currency $currency);

    public function addDefaultCurrency(Currency $currency);

    public function export(array $data);
}
