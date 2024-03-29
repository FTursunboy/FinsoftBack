<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, SoftDeletes;

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

    public static function bootSoftDeletes()
    {

    }
}
