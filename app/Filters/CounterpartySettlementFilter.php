<?php

namespace App\Filters;

use App\Models\CounterpartySettlement;
use App\Traits\Sort;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class CounterpartySettlementFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];
    use Sort {
        sort as traitSort;
    }

    protected $model = CounterpartySettlement::class;

    public function date($value) :CounterpartySettlementFilter
    {
        return $this->whereDate('date', $value);
    }

    public function organization(int $id) :CounterpartySettlementFilter
    {
        return $this->where('organization_id', $id);
    }

    public function counterparty(int $id) :CounterpartySettlementFilter
    {
        return $this->where('counterparty', $id);
    }

    public function counterpartyAgreement(int $id) :CounterpartySettlementFilter
    {
        return $this->where('counterparty', $id);
    }

    public function search(string $search) :CounterpartySettlementFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->whereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('counterparty_agreement_id', function ($query) use ($searchTerm) {
                    return $query->where('counterparty_agreements.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('counterparty_id', function ($query) use ($searchTerm) {
                    return $query->where('counterparties.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }


    public function sort() :CounterpartySettlementFilter
    {
        $filteredParams = $this->input();

        $relations = ['employee', 'counterparty', 'author', 'organizationBill', 'currency', 'organization', 'senderCashRegister', 'checkingAccount'];

        return $this->traitSort($filteredParams, $this, $relations);
    }

    public function startDate($value)
    {
        $date = Carbon::parse($value);
        return $this->where('date', '>=', $date);
    }

    public function endDate($value)
    {
        $date = Carbon::parse($value);

        return $this->where('date', '<=', $date);
    }

}
