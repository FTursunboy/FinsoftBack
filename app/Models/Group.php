<?php

namespace App\Models;

use App\Filters\MovementDocumentFilter;
use App\Filters\UserGroupFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, SoftDeletes, Filterable;

    const STORAGES = 0;
    const USERS = 1;
    const EMPLOYEES = 2;

    protected $fillable = ['name', 'type', 'deleted_at'];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function storages(): HasMany
    {
        return $this->hasMany(Storage::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'login' => $data['filterData']['login'] ?? null,
            'email' => $data['filterData']['email'] ?? null,
            'phone' => $data['filterData']['phone'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'position_id' => $data['filterData']['position_id'] ?? null,
            'group_id' => $data['filterData']['group_id'] ?? null,
            'deleted' => $data['filterData']['deleted'] ?? null,
        ];
    }

    public static function bootSoftDeletes()
    {

    }

    public function modelFilter()
    {
        return $this->provideFilter(UserGroupFilter::class);
    }
}
