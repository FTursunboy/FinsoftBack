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

    public function startDate($startDate) :InventoryOperation
    {
        return $this->whereDate('date', '>=', $startDate);
    }

    public function endDate($endDate) :InventoryOperation
    {
        return $this->whereDate('date', '<=', $endDate);
    }

    public function organization($value) : InventoryOperation
    {
        return $this->where('organization_id', $value);
    }

    public function currency($value) : InventoryOperation
    {
        return $this->where('organization_id', $value);
    }

    public function storage($value) : InventoryOperation
    {
        return $this->where('storage_id', $value);
    }

    public function author($value) : InventoryOperation
    {
        return $this->where('author_id', $value);
    }

    public function active($value) :CashStoreFilter
    {
        return $value ? $this->where('active',  true) : $this->where('active', false);
    }

    public function deleted($value) :CashStoreFilter
    {
        return $value ? $this->where('deleted_at', '!=', null) : $this->where('deleted_at', null);
    }


    public function sort1() :InventoryOperation
    {
        $filteredParams = $this->input();

        return $this->sort($filteredParams, $this->query, ['organization', 'storage', 'author', 'responsiblePerson']);
    }
}
