<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\View\Compilers\Concerns\CompilesStyles;
use Laravel\Scout\Searchable;

class CashRegister extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, CompilesStyles, SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'currency_id',
        'organization_id',
        'responsible_person_id'
    ];

    public static function bootSoftDeletes()
    {

    }

    public function currency() :BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function organization() :BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id', 'id');
    }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'currency_id' => $data['filterData']['currency_id'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'responsible_person_id' => $data['filterData']['responsible_person_id'] ?? null,
            'deleted' => $data['filterData']['deleted'] ?? null,
        ];
    }
}
