<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FilterTrait
{
    protected function processSearchData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'currency_id' => $data['filterData']['currency_id'] ?? null,
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'name'  => $data['filterData']['name'] ?? null,
            'bill_number' => $data['filterData']['bill_number'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
            'comment' => $data['filterData']['comment'] ?? null
        ];
    }

    protected function orderFields($filteredParams, $query)
    {
        if (!is_null($filteredParams['orderBy'])) {
            if (Str::contains($filteredParams['orderBy'], '.')) {
                list($relation, $field) = explode('.', $filteredParams['orderBy']);

               return $query->query(function ($q) use ($relation, $field, $filteredParams) {
                    $q->with([$relation => function ($query) use ($field, $filteredParams) {
                        $query->orderBy($field, $filteredParams['direction']);
                    }]);
                });
            } else {

              return  $query->orderBy($filteredParams['orderBy'], $filteredParams['direction']);
            }
        }
    }
}
