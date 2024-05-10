<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Models\Firing;
use App\Traits\Sort;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class FiringFilter extends ModelFilter
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

    protected $model = Firing::class;

    public function date($value) :FiringFilter
    {
        return $this->whereDate('date', $value);
    }

    public function hiring_date($value) :FiringFilter
    {
        return $this->whereDate('hiring_date', $value);
    }


    public function employee($value) :FiringFilter
    {
        return $this->where('employee_id', $value);
    }

    public function organization(int $id) :FiringFilter
    {
        return $this->where('organization_id', $id);
    }

    public function author(int $id) :FiringFilter
    {
        return $this->where('author_id', $id);
    }

    public function search(string $search) :FiringFilter
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
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }


    public function sort() :FiringFilter
    {
        $filteredParams = $this->input();

        $relations = ['organization', 'employee'];

        return $this->traitSort($filteredParams, $this, $relations);
    }


}
