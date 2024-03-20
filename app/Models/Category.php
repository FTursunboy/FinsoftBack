<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Category extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'deleted_at'];

    public static function bootSoftDeletes()
    {

    }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name' => $data['filterData']['name'] ?? null,
        ];
    }
}
