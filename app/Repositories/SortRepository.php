<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class SortRepository
{
    public static function sort(Model $model, array $filteredParams, $query, array $relations): Builder
    {
        if (!is_null($filteredParams['orderBy'])) {
            if (Str::contains($filteredParams['orderBy'], '.')) {
                list($relation, $field) = explode('.', $filteredParams['orderBy']);

                $relatedModel = new $model;
                $relatedModel = $relatedModel->$relation()->getRelated();
                $relatedTable = $relatedModel->getTable();

                $this_model = new $model;
                $this_table = $this_model->getTable();

                return $query->query(function ($query) use ($relation, $relatedTable, $this_table, $filteredParams, $field, $relations) {
                  return $query->with($relations)->join($relatedTable, "$this_table.{$relation}_id", '=', "{$relatedTable}.id")
                        ->orderBy("{$relatedTable}.{$field}", $filteredParams['direction'])
                        ->select("{$this_table}.*");
                });

            } else {
                return $query->query(function ($query) use ($relations) {
                    return $query->with($relations);
                })->orderBy($filteredParams['orderBy'], $filteredParams['direction']);
            }
        }
        return $query->query(function ($query) use ($relations) {
            $query->with($relations)->orderBy('deleted_at');
        });
    }


}
