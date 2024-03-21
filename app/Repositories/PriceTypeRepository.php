<?php

namespace App\Repositories;

use App\DTO\CurrencyDTO;
use App\DTO\DocumentDTO;
use App\DTO\ExchangeRateDTO;
use App\DTO\PriceTypeDTO;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\PriceType;
use App\Repositories\Contracts\PriceTypeRepository as PriceTypeRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PriceTypeRepository implements PriceTypeRepositoryInterface
{
    const ON_PAGE = 10;

    use Sort, FilterTrait;

    public $model = PriceType::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['currency']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(PriceTypeDTO $DTO)
    {
        $this->model::create([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'description' => $DTO->description
        ]);
    }

    public function update(PriceType $priceType, PriceTypeDTO $DTO): PriceType
    {
        $priceType->update([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'description' => $DTO->description
        ]);

        return $priceType->load('currency');
    }

    public function search(string $search)
    {
        return $this->model::whereAny(['name', 'description'], 'like', '%' . $search . '%')
            ->where(function ($query) use ($search) {
                return $query->orWhereHas('currency', function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                });
            });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['description'], function ($query) use ($data) {
                return $query->where('description', 'like', '%' . $data['description'] . '%');
            });
    }
}
