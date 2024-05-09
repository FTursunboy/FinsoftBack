<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodGroup extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'is_good', 'is_service', 'deleted_at'];

    public function goods()
    {
        return $this->hasMany(Good::class);
    }

    public static function bootSoftDeletes() { }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'is_service' => $data['filterData']['is_service'] ?? null,
            'is_good' => $data['filterData']['is_good'] ?? null,
        ];
    }
}
