<?php

namespace App\Filters;

use App\Models\MovementDocument;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;

class MovementDocumentFilter extends ModelFilter
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

    protected $model = MovementDocument::class;

    public function startDate($startDate) :MovementDocumentFilter
    {
        return $this->whereDate('date', '>=', $startDate);
    }

    public function endDate($endDate) :MovementDocumentFilter
    {
        return $this->whereDate('date', '<=', $endDate);
    }

    public function sender_storage(int $id) :MovementDocumentFilter
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
                ->orWhereHas('sender_storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('recipient_storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }


    public function sort() :MovementDocumentFilter
    {
        $relations = ['sender_storage', 'recipient_storage', 'author', 'organization', 'goods'];

        return $this->traitSort($this->input(), $this, $relations);
    }

}
