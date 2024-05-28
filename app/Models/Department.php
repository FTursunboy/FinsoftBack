<?php

namespace App\Models;

use App\Repositories\Contracts\SoftDeleteInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model implements SoftDeleteInterface
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'name',
    ];

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'deleted' =>  $data['filterData']['deleted'] ?? null
        ];
    }

}
