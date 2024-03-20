<?php

namespace App\Repositories;

use App\DTO\CashRegisterDTO;
use App\DTO\DocumentDTO;
use App\Models\CashRegister;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\IndexInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class CashRegisterRepository implements CashRegisterRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = CashRegister::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['organization', 'currency', 'responsiblePerson']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(CashRegisterDTO $DTO)
    {
        $this->model::create([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'organization_id' => $DTO->organization_id,
            'responsible_person_id' => $DTO->responsible_person_id
        ]);
    }

    public function update(CashRegister $cashRegister, CashRegisterDTO $DTO): CashRegister
    {
        $cashRegister->update([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'organization_id' => $DTO->organization_id,
            'responsible_person_id' => $DTO->responsible_person_id
        ]);

        return $cashRegister->load(['currency', 'organization', 'responsiblePerson']);
    }

    public function search(array $data)
    {
        return $this->model::where('name', 'like', '%' . $data['search'] . '%')
            ->where(function ($query) use ($data) {
                $query->orWhereHas('currency', function ($query) use ($data) {
                    return $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                    ->orWhereHas('organization', function ($query) use ($data) {
                        return $query->where('name', 'like', '%' . $data['search'] . '%');
                    })
                    ->orWhereHas('responsiblePerson', function ($query) use ($data) {
                        return $query->where('name', 'like', '%' . $data['search'] . '%');
                    });
            });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['responsible_person_id'], function ($query) use ($data) {
                return $query->where('responsible_person_id', $data['responsible_person_id']);
            })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            });
    }
}
