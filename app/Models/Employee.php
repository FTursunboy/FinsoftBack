<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Employee extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'image', 'position_id', 'phone', 'email', 'address', 'group_id'];


    public static function bootSoftDeletes()
    {

    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }


    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'phone' => $data['filterData']['phone'] ?? null,
            'email' => $data['filterData']['email'] ?? null,
            'address' => $data['filterData']['address'] ?? null,
        ];
    }
}
