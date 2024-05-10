<?php

namespace App\Filters;

use App\Models\InventoryDocument;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;

class InventoryDocumentFilter extends ModelFilter
{
    use Sort;
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    protected $model = InventoryDocument::class;

    public function date($value) : InventoryDocumentFilter
    {
        return $this->whereDate('date', $value);
    }

    public function organization($value) : InventoryDocumentFilter
    {
        return $this->whereDate('organization_id', $value);
    }

    public function storage($value) : InventoryDocumentFilter
    {
        return $this->whereDate('storage_id', $value);
    }

    public function author($value) : InventoryDocumentFilter
    {
        return $this->whereDate('author_id', $value);
    }

    public function responsiblePerson($value) : InventoryDocumentFilter
    {
        return $this->whereDate('responsible_person_id', $value);
    }

    public function search(string $search) : InventoryDocumentFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('responsiblePerson', function ($query) use ($searchTerm) {
                    return $query->where('employees.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function sort1() :InventoryDocumentFilter
    {
        $filteredParams = $this->input();

        return $this->sort($filteredParams, $this->query, ['organization', 'storage', 'author', 'responsiblePerson']);
    }
}
