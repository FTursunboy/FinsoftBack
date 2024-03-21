<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Storage extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'organization_id', 'group_id'];

    public function employeeStorage() :hasOne
    {
        return $this->hasOne(EmployeeStorage::class);
    }

    public function organization(): belongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function group(): belongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

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
            'name'  => $data['filterData']['name'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
        ];
    }

}
