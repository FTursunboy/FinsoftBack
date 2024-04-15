<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;

class MovementDocumentFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];



    public function date($value)
    {
        return $this->whereDate('date', $value);
    }

    public function senderStorage(int $id)
    {
        return $this->where('sender_storage_id', $id);
    }

    public function recipientStorage(int $id)
    {
        return $this->where('recipient_storage_id', $id);
    }

    public function organization(int $id)
    {

        return $this->where('organization_id', $id);
    }

    public function author(int $id)
    {
        return $this->where('author_id', $id);
    }


}
