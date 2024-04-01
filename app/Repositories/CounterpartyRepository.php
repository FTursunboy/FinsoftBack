<?php

namespace App\Repositories;

use App\DTO\CounterpartyDTO;
use App\Models\Counterparty;
use App\Repositories\Contracts\CounterpartyRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class CounterpartyRepository implements CounterpartyRepositoryInterface
{
    public $model = Counterparty::class;

    use Sort, FilterTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(CounterpartyDTO $DTO)
    {
        $model = $this->model::create([
            'name' => $DTO->name,
            'address' => $DTO->address,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
        ]);

        $model->roles()->attach($DTO->roles);
    }

    public function update(Counterparty $counterparty, CounterpartyDTO $DTO): Counterparty
    {
        $counterparty->update([
            'name' => $DTO->name,
            'address' => $DTO->address,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
        ]);
        $counterparty->roles()->detach();
        $counterparty->roles()->attach($DTO->roles);

        return $counterparty;
    }

    public function delete(Counterparty $counterparty)
    {


        $counterparty->delete();
    }

    public function massDelete(array $ids)
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->model::whereIn('id', $ids['ids'])->update([
            'deleted_at' => Carbon::now()
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function search(array $filterParams)
    {
        $searchTerm = explode(' ', $filterParams['search']);

        return $this->model::where(function ($query) use ($searchTerm) {
            $query->whereAny(['name', 'phone', 'address', 'email'], 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('roles', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', '%' . $data['name'] . '%');
        })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            })
            ->when($data['address'], function ($query) use ($data) {
                return $query->where('address', 'like', '%' . $data['address'] . '%');
            })
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['roles'], function ($query) use ($data) {
                return $query->whereHas('roles', function ($query) use ($data) {
                    return $query->whereIn('role_id', $data['roles']);
                });
            });
    }
}
