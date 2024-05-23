<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

trait Sort
{
    public function sort(array $filteredParams, $query, array $relations = [])
    {
        if (!is_null($filteredParams['sort'])) {
            if (Str::contains($filteredParams['sort'], '.')) {
                list($relation, $field) = explode('.', $filteredParams['sort']);

                $relatedTable = app($this->model)->$relation()->getRelated()->getTable();

                $thisTable = app($this->model)->getTable();

                return $query->with($relations)->join($relatedTable, "$thisTable.{$relation}_id", '=', "{$relatedTable}.id")
                    ->orderBy("{$relatedTable}.{$field}", $filteredParams['direction'])
                    ->select("{$thisTable}.*");
            }

            return $query->with($relations)->orderBy($filteredParams['sort'], $filteredParams['direction']);
        }

        $table = app($this->model)->getTable();

        if (Schema::hasColumn($table, 'deleted_at'))
            return $query->with($relations)->orderBy('deleted_at')->orderBy('created_at', 'desc');

        return $query->with($relations)->orderBy('created_at', 'desc');
    }


}
