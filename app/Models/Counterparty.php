<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class
Counterparty extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'email', 'deleted_at', 'created_at'];

    const PROVIDER = 'Поставщик';
    const CLIENT = 'Клиент';

    public static function bootSoftDeletes()
    {

    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class,'counterparty_roles','counterparty_id','role_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
        ];
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
            'address' => $data['filterData']['address'] ?? null,
            'email' => $data['filterData']['email'] ?? null,
            'roles' => $data['filterData']['roles'] ?? null,
        ];
    }

    public function cpAgreement(): HasMany
    {
        return $this->hasMany(CounterpartyAgreement::class);
    }
}
