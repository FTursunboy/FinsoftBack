<?php

namespace App\Filters;


use App\Traits\Sort;
use EloquentFilter\Filterable;
use EloquentFilter\ModelFilter;

class InventoryOperation extends ModelFilter
{
    use Sort, Filterable;
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    protected $model = InventoryOperation::class;

    public function startDate($startDate) :self
    {
        return $this->whereDate('date', '>=', $startDate);
    }

    public function endDate($endDate) :self
    {
        return $this->whereDate('date', '<=', $endDate);
    }

    public function organization($value) : self
    {
        return $this->where('organization_id', $value);
    }

    public function currency($value) : self
    {
        return $this->where('organization_id', $value);
    }

    public function storage($value) : self
    {
        return $this->where('storage_id', $value);
    }

    public function author($value) : self
    {
        return $this->where('author_id', $value);
    }

    public function active($value) :self
    {
        return $value ? $this->where('active',  true) : $this->where('active', false);
    }

    public function deleted($value) :self
    {
        return $value ? $this->where('deleted_at', '!=', null) : $this->where('deleted_at', null);
    }

    public function sort1() :self
    {
        $filteredParams = $this->input();

        return $this->sort($filteredParams, $this->query, ['organization', 'storage', 'author', 'responsiblePerson']);
    }
}
