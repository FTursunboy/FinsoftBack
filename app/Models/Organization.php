<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Organization extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'address', 'description', 'INN', 'director_id', 'chief_accountant_id'];

    public static function bootSoftDeletes()
    {

    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'director_id', 'id');
    }

    public function chiefAccountant(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'chief_accountant_id', 'id');
    }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'address' => $data['filterData']['address'] ?? null,
            'description' => $data['filterData']['description'] ?? null,
            'INN' => $data['filterData']['INN'] ?? null,
            'director_id' => $data['filterData']['director_id'] ?? null,
            'chief_accountant_id' => $data['filterData']['chief_accountant_id'] ?? null,
            'deleted' => $data['filterData']['deleted'] ?? null
        ];
    }
}


