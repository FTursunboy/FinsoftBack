<?php

namespace App\Filters;

use App\Traits\Sort;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class EmployeeMovementFilter extends ModelFilter
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


    public function date($value) :EmployeeMovementFilter
    {
        return $this->whereDate('date', $value);
    }

    public function hiring_date($value) :EmployeeMovementFilter
    {
        return $this->whereDate('hiring_date', $value);
    }


    public function organization(int $id) :EmployeeMovementFilter
    {
        return $this->where('organization_id', $id);
    }

    public function department(int $id) :EmployeeMovementFilter
    {
        return $this->where('department_id', $id);
    }

    public function position(int $id) :EmployeeMovementFilter
    {
        return $this->where('position_id', $id);
    }

    public function employee(int $id) :EmployeeMovementFilter
    {
        return $this->where('employee_id', $id);
    }

    public function author(int $id) :EmployeeMovementFilter
    {
        return $this->where('author_id', $id);
    }

    public function search(string $search) :EmployeeMovementFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('position', function ($query) use ($searchTerm) {
                    return $query->where('positions.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('employee_id', function ($query) use ($searchTerm) {
                    return $query->where('employees.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('department', function ($query) use ($searchTerm) {
                    return $query->where('departments.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });

        });
    }


    public function sort() :EmployeeMovementFilter
    {
        $filteredParams = $this->input();

        $relations = ['position', 'department', 'organization', 'employee'];

        return $this->traitSort($filteredParams, $this, $relations);
    }

}
