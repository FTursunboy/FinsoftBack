<?php

namespace App\Filters;

use App\Models\CashStore;
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


    public function sort() :MovementDocumentFilter
    {
        $filteredParams = $this->input();

        $relations = ['senderStorage', 'recipientStorage', 'author', 'organization', 'goods'];

        if (!is_null($filteredParams['sort'])) {
            if (Str::contains($filteredParams['sort'], '.')) {
                list($relation, $field) = explode('.', $filteredParams['sort']);

                $relatedTable = $this->getModel()->$relation()->getModel()->getTable();

                $thisTable = $this->getModel()->getTable();

                return $this->with($relations)->join($relatedTable, "$thisTable.{$relation}_id", '=', "{$relatedTable}.id")
                    ->orderBy("{$relatedTable}.{$field}", $filteredParams['direction'])
                    ->select("{$thisTable}.*");
            }

            return  $this->orderBy($filteredParams['sort'], $filteredParams['direction']);
        }

        return $this->orderBy('deleted_at')->orderBy('created_at', 'desc');
    }

}
