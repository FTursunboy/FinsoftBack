<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\PriceSetUpDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodPlan;
use App\Models\PriceSetUp;
use App\Models\SalePlan;
use App\Models\SetUpPrice;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\PriceSetUpRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class PriceSetUpRepository implements PriceSetUpRepositoryInterface
{
    public $model = PriceSetUp::class;

    use DocNumberTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = PriceSetUp::filterData($data);

        return $this->model::query()->paginate($filterParams['itemsPerPage']) ;
    }

    public function store(PriceSetUpDTO $dto): PriceSetUp
    {
        $priceSetUp = PriceSetUp::create([
            'doc_number' => $this->uniqueNumber(),
            'author_id' => \Auth::id(),
            'organization_id' => $dto->organization_id,
            'start_date' => $dto->start_date,
            'basis' => $dto->basis,
            'comment' => $dto->comment
        ]);

        foreach ($dto->goods as $good) {
            SetUpPrice::create(
                [
                    'price_set_up_id' => $priceSetUp->id,
                    'good_id' => $good['good_id'],
                    'quantity' => $good['quantity'],
                    'new_price' => $good['new_price'],
                    'price_type_id' => $good['price_type_id'],
                    'old_price' => $good['old_price'],
                ]
            );
        }

        return $priceSetUp->load(['goodPrices', 'author', 'organization']);

    }

    public function update(PriceSetUp $barcode, PriceSetUpDTO $DTO): PriceSetUp
    {
        // TODO: Implement update() method.
    }

    public function delete(Barcode $barcode)
    {
        // TODO: Implement delete() method.
    }
}
