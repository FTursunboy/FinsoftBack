<?php

namespace App\Repositories;

use App\DTO\PriceSetUpDTO;
use App\Models\PriceSetUp;
use App\Models\SetUpPrice;
use App\Repositories\Contracts\PriceSetUpRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class PriceSetUpRepository implements PriceSetUpRepositoryInterface
{
    use Sort, FilterTrait, DocNumberTrait;

    public $model = PriceSetUp::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['organization', 'author', 'setupGoods', 'setupGoods.good', 'setupGoods.priceType']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(PriceSetUpDTO $DTO)
    {
        return DB::transaction(function () use ($DTO) {
            $model = $this->model::create([
                'start_date' => $DTO->start_date,
                'organization_id' => $DTO->organization_id,
                'comment' => $DTO->comment,
                'basis' => $DTO->basis,
                'doc_number' => $this->uniqueNumber(),
                'author_id' => Auth::id(),
            ]);

            SetUpPrice::insert($this->setupGoods($DTO->goods, $model));
        });
    }

    public function update(PriceSetUp $priceSetUp, PriceSetUpDTO $DTO)
    {

    }

    public function setupGoods(array $goods, PriceSetUp $priceSetUp): array
    {
        $data = [];
        foreach ($goods as $good) {
            foreach ($good['prices'] as $price) {
                $data[] = [
                    'good_id' => $good['good_id'],
                    'price_type_id' => $price['price_type_id'],
                    'old_price' => $price['old_price'],
                    'new_price' => $price['new_price'],
                    'price_set_up_id' => $priceSetUp->id
                ];
            }
        }

        return $data;
    }

    public function search(string $search)
    {
        return $this->model::OrWhere(function ($query) use ($search) {
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['organization'], function ($query) use ($data) {
            return $query->where('organization', $data['organization']);
        })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

}
