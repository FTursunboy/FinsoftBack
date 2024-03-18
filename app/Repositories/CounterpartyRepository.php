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

    private const ON_PAGE = 10;

    use Sort, FilterTrait;

    public function index(array $data) :LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

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

    public function search(string $search)
    {
        return $this->model::whereAny(['name', 'phone', 'address', 'email'], 'like', '%' . $search . '%')
            ->orWhereHas('roles', function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });
    }
}
