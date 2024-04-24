<?php

namespace App\Filters;

use App\Traits\Sort;
use EloquentFilter\ModelFilter;

class CheckingAccountFilter extends ModelFilter
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

    public function date($value) :CheckingAccountFilter
    {
        return $this->whereDate('date', $value);
    }

    public function senderStorage(int $id) :CheckingAccountFilter
    {
        return $this->where('sender_storage_id', $id);
    }

    public function organization(int $id) :CheckingAccountFilter
    {
        return $this->where('organization_id', $id);
    }

    public function author(int $id) :CheckingAccountFilter
    {
        return $this->where('author_id', $id);
    }

    public function search(string $search) :CheckingAccountFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('checking_account_id', function ($query) use ($searchTerm) {
                    return $query->where('organization_bills.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }


    public function sort() :FiringFilter
    {
        $filteredParams = $this->input();

        $relations = ['employee', 'counterparty', 'author', 'organizationBill', 'currency', 'organization', 'senderCashRegister', 'checkingAccount', ''];

        return $this->traitSort($filteredParams, $this, $relations);
    }
}
