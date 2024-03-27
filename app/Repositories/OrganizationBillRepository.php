<?php

namespace App\Repositories;

use App\DTO\CurrencyDTO;
use App\DTO\ExchangeRateDTO;
use App\DTO\OrganizationBillDTO;
use App\Models\CashRegister;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\OrganizationBill;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\OrganizationBillRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrganizationBillRepository implements OrganizationBillRepositoryInterface
{
    use Sort;

    public $model = OrganizationBill::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['organization', 'currency']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(OrganizationBillDTO $dto)
    {
        OrganizationBill::create(get_object_vars($dto));
    }

    public function update(OrganizationBill $bill, OrganizationBillDTO $dto): OrganizationBill
    {
        $bill->update(get_object_vars($dto));

        return $bill->load(['currency', 'organization']);
    }

    public function search(array $filterParams)
    {
        if (!$filterParams['search']) {
            return $this->model::query();
        }

        $query = $this->model::whereAny(['name', 'bill_number', 'date', 'comment'], 'like', '%' . $filterParams['search'] . '%');

       // return $query;
        return $query->orWhere(function ($query) use ($filterParams) {
            return $query->OrWhere(function ($query) use ($filterParams) {
                return $query->orWhereHas('currency', function ($query) use ($filterParams) {
                    return $query->where('name', 'like', '%' . $filterParams['search'] . '%');
                })
                    ->orWhereHas('organization', function ($query) use ($filterParams) {
                        return $query->where('name', 'like', '%' . $filterParams['search'] . '%');
                    });
            });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['bill_number'], function ($query) use ($data) {
                return $query->where('bill_number', 'like', '%' . $data['bill_number'] . '%');
            })
            ->when($data['date'], function ($query) use ($data) {
                return $query->where('date', $data['date']);
            })
            ->when($data['comment'], function ($query) use ($data) {
                return $query->where('comment', 'like', '%' . $data['comment'] . '%');
            });
    }
}
