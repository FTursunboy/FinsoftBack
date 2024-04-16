<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class MovementDocumentFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */


    public function date($value) :MovementDocumentFilter
    {
        return $this->whereDate('date', $value);
    }

    public function senderStorage(int $id) :MovementDocumentFilter
    {
        return $this->where('sender_storage_id', $id);
    }

    public function recipientStorage(int $id) :MovementDocumentFilter
    {
        return $this->where('recipient_storage_id', $id);
    }

    public function organization(int $id) :MovementDocumentFilter
    {
        return $this->where('organization_id', $id);
    }

    public function author(int $id) :MovementDocumentFilter
    {
        return $this->where('author_id', $id);
    }

    public function search(string $search) :MovementDocumentFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('senderStorage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('recipientStorage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }




    public function sort() :MovementDocumentFilter
    {
        $filteredParams = $this->input();
        $relations = ['senderStorage', 'recipientStorage', 'author', 'organization'];

        if (!is_null($filteredParams['sort'])) {
            if (Str::contains($filteredParams['sort'], '.')) {
                list($relation, $field) = explode('.', $filteredParams['sort']);

                $relatedTable = $this->$relation()->getRelated()->getTable();

                $thisTable = $this->getTable();

                return $this->join($relatedTable, "$thisTable.{$relation}_id", '=', "{$relatedTable}.id")
                    ->orderBy("{$relatedTable}.{$field}", $filteredParams['direction'])
                    ->select("{$thisTable}.*");
            }

            return  $this->with($relations)->orderBy($filteredParams['sort'], $filteredParams['direction']);
        }

        return $this->with($relations)->orderBy('deleted_at')->orderBy('created_at', 'desc');
    }

}
