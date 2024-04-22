<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class HiringFilter extends ModelFilter
{
    use Sort {
        sort as traitSort;
    }
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */


    public function date($value) :HiringFilter
    {
        return $this->whereDate('date', $value);
    }

    public function movement_date($value) :HiringFilter
    {
        return $this->whereDate('hiring_date', $value);
    }

    public function department($value) :HiringFilter
    {
        return $this->where('department_id', $value);
    }

    public function employee($value) :HiringFilter
    {
        return $this->where('employee_id', $value);
    }

    public function organization(int $id) :HiringFilter
    {
        return $this->where('organization_id', $id);
    }

    public function author(int $id) :HiringFilter
    {
        return $this->where('author_id', $id);
    }

    public function search(string $search) :HiringFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('employee', function ($query) use ($searchTerm) {
                    return $query->where('employees.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('position', function ($query) use ($searchTerm) {
                    return $query->where('positions.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }


    public function sort() :HiringFilter
    {
        $filteredParams = $this->input();

        $relations = ['position', 'department', 'organization', 'organization'];

        return $this->traitSort($filteredParams, $this, $relations);
    }

}
