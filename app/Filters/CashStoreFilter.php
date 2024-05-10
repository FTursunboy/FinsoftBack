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

    public function recipientStorage(int $id) :CashStoreFilter
    {
        return $this->where('recipient_storage_id', $id);
    }

    public function organization(int $id) :CashStoreFilter
    {
        return $this->where('organization_id', $id);
    }

    public function author(int $id) :CashStoreFilter
    {
        return $this->where('author_id', $id);
    }

    public function operationType(string $type) :CashStoreFilter
    {
        return $this->where('operation_type', $type);
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
