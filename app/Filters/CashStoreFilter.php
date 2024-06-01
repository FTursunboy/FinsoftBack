<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class CashStoreFilter extends ModelFilter
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

    protected $model = CashStore::class;

    public function date($value) :CashStoreFilter
    {
        return $this->whereDate('date', $value);
    }

    public function senderStorage(int $id) :CashStoreFilter
    {
        return $this->where('sender_storage_id', $id);
    }
    public function month(int $id) :CashStoreFilter
    {
        return $this->where('month_id', $id);
    }

    public function cashRegister(int $id) :CashStoreFilter
    {
        return $this->where('cashRegister_id', $id);
    }

    public function organization(int $id) :CashStoreFilter
    {
        return $this->where('organization_id', $id);
    }

    public function recipientStorage(int $id) :CashStoreFilter
    {
        return $this->where('recipient_storage_id', $id);
    }

    public function responsiblePerson(int $id) :CashStoreFilter
    {
        return $this->where('responsiblePerson_id', $id);
    }
    public function employee(int $id) :CashStoreFilter
    {
        return $this->where('employee_id', $id);
    }

    public function balanceArticle(int $id) :CashStoreFilter
    {
        return $this->where('balance_article_id', $id);
    }

    public function author(int $id) :CashStoreFilter
    {
        return $this->where('author_id', $id);
    }

    public function operationType(int $id) :CashStoreFilter
    {
        return $this->where('operationType_id', $id);
    }

    public function active($value) :CashStoreFilter
    {
        return $value ? $this->where('active',  true) : $this->where('active', false);
    }

    public function deleted($value) :CashStoreFilter
    {
        return $value ? $this->where('deleted_at', '!=', null) : $this->where('deleted_at', null);
    }

    public function search(string $search) :CashStoreFilter
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

    public function sort() :CashStoreFilter
    {
        $filteredParams = $this->input();

        $relations = ['employee', 'counterparty', 'author', 'organizationBill', 'currency', 'organization', 'senderCashRegister', 'cashRegister'];

        return $this->traitSort($filteredParams, $this, $relations);
    }

}
