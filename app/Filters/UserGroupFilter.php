<?php

namespace App\Filters;

use App\Models\Group;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;

class UserGroupFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */

    use Sort {
        sort as traitSort;
    }

    protected $model = Group::class;

    public function organization($value) :UserGroupFilter
    {
        return $this->whereDate('date', $value);
    }

    public function senderStorage(int $id) :UserGroupFilter
    {
        return $this->where('sender_storage_id', $id);
    }
    public function month(int $id) :UserGroupFilter
    {
        return $this->where('month_id', $id);
    }


    public function recipientStorage(int $id) :UserGroupFilter
    {
        return $this->where('recipient_storage_id', $id);
    }

    public function responsiblePerson(int $id) :UserGroupFilter
    {
        return $this->where('responsiblePerson_id', $id);
    }
    public function employee(int $id) :UserGroupFilter
    {
        return $this->where('employee_id', $id);
    }

    public function balanceArticle(int $id) :UserGroupFilter
    {
        return $this->where('balance_article_id', $id);
    }

    public function author(int $id) :UserGroupFilter
    {
        return $this->where('author_id', $id);
    }

    public function operationType(int $id) :UserGroupFilter
    {
        return $this->where('operationType_id', $id);
    }

    public function search(string $search) :UserGroupFilter
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
                ->orWhereHas('cashRegister', function ($query) use ($searchTerm) {
                    return $query->where('cash_registers.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function sort() :UserGroupFilter
    {
        $filteredParams = $this->input();

        $relations = ['employee', 'counterparty', 'author', 'organizationBill', 'currency', 'organization', 'senderCashRegister', 'cashRegister'];

        return $this->traitSort($filteredParams, $this, $relations);
    }

}
