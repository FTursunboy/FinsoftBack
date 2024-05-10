<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Models\Department;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class DepartmentFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */

    protected $model = Department::class;

    public function name($value) :DepartmentFilter
    {
        return $this->whereDate('name', $value);
    }


    public function search(string $search) :DepartmentFilter
    {
        $searchTerm = explode(' ', $search);

        return $this->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');

        });
    }


    public function sort() :DepartmentFilter
    {
        $filteredParams = $this->input();

        if (!is_null($filteredParams['sort'])) {
            return  $this->orderBy($filteredParams['sort'], $filteredParams['direction']);
        }

        return $this->orderBy('deleted_at')->orderBy('created_at', 'desc');
    }

}
