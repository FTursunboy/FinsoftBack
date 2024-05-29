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
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Currency::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->getQuery($filteredParams);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function getQuery(array $filteredParams)
    {
        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        return $this->sort($filteredParams, $query, ['exchangeRates']);

    }

    public function store(CurrencyDTO $dto): Currency
    {
        return $this->model::create([
            'name' => $dto->name,
            'digital_code' => $dto->digital_code,
            'symbol_code' => $dto->symbol_code,
        ]);
    }

    public function update(Currency $currency, CurrencyDTO $dto): Currency
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
        $searchTerm = explode(' ', $search);

        return $this->model::whereAny(['name', 'symbol_code', 'digital_code'], 'like', '%' . implode('%', $searchTerm) . '%');
    }

    public function filter($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', '%' . $data['name'] . '%');
        })
            ->when($data['digital_code'], function ($query) use ($data) {
                return $query->where('digital_code', 'like', '%' . $data['digital_code'] . '%');
            })
            ->when($data['symbol_code'], function ($query) use ($data) {
                return $query->where('symbol_code', 'like', '%' . $data['symbol_code'] . '%');
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

    public function addDefaultCurrency(Currency $currency)
    {
        $currency->update([
            'default' => true
        ]);
    }


    public function export(array $data): string
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->getQuery($filteredParams);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'Цифровой код', 'Символьный код', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->digital_code,
                $row->symbol_code,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }

}
